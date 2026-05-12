@extends('backend.v_layouts.app')

@section('content')
    <!-- contentAwal -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form class="form-horizontal" action="{{ route('backend.user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <h4 class="card-title">{{ $judul }}</h4>

                            <div class="row">
                                <!-- Kolom Kiri: Foto -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Foto</label><br>
                                        <img id="foto-preview" src="#" alt="Preview Foto" class="img-thumbnail mb-2" style="max-width: 100%; display: none;">
                                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()">
                                        @error('foto')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kolom Kanan: Form Input -->
                                <div class="col-md-8">
                                    <!-- Hak Akses -->
                                    <div class="form-group">
                                        <label>Hak Akses</label>
                                        <select name="role" class="form-control @error('role') is-invalid @enderror">
                                            <option value="">- Pilih Hak Akses -</option>
                                            <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Super Admin</option>
                                            <option value="0" {{ old('role') == '0' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Nama -->
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" name="nama" value="{{ old('nama') }}"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            placeholder="Masukkan Nama">
                                        @error('nama')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Masukkan Email">
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- HP -->
                                    <div class="form-group">
                                        <label>HP</label>
                                        <input type="text" name="hp" value="{{ old('hp') }}"
                                            class="form-control @error('hp') is-invalid @enderror"
                                            placeholder="Masukkan Nomor HP"
                                            onkeypress="return hanyaAngka(event)"
                                            maxlength="15">
                                        @error('hp')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Masukkan Password">
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Konfirmasi Password -->
                                    <div class="form-group">
                                        <label>Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation"
                                            class="form-control"
                                            placeholder="Konfirmasi Password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('backend.user.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- contentAkhir -->

    <!-- Script Preview Foto & Hanya Angka -->
    <script>
        // Preview Foto
        function previewFoto() {
            const fotoInput = document.querySelector('input[name="foto"]');
            const preview = document.getElementById('foto-preview');

            if (fotoInput.files && fotoInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(fotoInput.files[0]);
            }
        }

        // Hanya Angka untuk Input HP
        function hanyaAngka(evt) {
            const charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>
@endsection
