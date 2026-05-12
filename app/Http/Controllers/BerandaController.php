<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman beranda backend.
     */
public function berandaBackend()
{
    // Contoh: ambil data dari user yang login (jika menggunakan auth)
    $user = auth()->user(); // atau User::find(1) untuk testing

    return view('backend.v_beranda.index', [
        'judul' => 'Halaman Beranda',
        'nama_user' => $user?->nama ?? 'Nama_User',      // fallback jika null
        'role_user' => $user?->role_text ?? 'Role_User',    // atau konversi role: $user->role == 1 ? 'SuperAdmin' : 'Admin'
    ]);
}
}
