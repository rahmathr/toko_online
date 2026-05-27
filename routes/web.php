<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// =====================================================================
// ROUTES PUBLIC
// =====================================================================

Route::get('/', function () {
    return redirect()->route('backend.login');
});

// =====================================================================
// ROUTES AUTHENTICATION
// =====================================================================

Route::get('backend/login', [LoginController::class, 'loginBackend'])
    ->name('backend.login');

Route::post('backend/login', [LoginController::class, 'authenticateBackend'])
    ->name('backend.authenticate');

Route::post('backend/logout', [LoginController::class, 'logoutBackend'])
    ->name('backend.logout')
    ->middleware('auth');

// =====================================================================
// ROUTES BACKEND (MEMBUTUHKAN AUTH)
// =====================================================================

Route::middleware('auth')->group(function () {

    // --- Beranda ---
    Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])
        ->name('backend.beranda');

    // --- User Management ---
    Route::resource('backend/user', UserController::class, [
        'as' => 'backend'
    ]);

    // --- User Report ---
    Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])
        ->name('backend.laporan.formuser');

    Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])
        ->name('backend.laporan.cetakuser');

    // --- Kategori Management ---
    Route::resource('backend/kategori', KategoriController::class, [
        'as' => 'backend'
    ]);

    // --- Produk Management ---
    Route::resource('backend/produk', ProdukController::class, [
        'as' => 'backend'
    ]);

    // --- Produk Foto (Custom Routes) ---
    Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])
        ->name('backend.foto_produk.store');

    Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])
        ->name('backend.foto_produk.destroy');

    // --- Produk Report ---
    Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])
        ->name('backend.laporan.formproduk');

    Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])
        ->name('backend.laporan.cetakproduk');

});
