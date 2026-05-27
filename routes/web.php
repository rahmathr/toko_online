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
*/

// Redirect root ke halaman login backend
Route::get('/', function () {
    return redirect()->route('backend.login');
});

// -----------------------------------------------------------------
// AUTH ROUTES
// -----------------------------------------------------------------
Route::get('backend/login', [LoginController::class, 'loginBackend'])
    ->name('backend.login');

Route::post('backend/login', [LoginController::class, 'authenticateBackend'])
    ->name('backend.authenticate');

Route::post('backend/logout', [LoginController::class, 'logoutBackend'])
    ->name('backend.logout');

// -----------------------------------------------------------------
// PROTECTED ROUTES (Middleware: auth)
// -----------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard / Beranda
    Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])
        ->name('backend.beranda');

    // User Management
    Route::resource('backend/user', UserController::class, ['as' => 'backend']);

    // Kategori Management
    Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend']);

    // Produk Management (CRUD Utama)
    Route::resource('backend/produk', ProdukController::class, ['as' => 'backend']);

    // -----------------------------------------------------------------
    // CUSTOM ROUTES: Foto Produk Tambahan
    // -----------------------------------------------------------------

    // Tambah foto tambahan
    Route::post('backend/foto-produk/store', [ProdukController::class, 'storeFoto'])
        ->name('backend.foto.produk.store');

    // Hapus foto tambahan
    Route::delete('backend/foto-produk/{id}', [ProdukController::class, 'destroyFoto'])
        ->name('backend.foto.produk.destroy');
});
