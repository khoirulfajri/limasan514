<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Add this line to import the User model
use App\Models\Booking; // Add this line to import the Booking model
use App\Models\Finance; // Add this line to import the Finance model

class AdminController extends Controller
{
    public function dashboard()
    {

        $totalUser = User::count();
        $totalBooking = Booking::count();
        $totalPemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $grafik = Finance::selectRaw("MONTH(tanggal) as bulan,
        SUM(CASE WHEN tipe='pemasukan' THEN jumlah ELSE 0 END) as pemasukan,
        SUM(CASE WHEN tipe='pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran 
        ")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $pendingBooking = Booking::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalUser',
            'totalBooking',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'pendingBooking',
            'grafik'
        ));
    }

    // halaman User
    public function users()
    {
        $users = User::latest()->get();

        return view('admin.users', compact('users'));
    }


    public function storeUser(Request $request)
    {

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'role' => $request->role,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambah');
    }


    public function updateUser(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->no_telp = $request->no_telp;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User berhasil diubah');
    }


    public function deleteUser($id)
    {

        User::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus');
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
    // Tambah Booking
    public function storeBooking(Request $request)
    {

        $bukti = null;

        if ($request->hasFile('bukti_pembayaran')) {

            $file = $request->file('bukti_pembayaran');

            $namaFile = 'bukti/' . time() . '_' . $file->getClientOriginalName();

            $file->storeAs('public/bukti', $namaFile);

            $bukti = $namaFile;
        }

        Booking::create([

            'kode_booking' => 'BK' . time(),

            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,

            'jumlah_tamu' => $request->jumlah_tamu,
            'jumlah_kamar' => $request->jumlah_kamar,

            'check_in' => $request->check_in,
            'check_out' => $request->check_out,

            'total_malam' => $request->total_malam,
            'total_harga' => $request->total_harga,

            'catatan' => $request->catatan,

            'bukti_pembayaran' => $bukti,

            'status' => 'pending'

        ]);

        return back();
    }
    // ubah Booking
    public function updateBooking(Request $request, $id)
    {

        $booking = Booking::findOrFail($id);

        $booking->update([

            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,

            'jumlah_tamu' => $request->jumlah_tamu,
            'jumlah_kamar' => $request->jumlah_kamar,

            'check_in' => $request->check_in,
            'check_out' => $request->check_out,

            'total_malam' => $request->total_malam,
            'total_harga' => $request->total_harga,

            'catatan' => $request->catatan

        ]);

        return back()->with('success', 'Booking berhasil diupdate');
    }
    // hapus Booking
    public function deleteBooking($id)
    {

        Booking::findOrFail($id)->delete();

        return back()->with('success', 'Booking berhasil dihapus');
    }

    // Halaman Transaksi
    public function transaksi(Request $request)
    {

        $query = Finance::query();

        if ($request->search) {
            $query->where('kode_transaksi', 'like', '%' . $request->search . '%')
                ->orWhere('keterangan', 'like', '%' . $request->search . '%');
        }

        $data = $query->latest()->paginate(10);

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
    // hapus data Transaksi
    public function deleteTransaksi($id)
    {
        Finance::findOrFail($id)->delete();
        return back();
    }

    public function storeTransaksi(Request $request)
    {

        $kode = $request->tipe == 'pemasukan' ? 'INC' : 'EXP';

        Finance::create([

            'kode_transaksi' => $kode . time(),

            'tipe' => $request->tipe,

            'jumlah' => $request->jumlah,

            'keterangan' => $request->keterangan,

            'tanggal' => $request->tanggal

        ]);

        return back()->with('success', 'Transaksi berhasil ditambahkan');
    }

    // tambah Pengeluaran
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

        $data = Finance::latest()->get();

        $pemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');

        $pengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');

        $saldo = $pemasukan - $pengeluaran;

        // grafik bulanan
        $grafik = Finance::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw("SUM(CASE WHEN tipe='pemasukan' THEN jumlah ELSE 0 END) as pemasukan"),
            DB::raw("SUM(CASE WHEN tipe='pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran")
        )
            ->whereYear('tanggal', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulan = [];
        $dataPemasukan = [];
        $dataPengeluaran = [];

        $namaBulan = [
            1 => 'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        foreach ($grafik as $g) {

            $bulan[] = $namaBulan[$g->bulan];
            $dataPemasukan[] = $g->pemasukan;
            $dataPengeluaran[] = $g->pengeluaran;
        }

        return view('admin.laporan', compact(
            'data',
            'pemasukan',
            'pengeluaran',
            'saldo',
            'bulan',
            'dataPemasukan',
            'dataPengeluaran'
        ));
    }
    // export Laporan PDF
    public function exportPDF()
    {

        $data = Finance::orderBy('tanggal', 'asc')->get();
        $pemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;

        $pdf = Pdf::loadView('admin.laporan_pdf', compact(
            'data',
            'pemasukan',
            'pengeluaran',
            'saldo'
        ));

        return $pdf->download('laporan-keuangan.pdf');
    }
}
