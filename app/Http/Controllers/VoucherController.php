<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        return view('admin.vouchers.index', compact('vouchers'));
    }
    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $r)
    {
        Voucher::create([
            'kode' => strtoupper($r->kode),
            'tipe' => $r->tipe,
            'nilai' => $r->nilai,
            'minimal_transaksi' => $r->minimal_transaksi,
            'kuota' => $r->kuota,
            'expired_at' => $r->expired_at,
            'is_active' => $r->is_active ?? 1,
        ]);

        return back()->with('success', 'Voucher berhasil ditambahkan');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function destroy($id)
    {
        Voucher::findOrFail($id)->delete();
        return back()->with('success', 'Voucher dihapus');
    }
}
