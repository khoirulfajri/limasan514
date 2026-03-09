<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insert([
            ['nomor_kamar' => '1', 'lantai' => 1],
            ['nomor_kamar' => '2', 'lantai' => 1],
            ['nomor_kamar' => '3', 'lantai' => 2],
            ['nomor_kamar' => '4', 'lantai' => 2],
            ['nomor_kamar' => '5', 'lantai' => 2],
        ]);
    }
}