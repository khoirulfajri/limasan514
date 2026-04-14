<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [

        'kode_booking',
        'user_id',

        'nama',
        'email',
        'no_telp',
        'jenis_kelamin',

        'jumlah_tamu',
        'jumlah_kamar',

        'check_in',
        'check_out',

        'total_malam',
        'total_harga',

        'catatan',
        'sumber',

        'voucher_id',
        'diskon',

        'status',
        'bukti_pembayaran',

        //PAYMENT
        'metode_pembayaran',
        'status_pembayaran',
        'tanggal_pembayaran',
        'jumlah_dibayar'
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'booking_rooms');
    }

    public function finance()
    {
        return $this->hasOne(Finance::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
