// 1. Inisialisasi Peta & Base Map
var map = L.map("map", {
    zoomControl: false 
}).setView([-3.316694, 114.590111], 12);

// Pindahkan tombol zoom ke pojok kanan bawah (bottomright)
L.control.zoom({
    position: 'bottomright'
}).addTo(map);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

var markerLayer = L.layerGroup().addTo(map);
