<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voucher::create([
            'kode' => 'DISKON10',
            'tipe' => 'persen',
            'nilai' => 10,
            'minimal_transaksi' => 300000,
            'kuota' => 10,
            'expired_at' => now()->addDays(30),
            'is_active' => true
        ]);
        
        Voucher::create([
            'kode' => 'HEMAT50K',
            'tipe' => 'nominal',
            'nilai' => 50000,
            'minimal_transaksi' => 200000,
            'kuota' => 5,
            'expired_at' => now()->addDays(15),
            'is_active' => true
        ]);
    }
}
