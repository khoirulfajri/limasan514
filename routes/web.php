<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController; // Add this line
use App\Http\Controllers\AuthController; // Add this line
use App\Http\Controllers\VoucherController; // Add this line
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
Route::post('/booking/confirm', [BookingController::class, 'confirmBooking']);
Route::get('/payment', [BookingController::class, 'payment']);
// Route::post('/upload-bukti', [BookingController::class, 'upload']);
Route::get('/invoice/{kode}', [BookingController::class, 'invoice']);
Route::get('/invoice-pdf/{kode}', [BookingController::class, 'invoicePdf']);
Route::get('/cek-voucher', [BookingController::class, 'cekVoucher']);

Route::get('/cek-booking', [BookingController::class,'formCekBooking'])->name('cek.booking');
// submit cek booking & upload bukti
Route::post('/cek-booking', [BookingController::class,'cekBooking'])->name('cek.booking.submit');
// upload bukti pembayaran
Route::post('/cek-booking/upload/{id}', [BookingController::class,'uploadBukti'])->name('cek.booking.upload');
// mendapatkan tanggal penuh untuk kalender
Route::get('/full-dates', [BookingController::class, 'getFullDates']);

// Route::get('/test-email', [BookingController::class, 'testEmail']);

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('vouchers', VoucherController::class);

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/users/update/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::get('/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    

    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::post('/booking/store', [AdminController::class, 'storeBooking'])->name('booking.store');
    Route::post('/booking/update/{id}', [AdminController::class, 'updateBooking'])->name('booking.update');
    Route::get('/booking/delete/{id}', [AdminController::class, 'deleteBooking'])->name('booking.delete');
    Route::get('/booking/confirm/{id}', [AdminController::class, 'confirmBooking'])->name('booking.confirm');

    Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');
    Route::get('/transaksi/delete/{id}', [AdminController::class, 'deleteTransaksi'])->name('transaksi.delete');

    Route::post('/transaksi/store',[AdminController::class,'storeTransaksi'])->name('transaksi.store');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/pdf', [AdminController::class, 'exportPDF'])->name('laporan.pdf');
    Route::get('/laporan/export',[AdminController::class,'exportPDF'])->name('laporan.export');
});

Route::get('/admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginProcess'])->name('admin.login.process');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
