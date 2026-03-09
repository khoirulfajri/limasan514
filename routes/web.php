<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController; // Add this line
use App\Http\Controllers\AuthController; // Add this line
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [Controller::class, 'index'])->name('home');
Route::get('/login', [Controller::class, 'login'])->name('login');
Route::get('/register', [Controller::class, 'register'])->name('register');

Route::get('/booking', [BookingController::class, 'booking']);
Route::post('/booking/store', [BookingController::class, 'store']);
Route::get('/payment/{kode}', [BookingController::class, 'payment']);
Route::post('/upload-bukti', [BookingController::class, 'upload']);
Route::get('/invoice/{kode}', [BookingController::class, 'invoice']);
Route::get('/invoice-pdf/{kode}', [BookingController::class, 'invoicePdf']);
Route::get('/cek-booking', [BookingController::class, 'cekBooking']);
Route::post('/cek-booking', [BookingController::class, 'hasilCek']);

Route::get('/test-email', [BookingController::class, 'testEmail']);

Route::prefix('admin')->middleware('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/bookings', [AdminController::class, 'bookings']);
    Route::get('/booking/confirm/{id}', [AdminController::class, 'confirmBooking']);
    Route::get('/transaksi', [AdminController::class, 'transaksi']);
    Route::post('/pemasukan/store', [AdminController::class, 'storePemasukan']);
    Route::post('/pengeluaran/store', [AdminController::class, 'storePengeluaran']);
    Route::get('/laporan', [AdminController::class, 'laporan']);
});

Route::get('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'loginProcess']);
Route::get('/admin/logout', [AuthController::class, 'logout']);
