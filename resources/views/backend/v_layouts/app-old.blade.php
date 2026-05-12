<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>
</head>
<body>
    {{-- Navbar hanya tampil jika user sudah login --}}
    @auth
        <nav>
            <a href="{{ route('backend.beranda') }}">Beranda</a> |
            <a href="#">User</a> |
            <a href="#" onclick="event.preventDefault(); document.getElementById('keluar-app').submit();">
                Keluar
            </a>
        </nav>
        <hr>
    @endauth

    {{-- Content Section --}}
    @yield('content')

    {{-- Logout Form (Hidden) --}}
    @auth
        <form id="keluar-app" action="{{ route('backend.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endauth
</body>
</html>
