@extends('backend.v_layouts.app')

@section('content')
    <!-- contentAwal -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="{{ route('backend.produk.update', $edit->id) }}"
                          method="post"
                          enctype="multipart/form-data">
                        @method('put')
                        @csrf

                        <div class="card-body">
                            <h4 class="card-title">{{ $judul }}</h4>

                            <div class="row">
                                <!-- Kolom Kiri: Foto Produk -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Foto</label>

                                        {{-- Preview Image --}}
                                        @if ($edit->foto)
                                            @php
                                                // Auto-detect path
                                                if (strpos($edit->foto, 'img-produk/') === 0) {
                                                    $fotoPath = $edit->foto;
                                                } else {
                                                    $fotoPath = 'img-produk/' . $edit->foto;
                                                }
                                            @endphp

                                            <img src="{{ asset('storage/' . $fotoPath) }}"
                                                class="foto-preview img-fluid rounded mb-2"
                                                alt="Foto Produk"
                                                style="max-height: 300px; object-fit: contain;"
                                                onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                                        @else
                                            <img src="https://via.placeholder.com/400x300?text=No+Image"
                                                class="foto-preview img-fluid rounded mb-2"
                                                alt="No Image">
                                        @endif

                                        {{-- File Input --}}
                                        <input type="file"
                                            name="foto"
                                            class="form-control @error('foto') is-invalid @enderror"
                                            onchange="previewFoto()">
                                        @error('foto')
                                            <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kolom Kanan: Form Fields -->
                                <div class="col-md-8">
                                    <!-- Status -->
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status"
                                                class="form-control @error('status') is-invalid @enderror">
                                            <option value="" {{ old('status', $edit->status) == '' ? 'selected' : '' }}>
                                                - Pilih Status -
                                            </option>
                                            <option value="1" {{ old('status', $edit->status) == '1' ? 'selected' : '' }}>
                                                Publish
                                            </option>
                                            <option value="0" {{ old('status', $edit->status) == '0' ? 'selected' : '' }}>
                                                Draft
                                            </option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Kategori -->
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="kategori_id"
                                                class="form-control @error('kategori_id') is-invalid @enderror">
                                            <option value="">- Pilih Kategori -</option>
                                            @foreach ($kategori as $row)
                                                <option value="{{ $row->id }}"
                                                        {{ old('kategori_id', $edit->kategori_id) == $row->id ? 'selected' : '' }}>
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
                                               value="{{ old('nama_produk', $edit->nama_produk) }}"
                                               class="form-control @error('nama_produk') is-invalid @enderror"
                                               placeholder="Masukkan Nama Produk">
                                        @error('nama_produk')
                                            <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Detail -->
                                    <div class="form-group">
                                        <label>Detail</label><br>
                                        <textarea name="detail"
                                                  class="form-control @error('detail') is-invalid @enderror"
                                                  id="ckeditor">{{ old('detail', $edit->detail) }}</textarea>
                                        @error('detail')
                                            <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Harga -->
                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="text"
                                               onkeypress="return hanyaAngka(event)"
                                               name="harga"
                                               value="{{ old('harga', $edit->harga) }}"
                                               class="form-control @error('harga') is-invalid @enderror"
                                               placeholder="Masukkan Harga Produk">
                                        @error('harga')
                                            <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Berat -->
                                    <div class="form-group">
                                        <label>Berat</label>
                                        <input type="text"
                                               onkeypress="return hanyaAngka(event)"
                                               name="berat"
                                               value="{{ old('berat', $edit->berat) }}"
                                               class="form-control @error('berat') is-invalid @enderror"
                                               placeholder="Masukkan Berat Produk">
                                        @error('berat')
                                            <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Stok -->
                                    <div class="form-group">
                                        <label>Stok</label>
                                        <input type="text"
                                               onkeypress="return hanyaAngka(event)"
                                               name="stok"
                                               value="{{ old('stok', $edit->stok) }}"
                                               class="form-control @error('stok') is-invalid @enderror"
                                               placeholder="Masukkan Stok Produk">
                                        @error('stok')
                                            <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">Perbaharui</button>
                                <a href="{{ route('backend.produk.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- contentAkhir -->
@endsection
