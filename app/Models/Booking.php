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

        'status',
        'bukti_pembayaran'

    ];

    public function rooms()
    {

        return $this->belongsToMany(Room::class, 'booking_rooms');
    }
}
