<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\BookingConfirmedMail;
use App\Models\User;
use App\Models\Booking;
use App\Models\Finance;
use App\Models\Room;
use App\Models\Voucher;

class AdminController extends Controller
{
    // ======================
    // DASHBOARD
    // ======================
    public function dashboard()
    {
        $totalUser = User::count();
        $totalBooking = Booking::count();
        $totalPemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $dataSumber = Booking::select('sumber', DB::raw('count(*) as total'))
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

    // ======================
    // USER
    // ======================
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

        return back()->with('success', 'User berhasil ditambah');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'role' => $request->role
        ]);

        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return back()->with('success', 'User berhasil diubah');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    // ======================
    // BOOKINGS
    // ======================
    public function bookings(Request $request)
    {
        $query = Booking::query();

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

        return view('admin.bookings', compact('bookings', 'warning', 'sisaKamar'));
    }

    // ======================
    // CONFIRM BOOKING (MASUK FINANCE)
    // ======================
    public function confirmBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'confirmed') {
            return back()->with('error', 'Booking sudah dikonfirmasi');
        }

        DB::transaction(function () use ($booking) {

            $booking->update([
                'status' => 'confirmed',
                'status_pembayaran' => 'lunas',
                'tanggal_pembayaran' => now()
            ]);

            // 🔥 CEGAH DOUBLE FINANCE
            $exists = Finance::where('booking_id', $booking->id)->exists();

            if (!$exists) {
                Finance::create([
                    'kode_transaksi' => generateKode('INC', 'finances', 'kode_transaksi'),
                    'tipe' => 'pemasukan',
                    'jumlah' => $booking->total_harga,
                    'keterangan' => 'Booking ' . $booking->kode_booking,
                    'tanggal' => now(),
                    'booking_id' => $booking->id,
                    'sumber' => $booking->sumber
                ]);
            }

            Mail::to($booking->email)
                ->send(new BookingConfirmedMail($booking));
        });

        return back()->with('success', 'Booking berhasil dikonfirmasi');
    }

    // ======================
    // tambah data BOOKING ADMIN
    // ======================
    public function storeBooking(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $request->validate([
                'nama' => 'required',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'jumlah_kamar' => 'required|numeric|min:1'
            ]);

            // CEK KETERSEDIAAN
            $availableRooms = app(BookingController::class)
                ->checkAvailability($request->check_in, $request->check_out);

            if ($availableRooms->count() < $request->jumlah_kamar) {
                return back()->with('error', 'Kamar tidak cukup');
            }

            // HITUNG TOTAL
            $totalMalam = Carbon::parse($request->check_in)
                ->diffInDays($request->check_out);

            $room = Room::first();
            if (!$room) {
                return back()->with('error', 'Data kamar belum tersedia');
            }

            $harga = $room->harga_per_malam;
            $totalHarga = $totalMalam * $harga * $request->jumlah_kamar;

            $bukti = null;

            if ($request->hasFile('bukti_pembayaran')) {

                $file = $request->file('bukti_pembayaran');

                $namaFile = 'bukti/' . time() . '_' . $file->getClientOriginalName();

                $file->storeAs('public', $namaFile);

                $bukti = $namaFile;
            }

            // diskon
            $diskon = 0;
            $voucher_id = null;

            if ($request->kode_voucher) {

                $voucher = Voucher::where('kode', strtoupper($request->kode_voucher))->first();

                if ($voucher && $voucher->is_active) {

                    if ($voucher->tipe == 'persen') {
                        $diskon = ($voucher->nilai / 100) * $totalHarga;
                    } else {
                        $diskon = $voucher->nilai;
                    }

                    $diskon = min($diskon, $totalHarga);
                    $voucher_id = $voucher->id;
                }
            }

            $totalHarga -= $diskon;

            // SIMPAN BOOKING
            $booking = Booking::create([
                'kode_booking' => generateKode('BK', 'bookings', 'kode_booking'),

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

                'voucher_id' => $voucher_id,
                'diskon' => $diskon,

                'catatan' => $request->catatan,
                'sumber' => $request->sumber ?? 'website',

                'status' => $request->sumber != 'website' ? 'confirmed' : 'pending',

                'metode_pembayaran' => $request->metode_pembayaran ?? 'cash',
                'status_pembayaran' => $request->status_pembayaran ?? 'lunas',
                'bukti_pembayaran' => $bukti,
            ]);

            // ASSIGN ROOM
            $rooms = $availableRooms->take($request->jumlah_kamar);

            foreach ($rooms as $room) {
                DB::table('booking_rooms')->insert([
                    'booking_id' => $booking->id,
                    'room_id' => $room->id
                ]);
            }

            // FINANCE (JIKA LUNAS)
            if ($booking->status_pembayaran == 'lunas') {
                Finance::create([
                    'kode_transaksi' => generateKode('INC', 'finances', 'kode_transaksi'),
                    'tipe' => 'pemasukan',
                    'jumlah' => $booking->total_harga,
                    'keterangan' => 'Booking ' . $booking->kode_booking,
                    'tanggal' => now(),
                    'booking_id' => $booking->id,
                    'sumber' => $booking->sumber
                ]);
            }

            if ($booking['voucher_id']) {
                Voucher::where('id', $booking['voucher_id'])->increment('digunakan');
            }

            // EMAIL
            if (!in_array($booking->sumber, ['booking.com', 'agoda', 'tiket.com'])) {
                try {
                    Mail::to($booking->email)
                        ->send(new BookingConfirmedMail($booking));
                } catch (\Exception $e) {
                    // optional log
                }
            }

            return back()->with('success', 'Booking berhasil ditambahkan');
        });
    }

    // ======================
    // UPDATE BOOKING
    // ======================
    public function updateBooking(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {

            $booking = Booking::findOrFail($id);

            $request->validate([
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'jumlah_kamar' => 'required|numeric|min:1'
            ]);

            // 🔥 CEK KETERSEDIAAN
            $availableRooms = app(BookingController::class)
                ->checkAvailability($request->check_in, $request->check_out, $booking->id);

            if ($availableRooms->count() < $request->jumlah_kamar) {
                return back()->with('error', 'Kamar tidak cukup');
            }

            // 🔥 HITUNG ULANG
            $totalMalam = Carbon::parse($request->check_in)
                ->diffInDays($request->check_out);

            $room = Room::first();

            if (!$room) {
                return back()->with('error', 'Data kamar belum tersedia');
            }

            $harga = $room->harga_per_malam;
            $totalHarga = $totalMalam * $harga * $request->jumlah_kamar;

            $bukti = $booking->bukti_pembayaran;

            if ($request->hasFile('bukti_pembayaran')) {

                // hapus file lama (opsional tapi bagus)
                if ($booking->bukti_pembayaran && Storage::exists('public/' . $booking->bukti_pembayaran)) {
                    Storage::delete('public/' . $booking->bukti_pembayaran);
                }

                $file = $request->file('bukti_pembayaran');
                $namaFile = 'bukti/' . time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public', $namaFile);

                $bukti = $namaFile;
            }

            // diskon
            $diskon = 0;
            $voucher_id = null;

            if ($request->kode_voucher) {

                $voucher = Voucher::where('kode', strtoupper($request->kode_voucher))->first();

                if ($voucher && $voucher->is_active) {

                    if ($voucher->tipe == 'persen') {
                        $diskon = ($voucher->nilai / 100) * $totalHarga;
                    } else {
                        $diskon = $voucher->nilai;
                    }

                    $diskon = min($diskon, $totalHarga);
                    $voucher_id = $voucher->id;
                }
            }

            $totalHarga -= $diskon;

            // 🔥 UPDATE BOOKING
            $booking->update([
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

                'voucher_id' => $voucher_id,
                'diskon' => $diskon,

                'catatan' => $request->catatan,
                'sumber' => $request->sumber ?? 'website',

                'bukti_pembayaran' => $bukti,

                'metode_pembayaran' => $request->metode_pembayaran ?? 'transfer',
                'status_pembayaran' => $request->status_pembayaran ?? 'menunggu_verifikasi',
            ]);

            // 🔥 UPDATE ROOM RELATION
            DB::table('booking_rooms')->where('booking_id', $booking->id)->delete();

            $rooms = $availableRooms->take($request->jumlah_kamar);

            foreach ($rooms as $room) {
                DB::table('booking_rooms')->insert([
                    'booking_id' => $booking->id,
                    'room_id' => $room->id
                ]);
            }

            // ======================
            // 🔥 SYNC FINANCE
            // ======================
            $finance = Finance::where('booking_id', $booking->id)->first();

            if ($request->status_pembayaran != 'lunas') {

                if ($finance) {
                    $finance->delete();
                }
            } else {

                if ($finance) {
                    $finance->update([
                        'jumlah' => $totalHarga,
                        'keterangan' => 'Booking ' . $booking->kode_booking,
                        'tanggal' => now(),
                        'sumber' => $booking->sumber
                    ]);
                } else {
                    Finance::create([
                        'kode_transaksi' => generateKode('INC', 'finances', 'kode_transaksi'),
                        'tipe' => 'pemasukan',
                        'jumlah' => $totalHarga,
                        'keterangan' => 'Booking ' . $booking->kode_booking,
                        'tanggal' => now(),
                        'booking_id' => $booking->id,
                        'sumber' => $booking->sumber
                    ]);
                }
            }

            return redirect()->route('admin.bookings')
                ->with('success', 'Booking berhasil diupdate');
        });
    }

    // ======================
    // HALAMAN FORM BOOKING (BUAT & EDIT)
    // ======================
    public function formBooking($id = null)
    {
        $booking = $id ? Booking::findOrFail($id) : null;

        $room = \App\Models\Room::first();
        $harga = $room ? $room->harga_per_malam : 0;

        return view('admin.booking_form', compact('booking', 'harga'));
    }
    // ======================
    // CANCEL BOOKING (GANTI DELETE)
    // ======================
    public function deleteBooking($id)
    {
        Booking::findOrFail($id)->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'Booking dibatalkan');
    }

    // ======================
    // TRANSAKSI
    // ======================
    public function transaksi(Request $request)
    {
        $query = Finance::query();

        // SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%' . $request->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER TIPE
        if ($request->tipe && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
            $query->where('tipe', $request->tipe);
        }

        // FILTER TANGGAL
        if ($request->dari && $request->sampai) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        } elseif ($request->dari) {
            $query->whereDate('tanggal', '>=', $request->dari);
        } elseif ($request->sampai) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $data = $query->latest()->paginate(10);

        $pemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;

        return view('admin.transaksi', compact(
            'data',
            'pemasukan',
            'pengeluaran',
            'saldo',

        ));
    }

    public function updateTransaksi(Request $request, $id)
    {
        $finance = Finance::findOrFail($id);

        // blok jika dari booking
        if ($finance->booking_id) {
            return back()->with('error', 'Transaksi dari booking tidak bisa diedit');
        }

        $bukti = $finance->bukti;

        if ($request->hasFile('bukti')) {

            $file = $request->file('bukti');

            $folder = $request->tipe == 'pengeluaran'
                ? 'bukti_pengeluaran'
                : 'bukti_pemasukan';

            $namaFile = $folder . '/' . time() . '_' . $file->getClientOriginalName();

            $file->storeAs('public', $namaFile);

            $bukti = $namaFile;
        }

        $finance->update([
            'tanggal' => $request->tanggal,
            'tipe' => $request->tipe,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'bukti' => $bukti
        ]);

        return back()->with('success', 'Transaksi berhasil diupdate');
    }

    public function deleteTransaksi($id)
    {
        Finance::findOrFail($id)->delete();
        return back();
    }

    // ======================
    // LAPORAN
    // ======================
    public function laporan()
    {
        $data = Finance::latest()->get();

        $pemasukan = Finance::where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = Finance::where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;

        // 🔥 GRAFIK BULANAN (BALIKIN)
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

    public function storeTransaksi(Request $request)
    {
        // ======================
        // VALIDASI
        // ======================
        $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:1',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // ======================
        // KODE TRANSAKSI
        // ======================
        $kodePrefix = $request->tipe == 'pemasukan' ? 'INC' : 'EXP';

        // ======================
        // UPLOAD BUKTI
        // ======================
        $bukti = null;

        if ($request->hasFile('bukti')) {

            $file = $request->file('bukti');

            // folder beda biar rapi
            $folder = $request->tipe == 'pengeluaran'
                ? 'bukti_pengeluaran'
                : 'bukti_pemasukan';

            $namaFile = $folder . '/' . time() . '_' . $file->getClientOriginalName();

            $file->storeAs('public', $namaFile);

            $bukti = $namaFile;
        }

        // ======================
        // SIMPAN DATA
        // ======================
        Finance::create([
            'kode_transaksi' => generateKode($kodePrefix, 'finances', 'kode_transaksi'),
            'tipe' => $request->tipe,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'bukti' => $bukti,
            'sumber' => 'manual' // opsional tapi bagus untuk tracking
        ]);

        return back()->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function exportPDF(Request $r)
    {
        $query = Finance::query();

        // ======================
        // FILTER BULAN
        // ======================
        if ($r->bulan) {
            $query->whereMonth('tanggal', $r->bulan);
        }

        // ======================
        // FILTER TAHUN
        // ======================
        if ($r->tahun) {
            $query->whereYear('tanggal', $r->tahun);
        }

        // ======================
        // AMBIL DATA + URUTKAN
        // ======================
        $data = $query->orderBy('tanggal', 'asc')->get();

        // ======================
        // HITUNG TOTAL
        // ======================
        $pemasukan = $data->where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = $data->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;

        // ======================
        // GENERATE PDF
        // ======================
        $pdf = Pdf::loadView('admin.laporan_pdf', compact(
            'data',
            'pemasukan',
            'pengeluaran',
            'saldo'
        ));

        return $pdf->stream('laporan-keuangan.pdf');
    }
}
