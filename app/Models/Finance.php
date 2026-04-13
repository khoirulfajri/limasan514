<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $fillable = [

        'kode_transaksi',
        'tipe',
        'jumlah',
        'keterangan',
        'tanggal',
        'booking_id',
        'sumber'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
