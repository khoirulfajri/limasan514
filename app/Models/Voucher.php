<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'kode',
        'tipe',
        'nilai',
        'minimal_transaksi',
        'kuota',
        'digunakan',
        'expired_at',
        'is_active'
    ];

    // Relasi dengan Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
