@extends('backend.v_layouts.app')

@section('content')
    <!-- contentAwal -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body border-top">
                    <h5 class="card-title">{{ $judul }}</h5>

                    <!-- Alert Welcome -->
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">
                            <i class="fas fa-hand-wave"></i> Selamat Datang, {{ Auth::user()->nama }}
                        </h4>

                        <p class="mb-2">
                            Aplikasi Toko Online dengan hak akses yang anda miliki sebagai
                            <b>
                                @if (Auth::user()->role == 1)
                                    <span class="text-success">Super Admin</span>
                                @elseif (Auth::user()->role == 0)
                                    <span class="text-primary">Admin</span>
                                @else
                                    <span class="text-secondary">User</span>
                                @endif
                            </b>
                        </p>

                        <p>
                            Ini adalah halaman utama dari aplikasi Web Programming.
                            Studi Kasus Toko Online.
                        </p>

                        <hr>

                        <p class="mb-0 font-weight-bold">
                            <i class="fas fa-graduation-cap"></i> Kuliah..? BSI Aja !!!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contentAkhir -->
@endsection
