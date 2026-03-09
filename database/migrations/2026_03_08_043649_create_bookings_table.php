<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            $table->string('kode_booking')->unique();

            $table->foreignId('user_id')->nullable();

            $table->string('nama');
            $table->string('email');
            $table->string('no_telp');
            $table->string('jenis_kelamin');

            $table->integer('jumlah_tamu');

            $table->integer('jumlah_kamar');

            $table->date('check_in');
            $table->date('check_out');

            $table->integer('total_malam');

            $table->bigInteger('total_harga');

            $table->text('catatan')->nullable();

            $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                ->default('pending');

            $table->string('bukti_pembayaran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
