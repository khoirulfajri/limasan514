<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon; // Add this line to import the Carbon class
use App\Mail\BookingConfirmedMail; // Add this line to import the BookingConfirmedMail class
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

        $dataSumber = \App\Models\Booking::select('sumber', DB::raw('count(*) as total'))
            ->groupBy('sumber')
            ->pluck('total', 'sumber');

        $pendingBooking = Booking::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalUser',
            'totalBooking',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'pendingBooking',
            'dataSumber'
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
    public function bookings(Request $request)
    {
        $query = Booking::query();

        // FILTER SUMBER
        if ($request->has('sumber') && $request->sumber != '') {
            $query->where('sumber', $request->sumber);
        }

        $bookings = $query->latest()->get();

        // 🔥 CEK KAMAR HARI INI
        $today = now()->format('Y-m-d');

        $availableRooms = app(BookingController::class)
            ->checkAvailability($today, $today);

        $sisaKamar = $availableRooms->count();

        $warning = null;

        if ($sisaKamar <= 1) {
            $warning = "⚠️ Sisa {$sisaKamar} kamar hari ini!";
        } elseif ($sisaKamar <= 3) {
            $warning = "⚠️ Kamar mulai menipis! Sisa {$sisaKamar}";
        }

        return view('admin.bookings', compact('bookings', 'warning', 'sisaKamar'))
            ->with('title', 'Bookings');
    }

    // update Status Booking otomatis masuk ke pemasukan
    public function confirmBooking($id)
    {

        $booking = Booking::findOrFail($id);

        //  cegah double confirm
        if ($booking->status === 'confirmed') {
            return back()->with('error', 'Booking sudah dikonfirmasi');
        }

        //  update status
        $booking->update([
            'status' => 'confirmed'
        ]);

        //  kirim email
        Mail::to($booking->email)
            ->send(new BookingConfirmedMail($booking));

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
        // ======================
        // 🔒 VALIDASI
        // ======================
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
            'jumlah_kamar' => 'required|numeric|min:1',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'sumber' => 'nullable|in:website,booking.com,agoda,tiket.com,on_the_spot',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // ======================
        // 🔥 UPLOAD BUKTI
        // ======================
        $bukti = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $bukti = $request->file('bukti_pembayaran')->store('bukti', 'public');
        }

        // ======================
        // 🔥 HITUNG TOTAL
        // ======================
        $totalMalam = Carbon::parse($request->check_in)
            ->diffInDays($request->check_out);

        $harga = 350000;

        $totalHarga = $totalMalam * $harga * $request->jumlah_kamar;

        // ======================
        // 🔥 SIMPAN BOOKING
        // ======================
        $booking = Booking::create([

            'kode_booking' => 'BK' . time(),

            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,

            'jumlah_tamu' => $request->jumlah_tamu,
            'jumlah_kamar' => $request->jumlah_kamar,

            'check_in' => $request->check_in,
            'check_out' => $request->check_out,

            'total_malam' => $totalMalam,
            'total_harga' => $totalHarga,

            'catatan' => $request->catatan,
            'sumber' => $request->sumber ?? 'website',

            'bukti_pembayaran' => $bukti,

            'status' => $request->sumber != 'website' ? 'confirmed' : 'pending'
        ]);

        // ======================
        // 🔥 ASSIGN ROOM (WAJIB)
        // ======================
        $availableRooms = app(\App\Http\Controllers\BookingController::class)
            ->checkAvailability($request->check_in, $request->check_out);

        if ($availableRooms->count() < $request->jumlah_kamar) {
            return back()->with('error', 'Kamar tidak cukup');
        }

        $rooms = $availableRooms->take($request->jumlah_kamar);

        foreach ($rooms as $room) {
            DB::table('booking_rooms')->insert([
                'booking_id' => $booking->id,
                'room_id' => $room->id
            ]);
        }

        return back()->with('success', 'Booking berhasil ditambahkan');
    }

    // ubah Booking
    public function updateBooking(Request $request, $id)
    {

        $booking = Booking::findOrFail($id);

        $request->validate([
            'sumber' => 'nullable|in:website,booking.com,agoda,tiket.com,on_the_spot'
        ]);

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

            'catatan' => $request->catatan,
            'sumber' => $request->sumber ?? 'website'

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
    public function exportPDF(Request $r)
    {
        $query = Finance::query();

        if ($r->bulan) {
            $query->whereMonth('tanggal', $r->bulan);
        }

        if ($r->tahun) {
            $query->whereYear('tanggal', $r->tahun);
        }

        $data = $query->get();

        // ======================
        // PEMASUKAN
        // ======================
        $pemasukan = $data->where('tipe', 'pemasukan')->sum('jumlah');

        // detail pemasukan (per keterangan)
        $detailPemasukan = $data->where('tipe', 'pemasukan')
            ->groupBy('keterangan');

        // ======================
        // PENGELUARAN
        // ======================
        $pengeluaran = $data->where('tipe', 'pengeluaran')->sum('jumlah');

        $detailPengeluaran = $data->where('tipe', 'pengeluaran')
            ->groupBy('keterangan');

        $saldo = $pemasukan - $pengeluaran;

        $pdf = \PDF::loadView('admin.laporan_pdf', compact(
            'pemasukan',
            'pengeluaran',
            'saldo',
            'detailPemasukan',
            'detailPengeluaran',
            'r'
        ));

        return $pdf->stream();
    }
}
