<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingInvoice;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    // ======================
    // GET FULL DATES
    // ======================
    public function getFullDates()
    {
        $rooms = Room::where('status', 'available')->count();
        $otaBuffer = 1;

        $totalRooms = $rooms - $otaBuffer;

        $bookings = Booking::where('status', '!=', 'cancelled')->get();

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

    // ======================
    // CHECK AVAILABILITY
    // ======================
    public function checkAvailability($checkin, $checkout, $excludeBookingId = null)
    {
        $bookedRooms = DB::table('booking_rooms')
            ->join('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')

            // hanya booking aktif
            ->where('bookings.status', '!=', 'cancelled')

            // EXCLUDE BOOKING SENDIRI
            ->when($excludeBookingId, function ($query) use ($excludeBookingId) {
                $query->where('bookings.id', '!=', $excludeBookingId);
            })

            // LOGIC OVERLAP
            ->where(function ($q) use ($checkin, $checkout) {

                $q->whereBetween('check_in', [$checkin, $checkout])
                    ->orWhereBetween('check_out', [$checkin, $checkout])
                    ->orWhere(function ($q) use ($checkin, $checkout) {

                        $q->where('check_in', '<=', $checkin)
                            ->where('check_out', '>=', $checkout);
                    });
            })

            ->pluck('room_id');

        return Room::whereNotIn('id', $bookedRooms)
            ->where('status', 'available')
            ->get();
    }

    // ======================
    // CEK VOUCHER
    // ======================
    public function cekVoucher(Request $r)
    {
        $voucher = Voucher::where('kode', $r->kode)->first();

        if (!$voucher || !$voucher->is_active || ($voucher->expired_at && now()->gt($voucher->expired_at))) {
            return response()->json(['status' => false]);
        }

        return response()->json([
            'status' => true,
            'tipe' => $voucher->tipe,
            'nilai' => $voucher->nilai
        ]);
    }

    // ======================
    // STORE (KE SESSION)
    // ======================
    public function store(Request $r)
    {
        $availableRooms = $this->checkAvailability($r->check_in, $r->check_out);

        if ($availableRooms->count() < $r->jumlah_kamar) {
            return back()->with('error', 'Kamar tidak tersedia');
        }

        $totalMalam = Carbon::parse($r->check_in)->diffInDays($r->check_out);

        if ($totalMalam <= 0) {
            return back()->with('error', 'Tanggal tidak valid');
        }

        // AMBIL HARGA DARI DB
        $room = Room::first();
        if (!$room) {
            return back()->with('error', 'Data kamar belum tersedia');
        }

        $harga = $room->harga_per_malam;

        $totalHarga = $totalMalam * $harga * $r->jumlah_kamar;

        // ======================
        // VOUCHER
        // ======================
        $diskon = 0;
        $voucher_id = null;

        if ($r->kode_voucher) {

            $voucher = Voucher::where('kode', $r->kode_voucher)->first();

            if (!$voucher || !$voucher->is_active) {
                return back()->with('error', 'Voucher tidak valid');
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

            if ($voucher->tipe == 'persen') {
                $diskon = ($voucher->nilai / 100) * $totalHarga;
            } else {
                $diskon = $voucher->nilai;
            }

            $diskon = min($diskon, $totalHarga);
            $voucher_id = $voucher->id;
        }

        $totalAkhir = $totalHarga - $diskon;

        // ======================
        // SESSION
        // ======================
        session([
            'booking_temp' => [
                'kode_booking' => generateKode('BK', 'bookings', 'kode_booking'),
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
                'harga_per_malam' => $harga,
            ]
        ]);

        return redirect('/payment');
    }

    // ======================
    // PAYMENT PAGE
    // ======================
    public function payment()
    {
        $booking = session('booking_temp');

        if (!$booking) {
            return redirect('/booking')->with('error', 'Session expired');
        }

        return view('frontend.page.payment', compact('booking'));
    }

    // ======================
    // CONFIRM BOOKING
    // ======================
    public function confirmBooking(Request $r)
    {
        $r->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        return DB::transaction(function () use ($r) {

            $data = session('booking_temp');

            if (!$data) {
                return redirect('/booking')->with('error', 'Session expired');
            }

            $availableRooms = $this->checkAvailability($data['check_in'], $data['check_out']);

            if ($availableRooms->count() < $data['jumlah_kamar']) {
                return redirect('/booking')->with('error', 'Kamar tidak tersedia');
            }

            $file = $r->file('bukti');
            $ext = $file->getClientOriginalExtension();
            $namaFile = $data['kode_booking'] . '.' . $ext;
            $file->storeAs('public/bukti', $namaFile);
            $bukti = 'bukti/' . $namaFile;

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

                'status' => 'pending',

                // PAYMENT
                'metode_pembayaran' => 'transfer',
                'status_pembayaran' => 'menunggu_verifikasi'
            ]);

            $rooms = $availableRooms->take($data['jumlah_kamar']);

            foreach ($rooms as $room) {
                DB::table('booking_rooms')->insert([
                    'booking_id' => $booking->id,
                    'room_id' => $room->id
                ]);
            }

            if ($data['voucher_id']) {
                Voucher::where('id', $data['voucher_id'])->increment('digunakan');
            }

            Mail::to($booking->email)->send(new BookingInvoice($booking));

            session()->forget('booking_temp');

            return redirect('/invoice/' . $booking->kode_booking);
        });
    }

    // ======================
    // INVOICE
    // ======================
    public function invoice($kode)
    {
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();
        return view('frontend.page.invoice', compact('booking'));
    }

    public function invoicePdf($kode)
    {
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        $pdf = PDF::loadView('frontend.page.invoice', compact('booking'));

        return $pdf->download('invoice.pdf');
    }

    // ======================
    // BOOKING PAGE
    // ======================
    public function booking()
    {
        $booking = session('booking_temp'); // ambil session
        $room = Room::where('nomor_kamar', '>=', 2)->first();
        $harga = $room->harga_per_malam;
        return view('frontend.page.booking', compact('booking', 'harga'));
    }

    // ======================
    // CEK BOOKING
    // ======================
    public function formCekBooking()
    {
        return view('frontend.page.cek-booking');
    }

    public function cekBooking(Request $request)
    {
        $booking = Booking::where('kode_booking', $request->kode_booking)
            ->orWhere('email', $request->kode_booking)
            ->first();

        return view('frontend.page.cek-booking', compact('booking'));
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
    // Cek ketersediaan kamar (AJAX)
    public function cekKamar(Request $r)
    {
        $available = $this->checkAvailability($r->checkin, $r->checkout);

        return response()->json([
            'sisa' => $available->count()
        ]);
    }
}
