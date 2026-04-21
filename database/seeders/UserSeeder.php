<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'nama' => 'Admin Limasan',
                'email' => 'admin@gmail.com',
                'no_telp' => '085943564269',
                'jenis_kelamin' => 'P',
                'password' => Hash::make('12345'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Owner Limasan',
                'email' => 'owner@gmail.com',
                'no_telp' => '085943564269',
                'jenis_kelamin' => 'L',
                'password' => Hash::make('owner12345'),
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
