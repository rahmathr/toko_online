<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('updated_at', 'desc')->get();

        return view('backend.v_user.index', [
            'judul' => 'Data User',
            'index' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.v_user.create', [
            'judul' => 'Tambah User'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|max:255|email|unique:user,email',
            'role' => 'required',
            'hp' => 'required|min:10|max:13',
            'password' => 'required|min:4|confirmed',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ], $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ]);

        // Set default status
        $validatedData['status'] = 0;

        // 2. Handle Upload Foto dengan ImageHelper
        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';

            // Pastikan direktori ada
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Simpan & resize gambar (385x400)
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);

            // Simpan nama file di database
            $validatedData['foto'] = $originalFileName;
        }

        // 3. Validasi Pola Password
        $password = $request->input('password');
        // Pola: huruf kecil, huruf besar, angka, dan simbol
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';

        if (preg_match($pattern, $password)) {
            // Hash password sebelum simpan
            $validatedData['password'] = Hash::make($validatedData['password']);

            // Simpan ke database
            User::create($validatedData);

            return redirect()->route('backend.user.index')
                ->with('success', 'Data berhasil tersimpan');
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors(['password' => 'Password harus terdiri dari kombinasi huruf besar, huruf kecil, angka, dan simbol karakter.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('backend.v_user.edit', [
            'judul' => 'Ubah User',
            'edit' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Rules validasi
        $rules = [
            'nama' => 'required|max:255',
            'role' => 'required',
            'status' => 'required',
            'hp' => 'required|min:10|max:13',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];

        // Pesan error custom
        $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ];

        // Validasi email unik (kecuali email user yang sedang diedit)
        if ($request->email != $user->email) {
            $rules['email'] = 'required|max:255|email|unique:user,email,' . $user->id;
        }

        // Eksekusi validasi
        $validatedData = $request->validate($rules, $messages);

        // Handle Upload Foto (jika ada file baru)
        if ($request->file('foto')) {
            // Hapus gambar lama jika ada
            if ($user->foto) {
                $oldImagePath = public_path('storage/img-user/') . $user->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload & resize gambar baru
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';

            // Pastikan direktori ada
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Simpan gambar dengan ukuran yang ditentukan
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);

            // Simpan nama file di database
            $validatedData['foto'] = $originalFileName;
        }

        // Update data user (tanpa password)
        $user->update($validatedData);

        return redirect()->route('backend.user.index')
            ->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus foto jika ada
        if ($user->foto) {
            $oldImagePath = public_path('storage/img-user/') . $user->foto;

            // Cek apakah file foto ada
            if (file_exists($oldImagePath)) {
                // Hapus file foto
                unlink($oldImagePath);
            }
        }

        // Hapus data user dari database
        $user->delete();

        // Redirect dengan pesan success
        return redirect()->route('backend.user.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
