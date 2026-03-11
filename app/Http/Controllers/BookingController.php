<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room; // Add this line to import the Room model
use App\Models\Booking; // Add this line to import the Booking model
use Carbon\Carbon; // Add this line to import the Carbon class
use Illuminate\Support\Facades\Auth; // Add this line to import the Auth facade
use Illuminate\Support\Facades\Mail; // Add this line to import the Mail facade
use App\Mail\BookingInvoice; // Import the BookingInvoice class from the correct namespace
use Barryvdh\DomPDF\Facade\Pdf; // Add this line to import the PDF facade

class BookingController extends Controller
{
    // Cek ketersediaan kamar
    private function checkAvailability($checkin, $checkout)
    {

        $bookedRooms = DB::table('booking_rooms')
            ->join('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')

            ->where(function ($q) use ($checkin, $checkout) {

                $q->whereBetween('check_in', [$checkin, $checkout])
                    ->orWhereBetween('check_out', [$checkin, $checkout])
                    ->orWhere(function ($q) use ($checkin, $checkout) {

                        $q->where('check_in', '<=', $checkin)
                            ->where('check_out', '>=', $checkout);
                    });
            })

            ->pluck('room_id');

        return Room::whereNotIn('id', $bookedRooms)->get();
    }

    // Simpan booking
    public function store(Request $r)
    {

        $availableRooms = $this->checkAvailability($r->check_in, $r->check_out);

        if ($availableRooms->count() < $r->jumlah_kamar) {

            return back()->with('error', 'Kamar tidak tersedia');
        }

        $totalMalam = Carbon::parse($r->check_in)
            ->diffInDays($r->check_out);

        $harga = 350000;

        $totalHarga = $totalMalam * $harga * $r->jumlah_kamar;

        $booking = Booking::create([

            'kode_booking' => 'BK' . time(),

            'user_id' => Auth::id(),

            'nama' => $r->nama,
            'email' => $r->email,
            'no_telp' => $r->no_telp,
            'jenis_kelamin' => $r->jenis_kelamin,

            'jumlah_tamu' => $r->jumlah_tamu,
            'jumlah_kamar' => $r->jumlah_kamar,

            'check_in' => $r->check_in,
            'check_out' => $r->check_out,

            'total_malam' => $totalMalam,
            'total_harga' => $totalHarga,

            'catatan' => $r->catatan

        ]);

        $rooms = $availableRooms->take($r->jumlah_kamar);

        foreach ($rooms as $room) {

            DB::table('booking_rooms')->insert([

                'booking_id' => $booking->id,
                'room_id' => $room->id

            ]);
        }

        return redirect('/payment/' . $booking->kode_booking);
    }

    // payment page
    public function payment($kode)
    {

        $booking = Booking::where('kode_booking', $kode)->first();

        return view('frontend.page.payment', compact('booking'))->with('title', 'Payment');
    }

    // upload bukti pembayaran
    public function upload(Request $r)
    {

        $file = $r->file('bukti')->store('bukti', 'public');
        $booking = Booking::where('kode_booking', $r->kode_booking)->firstOrFail();
        $booking->update([
            'bukti_pembayaran' => $file,
            'status' => 'pending'
        ]);

        Mail::to($booking->email)
            ->send(new BookingInvoice($booking));

        return redirect('/invoice/' . $r->kode_booking);
    }

    // invoice page
    public function invoice($kode)
    {

        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        return view('frontend.page.invoice', compact('booking'))->with('title', 'Invoice');
    }

    // invoice PDf
    public function invoicePdf($kode)
    {

        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        $title = "InvoicePDF";

        $pdf = PDF::loadView('frontend.page.invoice', compact('booking', 'title'));

        return $pdf->download('invoice.pdf');
    }
    // tanggal penuh
    public function disabledDates()
    {

        $dates = Booking::select('check_in', 'check_out')->get();

        return response()->json($dates);
    }

    // booking page
    public function booking()
    {
        return view('frontend.page.booking', [
            'title' => 'Booking',
        ]);
    }

    // Test email
    public function testEmail()
    {

        $data = [
            'nama' => 'Test User',
            'pesan' => 'Ini adalah email test dari Laravel'
        ];

        \Illuminate\Support\Facades\Mail::send(
            'emails.test',
            $data,
            function ($message) {

                $message->to('nisfinuril57@gmail.com')
                    ->subject('Test Email Laravel');
            }
        );

        return "Email berhasil dikirim (cek inbox)";
    }

    // halaman form booking
    public function formCekBooking()
    {
        return view('frontend.page.cek-booking')->with('title', 'Cek Booking');
    }

    public function cekBooking(Request $request)
    {
        $booking = Booking::where('kode_booking', $request->kode_booking)
            ->orWhere('email', $request->kode_booking)
            ->first();
            
        return view('frontend.page.cek-booking', compact('booking'))->with('title', 'Cek Booking');
    }

    // Upload bukti pembayaran
    public function uploadBukti(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $file = $request->file('bukti');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/bukti', $namaFile);

        $booking->bukti_pembayaran = $namaFile;
        $booking->save();

        return back()->with('success', 'Bukti pembayaran berhasil diupload');
    }
}
