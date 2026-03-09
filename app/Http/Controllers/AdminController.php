<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Add this line to import the User model
use App\Models\Booking; // Add this line to import the Booking model
use App\Models\Finance; // Add this line to import the Finance model

class AdminController extends Controller
{
    public function dashboard()
    {

        $totalUser = User::count();

        $totalBooking = Booking::count();

        $pemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');

        $pengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');

        $saldo = $pemasukan - $pengeluaran;

        return view('admin.dashboard', compact(
            'totalUser',
            'totalBooking',
            'pemasukan',
            'pengeluaran',
            'saldo'
        ))->with('title', 'Dashboard');
    }

    // Halaman Booking
    public function bookings()
    {

        $bookings = Booking::latest()->get();

        return view('admin.bookings', compact('bookings'))->with('title', 'Bookings');
    }

    // update Status Booking otomatis masuk ke pemasukan
    public function confirmBooking($id)
    {

        $booking = Booking::findOrFail($id);

        $booking->update([
            'status' => 'confirmed'
        ]);

        Finance::create([

            'kode_transaksi' => 'INC' . time(),

            'tipe' => 'pemasukan',

            'jumlah' => $booking->total_harga,

            'keterangan' => 'Pembayaran booking ' . $booking->kode_booking,

            'tanggal' => now(),

            'booking_id' => $booking->id

        ]);

        return back()->with('success', 'Booking berhasil dikonfirmasi');
    }

    // Halaman Transaksi
    public function transaksi()
    {

        $data = Finance::latest()->get();

        $pemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');

        $pengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');

        $saldo = $pemasukan - $pengeluaran;

        return view('admin.transaksi', compact(
            'data',
            'pemasukan',
            'pengeluaran',
            'saldo'
        ))->with('title', 'Transaksi');
    }

    // store Pemasukan
    public function storePemasukan(Request $request)
    {

        Finance::create([

            'kode_transaksi' => 'INC' . time(),

            'tipe' => 'pemasukan',

            'jumlah' => $request->jumlah,

            'keterangan' => $request->keterangan,

            'tanggal' => $request->tanggal

        ]);

        return back()->with('success', 'Pemasukan berhasil ditambahkan');
    }

    // tambah PEngeluaran
    public function storePengeluaran(Request $request)
    {

        Finance::create([

            'kode_transaksi' => 'OUT' . time(),

            'tipe' => 'pengeluaran',

            'jumlah' => $request->jumlah,

            'keterangan' => $request->keterangan,

            'tanggal' => $request->tanggal

        ]);

        return back()->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    // Laporan Keuangan
    public function laporan()
    {

        $pemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');

        $pengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');

        $saldo = $pemasukan - $pengeluaran;

        $data = Finance::latest()->get();

        return view('admin.laporan', compact(
            'pemasukan',
            'pengeluaran',
            'saldo',
            'data'
        ));
    }
}
