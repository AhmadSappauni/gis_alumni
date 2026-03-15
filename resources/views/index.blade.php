<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS Persebaran Alumni</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>
<body>
    @include('utama.filter-panel')
    <div id="map"></div>
    @include('utama.sidebar')
    @include('utama.daftar-alumni')
    @include('utama.id-card')
    @include('utama.cluster')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        var alumniData = @json($dataPekerjaan);
    </script>
    <script src="{{ asset('js/utama/map.js') }}"></script>
    <script src="{{ asset('js/utama/filter.js') }}"></script>
    <script src="{{ asset('js/utama/sidebar.js') }}"></script>
    <script src="{{ asset('js/utama/daftar-alumni.js') }}"></script>
    <script src="{{ asset('js/utama/id-card.js') }}"></script>
    <script src="{{ asset('js/utama/cluster.js') }}"></script>
    
</body>
</html>