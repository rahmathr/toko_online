<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .header-title {
            font-weight: bold;
            font-size: 14px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
        }

        .table-data th,
        .table-data td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .table-data th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .table-data td {
            font-weight: normal;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-print {
            display: none;
        }

        @media print {
            body {
                font-size: 11px;
            }

            .table-data th,
            .table-data td {
                padding: 6px;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Header Informasi -->
    <table class="header">
        <tr>
            <td>
                <div class="header-title">Perihal : {{ $judul }}</div>
                <div>Tanggal Awal : {{ \Carbon\Carbon::parse($tanggalAwal)->format('d-m-Y') }}</div>
                <div>Tanggal Akhir : {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d-m-Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Produk -->
    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="20%">Kategori</th>
                <th class="text-center" width="10%">Status</th>
                <th width="25%">Nama Produk</th>
                <th class="text-right" width="15%">Harga</th>
                <th class="text-center" width="10%">Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cetak as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->kategori->nama_kategori ?? '-' }}</td>
                    <td class="text-center">
                        @if ($row->status == 1)
                            <strong>Publis</strong>
                        @elseif ($row->status == 0)
                            Blok
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $row->nama_produk }}</td>
                    <td class="text-right">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $row->stok }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data untuk ditampilkan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Script Auto Print -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>

</body>
</html>
