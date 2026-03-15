document.addEventListener("DOMContentLoaded", function() {

    // =====================================================================
    // LOGIKA MODAL ID CARD PROFIL ALUMNI
    // =====================================================================

    // 1. Fungsi Utama: Mengisi Data dan Memunculkan Modal
    // Kita gunakan window. agar fungsi ini bisa dipanggil dari mana saja 
    // (misalnya dari klik tombol di peta atau dari daftar alumni)
    window.bukaProfilAlumni = function(index) {
        // Ambil data spesifik alumni berdasarkan index
        let alumni = alumniData[index];
        
        // --- PROSES 1: INJEKSI DATA KE HTML ---
        document.getElementById('modal-nama').textContent = alumni.nama_lengkap;
        document.getElementById('modal-tahun').textContent = "Lulusan Tahun " + alumni.tahun_lulus;
        document.getElementById('modal-perusahaan').textContent = alumni.nama_perusahaan;
        document.getElementById('modal-jabatan').textContent = alumni.jabatan;
        
        // Buat avatar berdasarkan nama
        let avatarUrl = 'https://ui-avatars.com/api/?name=' + alumni.nama_lengkap.replace(/\s+/g, '+') + '&background=004a87&color=fff&size=100';
        document.getElementById('modal-avatar').src = avatarUrl;
        
        // --- PROSES 2: MENGATUR LENCANA LINEARITAS ---
        let badgeLinearitas = document.getElementById('modal-linearitas');
        badgeLinearitas.textContent = alumni.linearitas;
        
        // Hapus class lama jika ada, lalu tambahkan class yang sesuai
        badgeLinearitas.className = "status-badge"; // Reset class dasar
        
        if (alumni.linearitas === 'Linier') {
            badgeLinearitas.classList.add("status-linier");
            // Gaya sebaris (inline) untuk berjaga-jaga jika CSS belum ter-load sempurna
            badgeLinearitas.style.background = "#e0f2fe"; 
            badgeLinearitas.style.color = "#0284c7";
        } else {
            badgeLinearitas.classList.add("status-tidak");
            badgeLinearitas.style.background = "#fee2e2"; 
            badgeLinearitas.style.color = "#b91c1c";
        }

        // --- PROSES 3: TAMPILKAN MODALNYA ---
        document.getElementById('profil-modal-overlay').classList.add('active');
        
        // --- PROSES 4: TUTUP MODAL LAIN (Opsional) ---
        // Jika ID Card ini dibuka dari Buku Direktori, kita tutup dulu Buku Direktorinya
        // agar tidak ada dua modal saling tumpang tindih
        let direktoriModal = document.getElementById('direktori-modal-overlay');
        if(direktoriModal) {
            direktoriModal.classList.remove('active');
        }
    };

    // 2. Logika Menutup Modal ID Card
    const btnTutupAtas = document.getElementById('close-profil-modal');
    const btnTutupBawah = document.getElementById('btn-tutup-bawah');
    const overlayModal = document.getElementById('profil-modal-overlay');

    // Fungsi tutup
    function tutupProfil() {
        overlayModal.classList.remove('active');
    }

    // Pasang aksi klik
    if(btnTutupAtas) btnTutupAtas.addEventListener('click', tutupProfil);
    if(btnTutupBawah) btnTutupBawah.addEventListener('click', tutupProfil);

});