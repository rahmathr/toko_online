@extends('backend.v_layouts.app')

@section('content')
    <!-- contentAwal -->
    <div class="row">
        <div class="col-12">
            <!-- Tombol Tambah -->
            <a href="{{ route('backend.user.create') }}" class="m-b-20 d-inline-block">
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </a>

            <!-- Card: Data User -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $judul }}</h5>

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($index as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->nama }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>
                                            @if ($row->role == 1)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-user-shield"></i> Super Admin
                                                </span>
                                            @elseif ($row->role == 0)
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-user"></i> Admin
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">User</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row->status == 1)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Aktif
                                                </span>
                                            @elseif ($row->status == 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-times-circle"></i> NonAktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <!-- Tombol Ubah -->
                                            <a href="{{ route('backend.user.edit', $row->id) }}" title="Ubah Data">
                                                <button type="button" class="btn btn-info btn-sm">
                                                    <i class="far fa-edit"></i> Ubah
                                                </button>
                                            </a>

                                            <!-- Form Hapus dengan SweetAlert Confirm -->
                                            <form method="POST" action="{{ route('backend.user.destroy', $row->id) }}" style="display: inline-block;">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm show_confirm"
                                                        data-konf-delete="{{ $row->nama }}"
                                                        title="Hapus Data">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contentAkhir -->

    <!-- SweetAlert Confirm Delete Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.show_confirm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const nama = this.getAttribute('data-konf-delete');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: `Data user "${nama}" akan dihapus permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
