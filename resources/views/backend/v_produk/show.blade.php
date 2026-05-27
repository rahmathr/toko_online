@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('backend.v_layouts.app')

@section('content')
    <!-- contentAwal -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $judul }}</h4>

                        <!-- Tampilkan Pesan -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ $errors->count() }} error terjadi:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Kolom Kiri: Detail Produk -->
                            <div class="col-md-6">
                                <!-- Kategori -->
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="kategori_id"
                                            class="form-control @error('kategori_id') is-invalid @enderror"
                                            disabled>
                                        <option value="" selected>- Pilih Kategori -</option>
                                        @foreach ($kategori as $row)
                                            <option value="{{ $row->id }}"
                                                    {{ old('kategori_id', $show->kategori_id) == $row->id ? 'selected' : '' }}>
                                                {{ $row->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Nama Produk -->
                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text"
                                           name="nama_produk"
                                           value="{{ old('nama_produk', $show->nama_produk) }}"
                                           class="form-control @error('nama_produk') is-invalid @enderror"
                                           placeholder="Masukkan Nama Produk"
                                           disabled>
                                    @error('nama_produk')
                                        <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Detail -->
                                <div class="form-group">
                                    <label>Detail</label>
                                    <textarea name="detail"
                                              class="form-control @error('detail') is-invalid @enderror"
                                              id="ckeditor"
                                              disabled>{{ old('detail', $show->detail) }}</textarea>
                                    @error('detail')
                                        <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kolom Kanan: Foto Utama & Foto Tambahan -->
                            <div class="col-md-6">
                                <!-- Foto Utama -->
                                <div class="form-group mb-4">
                                    <label>Foto Utama</label>

                                    @if($show->foto)
                                        @php
                                            // Auto-detect path
                                            if (strpos($show->foto, 'img-produk/') === 0) {
                                                $fotoPath = $show->foto;
                                            } else {
                                                $fotoPath = 'img-produk/' . $show->foto;
                                            }
                                            $fullPath = public_path('storage/' . $fotoPath);
                                            $fileExists = file_exists($fullPath);
                                        @endphp

                                        @if ($fileExists)
                                            <img src="{{ asset('storage/' . $fotoPath) }}"
                                                class="foto-preview img-fluid rounded"
                                                style="width: 100%; object-fit: contain; border: 2px solid #ddd;">
                                        @else
                                            <div class="alert alert-warning text-center">
                                                <i class="fas fa-exclamation-triangle"></i><br>
                                                <small><strong>Foto tidak ditemukan!</strong></small><br>
                                                <small class="text-muted">File: {{ $show->foto }}</small>
                                            </div>
                                            <img src="https://via.placeholder.com/400x300?text=No+Image"
                                                class="img-fluid rounded"
                                                style="width: 100%;">
                                        @endif
                                    @else
                                        <div class="alert alert-info text-center">
                                            <i class="fas fa-image"></i><br>
                                            Foto utama belum diupload
                                        </div>
                                    @endif
                                </div>

                                <!-- Foto Tambahan -->
                                <label class="font-weight-bold">Foto Tambahan</label>
                                <div id="foto-container">
                                    <div class="row">
                                        @forelse($show->fotoProduk as $foto)
                                            <div class="col-md-4 mb-3">
                                                @php
                                                    // Auto-detect path
                                                    if (strpos($foto->foto, 'img-produk/') === 0) {
                                                        $displayPath = $foto->foto;
                                                    } else {
                                                        $displayPath = 'img-produk/' . $foto->foto;
                                                    }
                                                @endphp

                                                <img src="{{ asset('storage/' . $displayPath) }}"
                                                    class="img-fluid rounded shadow-sm mb-2"
                                                    style="height: 150px; width: 100%; object-fit: cover;"
                                                    alt="Foto Tambahan"
                                                    onerror="console.log('Gagal load:', this.src);">

                                                <form action="{{ route('backend.foto_produk.destroy', $foto->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm btn-block"
                                                            onclick="return confirm('Hapus foto ini?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-muted fst-italic">Belum ada foto tambahan.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Tombol Tambah Foto -->
                                <button type="button" class="btn btn-primary add-foto mt-2">
                                    <i class="fas fa-plus"></i> Tambah Foto
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Kembali -->
                    <div class="border-top">
                        <div class="card-body">
                            <a href="{{ route('backend.produk.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contentAkhir -->
@endsection

{{-- ✅ Menggunakan @section untuk kompatibilitas ✅ --}}
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ DOM Loaded - Script foto berjalan');

        const fotoContainer = document.getElementById('foto-container');
        const addFotoButton = document.querySelector('.add-foto');

        if (!addFotoButton) {
            console.error('❌ Tombol .add-foto tidak ditemukan!');
            return;
        }

        if (!fotoContainer) {
            console.error('❌ Element #foto-container tidak ditemukan!');
            return;
        }

        console.log('✅ Tombol dan container ditemukan');

        // Ambil data dari Blade
        const produkId = "{{ $show->id }}";
        const storeRoute = "{{ route('backend.foto_produk.store') }}";
        const csrfToken = "{{ csrf_token() }}";

        console.log('Produk ID:', produkId);
        console.log('Store Route:', storeRoute);

        addFotoButton.addEventListener('click', function() {
            console.log('🔴 Tombol diklik!');

            const fotoRow = document.createElement('div');
            fotoRow.classList.add('form-group', 'row', 'mt-2', 'mb-2', 'border', 'p-2', 'bg-light');

            fotoRow.innerHTML = `
                <form action="${storeRoute}" method="post" enctype="multipart/form-data" class="w-100">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="produk_id" value="${produkId}">
                    <div class="col-md-12">
                        <label class="small text-muted">Pilih File:</label>
                        <input type="file" name="foto_produk" class="form-control form-control-sm" required accept="image/*">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="this.closest('.form-group').remove()">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    </div>
                </form>
            `;

            fotoContainer.appendChild(fotoRow);
            console.log('✅ Form ditambahkan');
        });
    });
</script>
@endsection
