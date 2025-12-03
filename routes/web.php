<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IkanController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

// Halaman utama
Route::get('/', function () {
    return view('home');
})->name('home');

// ===================
// Auth
// ===================
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================
// Admin (hanya admin + prevent back history)
// ===================
Route::middleware(['auth', 'admin', 'PreventBackHistory'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return redirect()->route('admin.ikan.index');
        })->name('dashboard');

        Route::resource('ikan', IkanController::class)->except(['show']);
    });

// ===================
// Katalog (umum / guest boleh akses)
// ===================
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{ikan}', [KatalogController::class, 'show'])->name('katalog.show');

// ===================
// Route yang butuh login + prevent back history
// ===================
Route::middleware(['auth', 'PreventBackHistory'])->group(function () {

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');

    Route::post('/keranjang/tambah/{ikan}', [KeranjangController::class, 'tambah'])
        ->name('keranjang.tambah');

    Route::patch('/keranjang/{id}', [KeranjangController::class, 'update'])
        ->name('keranjang.update');

    Route::delete('/keranjang/{id}', [KeranjangController::class, 'hapus'])
        ->name('keranjang.hapus');

    Route::delete('/keranjang', [KeranjangController::class, 'clear'])
        ->name('keranjang.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/sukses/{transaksi}', [CheckoutController::class, 'success'])
        ->name('checkout.success');

    // Transaksi & Struk
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])
        ->name('transaksi.show');

    Route::get('/transaksi/{transaksi}/cetak', [TransaksiController::class, 'cetak'])
        ->name('transaksi.cetak');

    Route::get('/transaksi/{transaksi}/struk-pdf', [TransaksiController::class, 'strukPdf'])
        ->name('transaksi.struk_pdf');

    Route::get('/transaksi/{transaksi}/struk', [TransaksiController::class, 'strukHtml'])
        ->name('transaksi.struk');

    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
