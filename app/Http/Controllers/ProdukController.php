<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\FotoProduk;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Log;
class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::orderBy('updated_at', 'desc')->get();

        return view('backend.v_produk.index', [
            'judul' => 'Data Produk',
            'index' => $produk,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.v_produk.create', [
            'judul' => 'Tambah Produk',
            'kategori' => $kategori,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required|max:255|unique:produk',
            'detail' => 'required',
            'harga' => 'required',
            'berat' => 'required',
            'stok' => 'required',
            'foto' => 'required|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ], [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.',
        ]);

        $validatedData['user_id'] = auth()->id();
        $validatedData['status'] = 0;

        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';

            // Simpan gambar asli SAJA (tanpa thumbnail)
            $fileName = ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $fileName;
        }

        Produk::create($validatedData);

        return redirect()->route('backend.produk.index')
            ->with('success', 'Data berhasil tersimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with('fotoProduk')->findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.v_produk.show', [
            'judul' => 'Detail Produk',
            'show' => $produk,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.v_produk.edit', [
            'judul' => 'Ubah Produk',
            'edit' => $produk,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $rules = [
            'nama_produk' => 'required|max:255|unique:produk,nama_produk,' . $id,
            'kategori_id' => 'required',
            'status' => 'required',
            'detail' => 'required',
            'harga' => 'required',
            'berat' => 'required',
            'stok' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];

        $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.',
        ];

        $validatedData = $request->validate($rules, $messages);
        $validatedData['user_id'] = auth()->id();

        if ($request->file('foto')) {
            // Hapus gambar lama SAJA (tanpa thumbnail)
            if ($produk->foto) {
                $oldImagePath = public_path('storage/img-produk/') . $produk->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';

            // Simpan gambar asli SAJA (tanpa thumbnail)
            $fileName = ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $fileName;
        }

        $produk->update($validatedData);

        return redirect()->route('backend.produk.index')
            ->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $directory = public_path('storage/img-produk/');

        if ($produk->foto) {
            // Hapus gambar asli SAJA (tanpa thumbnail)
            $oldImagePath = $directory . $produk->foto;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Hapus foto produk lainnya di tabel foto_produk
        $fotoProduks = FotoProduk::where('produk_id', $id)->get();
        foreach ($fotoProduks as $fotoProduk) {
            $fotoPath = $directory . $fotoProduk->foto;
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
            $fotoProduk->delete();
        }

        $produk->delete();

        return redirect()->route('backend.produk.index')
            ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Method untuk menyimpan foto tambahan.
     */
    public function storeFoto(Request $request)
    {
        try {
            $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'foto_produk' => 'required|image|mimes:jpeg,jpg,png,gif|file|max:1024',
            ], [
                'foto_produk.required' => 'File foto harus dipilih.',
                'foto_produk.image' => 'File harus berupa gambar.',
                'foto_produk.mimes' => 'Format gambar harus jpeg, jpg, png, atau gif.',
                'foto_produk.max' => 'Ukuran file maksimal 1024 KB.',
            ]);

            if ($request->hasFile('foto_produk')) {
                $file = $request->file('foto_produk');

                // Generate nama file unik
                $extension = $file->getClientOriginalExtension();
                $filename = date('YmdHis') . '_' . uniqid() . '.' . $extension;

                // Simpan file ke storage
                $file->storeAs('img-produk', $filename, 'public');

                // Simpan ke database
                FotoProduk::create([
                    'produk_id' => $request->produk_id,
                    'foto' => $filename,
                ]);
            }

            return redirect()->route('backend.produk.show', $request->produk_id)
                ->with('success', 'Foto berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error upload foto: ' . $e->getMessage());
            return redirect()->route('backend.produk.show', $request->produk_id)
                ->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk menghapus foto.
     */
    public function destroyFoto(string $id)  // ← Tambahkan 'string' di sini
    {
        $foto = FotoProduk::findOrFail($id);
        $produkId = $foto->produk_id;

        // Hapus file gambar dari storage
        $imagePath = public_path('storage/img-produk/') . $foto->foto;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Hapus record dari database
        $foto->delete();

        return redirect()->route('backend.produk.show', $produkId)
            ->with('success', 'Foto berhasil dihapus.');
    }
}
