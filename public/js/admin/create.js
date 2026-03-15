// Ganti isi create.js dengan ini
function initMap() {
    const mapElement = document.getElementById("map-tambah");

    if (mapElement) {
        // Jika sudah ada peta, hapus dulu
        if (window.mapAlumni) {
            window.mapAlumni.remove();
        }

        window.mapAlumni = L.map("map-tambah").setView([-3.3194, 114.5908], 12);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(window.mapAlumni);

        // Paksa invalidateSize segera setelah peta siap
        window.mapAlumni.whenReady(function() {
            setTimeout(() => {
                window.mapAlumni.invalidateSize();
            }, 100);
        });

        var marker;
        window.mapAlumni.on("click", function (e) {
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(window.mapAlumni);
            }
            document.getElementById("lat").value = e.latlng.lat.toFixed(6);
            document.getElementById("lng").value = e.latlng.lng.toFixed(6);
        });
    }
}

// Jalankan saat window load
window.addEventListener('load', initMap);