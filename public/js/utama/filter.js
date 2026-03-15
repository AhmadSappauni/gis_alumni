// VARIABEL BARU: Untuk menyimpan referensi semua marker di peta
var arrayMarker = []; 

// BUNGKUS DENGAN DOMContentLoaded AGAR JS MENUNGGU HTML SELESAI DIMUAT
document.addEventListener("DOMContentLoaded", function() {

    // 1. EVENT LISTENER UNTUK SEMUA FILTER & PENCARIAN
    document.getElementById('search-category').addEventListener('change', filterDanTampilkanMarker);
    document.getElementById('filter-linearitas').addEventListener('change', filterDanTampilkanMarker);
    document.getElementById('filter-tahun').addEventListener('change', filterDanTampilkanMarker);
    document.getElementById('filter-wilayah').addEventListener('input', filterDanTampilkanMarker);

    document.getElementById('btn-search').addEventListener('click', filterDanTampilkanMarker);

    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterDanTampilkanMarker();
        }
    });

    // 5. EVENT LISTENER TOGGLE TOMBOL FILTER 
    document.getElementById('toggle-filter').addEventListener('click', function() {
        var filterBody = document.getElementById('filter-body');
        filterBody.classList.toggle('hidden'); 
    });

    // 4. Jalankan fungsi saat web pertama kali dibuka
    filterDanTampilkanMarker();

    // =====================================================================
    // FITUR BARU: MENYULAP SELECT BAWAAN BROWSER JADI DROPDOWN PREMIUM
    // =====================================================================
    const selects = document.querySelectorAll('.custom-select');
    
    selects.forEach(select => {
        // 1. Buat pembungkus dan sembunyikan select asli
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-dropdown-wrapper';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        select.style.display = 'none';

        // 2. Buat tombol yang terlihat
        const trigger = document.createElement('div');
        trigger.className = 'custom-dropdown-trigger';
        trigger.innerHTML = `<span>${select.options[select.selectedIndex].text}</span><div class="arrow"></div>`;
        wrapper.appendChild(trigger);

        // 3. Buat kotak melayang untuk pilihan
        const optionsList = document.createElement('div');
        optionsList.className = 'custom-dropdown-options';
        wrapper.appendChild(optionsList);

        // 4. Pindahkan semua opsi ke bentuk div cantik
        Array.from(select.options).forEach(option => {
            const customOption = document.createElement('div');
            customOption.className = 'custom-option' + (option.selected ? ' selected' : '');
            customOption.dataset.value = option.value;
            customOption.textContent = option.text;

            // Jika pilihan diklik
            customOption.addEventListener('click', function() {
                select.value = this.dataset.value; // Update sistem asli
                trigger.querySelector('span').textContent = this.textContent; // Update teks
                
                optionsList.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected'); // Beri warna biru
                
                optionsList.classList.remove('open');
                trigger.classList.remove('active');
                
                // PENTING: Pancing sistem agar memfilter peta ulang
                select.dispatchEvent(new Event('change'));
            });
            optionsList.appendChild(customOption);
        });

        // 5. Logika buka tutup saat diklik
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            // Tutup dropdown lain jika ada yang terbuka
            document.querySelectorAll('.custom-dropdown-options').forEach(list => {
                if (list !== optionsList) list.classList.remove('open');
            });
            document.querySelectorAll('.custom-dropdown-trigger').forEach(trig => {
                if (trig !== trigger) trig.classList.remove('active');
            });
            
            optionsList.classList.toggle('open');
            trigger.classList.toggle('active');
        });
    });

    // 6. Tutup dropdown jika klik sembarang tempat di peta
    document.addEventListener('click', function() {
        document.querySelectorAll('.custom-dropdown-options').forEach(list => list.classList.remove('open'));
        document.querySelectorAll('.custom-dropdown-trigger').forEach(trigger => trigger.classList.remove('active'));
    });
    // =====================================================================

}); // Akhir dari penunggu DOMContentLoaded


// 2. Fungsi Utama: Menyaring & Menampilkan Marker + Daftar List
// =====================================================================
// PERSIAPAN WADAH MARKER CLUSTER (Dibuat Global dengan window.)
// =====================================================================
window.wadahNormal = L.featureGroup(); 
window.wadahCluster = L.markerClusterGroup({
    chunkedLoading: true, 
    spiderfyOnMaxZoom: true,
    showCoverageOnHover: false, 
    maxClusterRadius: 50 
});
window.statusClusterAktif = false; 

// Fungsi untuk bongkar-pasang wadah di atas peta (Dibuat global dengan window.)
window.perbaruiTampilanPeta = function() {
    if(map.hasLayer(window.wadahNormal)) map.removeLayer(window.wadahNormal);
    if(map.hasLayer(window.wadahCluster)) map.removeLayer(window.wadahCluster);

    if(window.statusClusterAktif) {
        map.addLayer(window.wadahCluster);
    } else {
        map.addLayer(window.wadahNormal);
    }
};

// =====================================================================
// FUNGSI UTAMA PENCETAK MARKER
// =====================================================================
function filterDanTampilkanMarker() {
    let keyword = document.getElementById('search-input').value.toLowerCase();
    
    let searchCategory = document.getElementById('search-category').value;
    let linearitasTerpilih = document.getElementById('filter-linearitas').value;
    let tahunTerpilih = document.getElementById('filter-tahun').value;
    let wilayahKetik = document.getElementById('filter-wilayah').value.toLowerCase();

    // BERSIHKAN KEDUA WADAH SEBELUM MULAI LOOPING BARU (Pake window.)
    window.wadahNormal.clearLayers();
    window.wadahCluster.clearLayers();
    arrayMarker = []; 

    let resultsHTML = ''; 
    let jumlahDitemukan = 0;
    
    // PERHATIAN: Memeriksa apakah semua filter dalam kondisi kosong
    let isDefaultState = (keyword === '' && linearitasTerpilih === 'semua' && tahunTerpilih === 'semua' && wilayahKetik === '');

    alumniData.forEach(function(alumni, index) { 
        let namaAlumni = alumni.nama_lengkap.toLowerCase();
        let namaPerusahaan = alumni.nama_perusahaan.toLowerCase();
        
        let cocokKeyword = true;
        if (keyword !== '') {
            if (searchCategory === 'semua') {
                cocokKeyword = namaAlumni.includes(keyword) || namaPerusahaan.includes(keyword);
            } 
            else if (searchCategory === 'nama') {
                cocokKeyword = namaAlumni.includes(keyword);
            } 
            else if (searchCategory === 'perusahaan') {
                cocokKeyword = namaPerusahaan.includes(keyword);
            }
        }

        let cocokLinearitas = (linearitasTerpilih === 'semua' || alumni.linearitas === linearitasTerpilih);
        
        let cocokTahun = false;
        if (tahunTerpilih === 'semua') {
            cocokTahun = true;
        } else {
            let tahunSaatIni = new Date().getFullYear(); 
            let tahunAlumni = parseInt(alumni.tahun_lulus); 
            let selisihTahun = tahunSaatIni - tahunAlumni; 
            let batasTahun = parseInt(tahunTerpilih); 

            if (selisihTahun >= 0 && selisihTahun <= batasTahun) {
                cocokTahun = true;
            }
        }
        
        let teksWilayah = (alumni.alamat_lengkap || alumni.nama_perusahaan || "").toLowerCase(); 
        let cocokWilayah = (wilayahKetik === '' || teksWilayah.includes(wilayahKetik));

        // JIKA SYARAT COCOK & PUNYA KOORDINAT -> CETAK MARKER
        if(cocokKeyword && cocokLinearitas && cocokTahun && cocokWilayah && alumni.latitude && alumni.longitude) {
            
            var markerColor = (alumni.linearitas === 'Linier') ? 'blue' : 'red';
            var customIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-' + markerColor + '.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
            });

            var marker = L.marker([alumni.latitude, alumni.longitude], {icon: customIcon});
            
            var placeholderUrl = 'https://ui-avatars.com/api/?name=' + alumni.nama_lengkap.replace(/\s+/g, '+') + '&background=004a87&color=fff&size=60&rounded=true';
            let badgeClass = (alumni.linearitas === 'Linier') ? 'badge-linier' : 'badge-tidak';

            var popupContent = `
                <div class="premium-popup">
                    <div class="popup-cover"></div>
                    <div class="popup-avatar">
                        <img src="${placeholderUrl}" alt="Avatar">
                    </div>
                    <div class="popup-body">
                        <h3 class="popup-name">${alumni.nama_lengkap}</h3>
                        <span class="popup-year">Lulusan Tahun ${alumni.tahun_lulus}</span>
                        <div class="popup-info">
                            <div class="info-row">
                                <span class="icon">🏢</span>
                                <span><b>${alumni.nama_perusahaan}</b></span>
                            </div>
                            <div class="info-row">
                                <span class="icon">💼</span>
                                <span>${alumni.jabatan}</span>
                            </div>
                        </div>
                        <div class="popup-footer">
                            <span class="popup-badge ${badgeClass}">${alumni.linearitas}</span>
                        </div>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            
            // KODE BARU: Masukkan marker ke dalam DUA wadah sekaligus (Pake window.)
            window.wadahNormal.addLayer(marker);
            window.wadahCluster.addLayer(marker);

            // Simpan marker di array global (untuk fitur "terbang ke lokasi")
            arrayMarker[index] = marker;

            // Cetak list kartu di sidebar filter (Hanya jika sedang mencari sesuatu)
            if (!isDefaultState) {
                let statusClass = (alumni.linearitas === 'Linier') ? 'status-linier' : 'status-tidak';
                resultsHTML += `
                    <div class="result-card" onclick="terbangKeLokasi(${index})">
                        <div class="result-name">${alumni.nama_lengkap} <span style="font-weight:normal; font-size:11px; color:#94a3b8;">(${alumni.tahun_lulus})</span></div>
                        <div class="result-job">🏢 ${alumni.nama_perusahaan}</div>
                        <div class="result-status ${statusClass}">${alumni.linearitas}</div>
                    </div>
                `;
                jumlahDitemukan++;
            }
        }
    }); // Akhir Looping

    // UPDATE PETA SETELAH LOOPING SELESAI (Pake window.)
    window.perbaruiTampilanPeta();

    // UPDATE DAFTAR HASIL DI PANEL FILTER
    let resultsContainer = document.getElementById('search-results');
    
    if (isDefaultState) {
        resultsContainer.innerHTML = '';
    } else if (jumlahDitemukan > 0) {
        resultsContainer.innerHTML = `<div class="result-count">Ditemukan ${jumlahDitemukan} Alumni</div>` + resultsHTML;
    } else {
        resultsContainer.innerHTML = `<div class="result-empty">Data tidak ditemukan.</div>`;
    }
}

// 3. Terbang ke marker dan Buka Pop-up (Tetap di luar karena dipanggil lewat onClick HTML)
function terbangKeLokasi(index) {
    let targetMarker = arrayMarker[index]; 
    
    if(targetMarker) {
        let posisi = targetMarker.getLatLng(); 

        map.flyTo(posisi, 16, {
            animate: true,
            duration: 1.5
        });

        setTimeout(function() {
            targetMarker.openPopup();
        }, 300); 
    }
}

