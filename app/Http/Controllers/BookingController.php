<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room; // Add this line to import the Room model
use App\Models\Booking; // Add this line to import the Booking model
use App\Models\Voucher; // Add this line to import the Voucher model
use Carbon\Carbon; // Add this line to import the Carbon class
use Illuminate\Support\Facades\Auth; // Add this line to import the Auth facade
use Illuminate\Support\Facades\Mail; // Add this line to import the Mail facade
use App\Mail\BookingInvoice; // Import the BookingInvoice class from the correct namespace
use Barryvdh\DomPDF\Facade\Pdf; // Add this line to import the PDF facade

class BookingController extends Controller
{
    // mendapatkan tanggal penuh (tidak tersedia)
    public function getFullDates()
    {
        $totalRooms = \App\Models\Room::count();

        $bookings = \App\Models\Booking::where('status', '!=', 'cancelled')->get();

        $dates = [];

        foreach ($bookings as $booking) {

            $start = strtotime($booking->check_in);
            $end = strtotime($booking->check_out);

            for ($date = $start; $date < $end; $date += 86400) {

                $d = date('Y-m-d', $date);

                if (!isset($dates[$d])) {
                    $dates[$d] = 0;
                }

                $dates[$d] += $booking->jumlah_kamar;
            }
        }

        $fullDates = [];

        foreach ($dates as $date => $total) {
            if ($total >= $totalRooms) {
                $fullDates[] = $date;
            }
        }

        return response()->json($fullDates);
    }

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

    // Cek voucher
    public function cekVoucher(Request $r)
    {
        $voucher = Voucher::where('kode', $r->kode)->first();

        if (!$voucher) {
            return response()->json(['status' => false]);
        }

        if (!$voucher->is_active) {
            return response()->json(['status' => false]);
        }

        if ($voucher->expired_at && now()->gt($voucher->expired_at)) {
            return response()->json(['status' => false]);
        }

        return response()->json([
            'status' => true,
            'tipe' => $voucher->tipe,
            'nilai' => $voucher->nilai
        ]);
    }

    // Simpan booking
    public function store(Request $r)
    {
        // cek ketersediaan kamar
        $availableRooms = $this->checkAvailability($r->check_in, $r->check_out);

        if ($availableRooms->count() < $r->jumlah_kamar) {
            return back()->with('error', 'Kamar tidak tersedia');
        }

        // hitung malam
        $totalMalam = Carbon::parse($r->check_in)
            ->diffInDays($r->check_out);

        if ($totalMalam <= 0) {
            return back()->with('error', 'Tanggal tidak valid');
        }

        $harga = 350000;

        $totalHarga = $totalMalam * $harga * $r->jumlah_kamar;

        // ======================
        // VOUCHER LOGIC
        // ======================
        $diskon = 0;
        $voucher_id = null;
        $voucher = null;

        if ($r->kode_voucher) {

            $voucher = Voucher::where('kode', $r->kode_voucher)->first();

            if (!$voucher) {
                return back()->with('error', 'Voucher tidak ditemukan');
            }

            if (!$voucher->is_active) {
                return back()->with('error', 'Voucher tidak aktif');
            }

            if ($voucher->expired_at && now()->gt($voucher->expired_at)) {
                return back()->with('error', 'Voucher expired');
            }

            if ($voucher->kuota && $voucher->digunakan >= $voucher->kuota) {
                return back()->with('error', 'Voucher habis');
            }

            if ($voucher->minimal_transaksi && $totalHarga < $voucher->minimal_transaksi) {
                return back()->with('error', 'Minimal transaksi belum terpenuhi');
            }

            // hitung diskon
            if ($voucher->tipe == 'persen') {
                $diskon = ($voucher->nilai / 100) * $totalHarga;
            } else {
                $diskon = $voucher->nilai;
            }

            // biar tidak lebih besar dari total
            $diskon = min($diskon, $totalHarga);

            $voucher_id = $voucher->id;
        }

        $totalAkhir = $totalHarga - $diskon;

        // ======================
        // SIMPAN KE SESSION (BUKAN DB)
        // ======================
        $kodeBooking = 'BK' . time();

        session([
            'booking_temp' => [
                'kode_booking' => $kodeBooking,
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
                'total_harga' => $totalAkhir,

                'catatan' => $r->catatan,
                'sumber' => 'website',

                'voucher_id' => $voucher_id,
                'diskon' => $diskon,
            ]
        ]);
        // redirect ke halaman preview
        return redirect('/payment');
    }

    // payment page
    public function payment()
    {
        $booking = session('booking_temp');

        if (!$booking) {
            return redirect('/booking')->with('error', 'Session expired');
        }

        return view('frontend.page.payment', compact('booking'));
    }

    // confirm booking (pindahkan dari session ke DB)

    public function confirmBooking(Request $r)
    {
        // ======================
        // 🔒 VALIDASI
        // ======================
        $r->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // ambil data dari session
        $data = session('booking_temp');

        if (!$data) {
            return redirect('/booking')->with('error', 'Session expired');
        }

        // ======================
        // 🔥 UPLOAD BUKTI
        // ======================
        $bukti = null;

        if ($r->hasFile('bukti')) {
            $bukti = $r->file('bukti')->store('bukti', 'public');
        }

        // ======================
        // 🔥 SIMPAN BOOKING
        // ======================
        $booking = Booking::create([
            'kode_booking' => $data['kode_booking'],
            'user_id' => $data['user_id'],

            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_telp' => $data['no_telp'],
            'jenis_kelamin' => $data['jenis_kelamin'],

            'jumlah_tamu' => $data['jumlah_tamu'],
            'jumlah_kamar' => $data['jumlah_kamar'],

            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],

            'total_malam' => $data['total_malam'],
            'total_harga' => $data['total_harga'],

            'catatan' => $data['catatan'],
            'sumber' => $data['sumber'],

            'voucher_id' => $data['voucher_id'],
            'diskon' => $data['diskon'],

            'bukti_pembayaran' => $bukti,
            'status' => 'pending'
        ]);

        // ======================
        // 🔥 ASSIGN ROOM
        // ======================
        $availableRooms = $this->checkAvailability($data['check_in'], $data['check_out']);

        if ($availableRooms->count() < $data['jumlah_kamar']) {
            return redirect('/booking')->with('error', 'Kamar sudah tidak tersedia');
        }

        $rooms = $availableRooms->take($data['jumlah_kamar']);

        foreach ($rooms as $room) {
            DB::table('booking_rooms')->insert([
                'booking_id' => $booking->id,
                'room_id' => $room->id
            ]);
        }

        // ======================
        // 🔥 UPDATE VOUCHER
        // ======================
        if ($data['voucher_id']) {
            Voucher::where('id', $data['voucher_id'])->increment('digunakan');
        }

        // ======================
        // 🔥 KIRIM EMAIL
        // ======================
        Mail::to($booking->email)
            ->send(new BookingInvoice($booking));

        // ======================
        // 🧹 HAPUS SESSION
        // ======================
        session()->forget('booking_temp');

        // ======================
        // 🚀 REDIRECT
        // ======================
        return redirect('/invoice/' . $booking->kode_booking)
            ->with('success', 'Booking berhasil dibuat, menunggu konfirmasi admin');
    }


    // upload bukti pembayaran
    // public function upload(Request $r)
    // {

    //     $file = $r->file('bukti')->store('bukti', 'public');
    //     $booking = Booking::where('kode_booking', $r->kode_booking)->firstOrFail();
    //     $booking->update([
    //         'bukti_pembayaran' => $file,
    //         'status' => 'pending'
    //     ]);

    //     Mail::to($booking->email)
    //         ->send(new BookingInvoice($booking));

    //     return redirect('/invoice/' . $r->kode_booking);
    // }

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
