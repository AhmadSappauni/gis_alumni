<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    @stack('styles')

    <style>
    body {
        overflow: hidden; /* Mencegah scroll double di browser */
    }
    .main-content {
        overflow-y: auto;
        height: 100vh; /* Pastikan setinggi layar penuh */
    }
</style>
</head>

<body>
    @include('admin.komponen.sidebar')
    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Script dari halaman -->
    @stack('scripts')
</body>
</html>