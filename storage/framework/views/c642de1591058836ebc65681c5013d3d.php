<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin-style.css')); ?>">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <?php echo $__env->yieldPushContent('styles'); ?>

    <style>
    body {
        overflow: hidden; 
    }
    .main-content {
        overflow-y: auto;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* --- MODAL OVERLAY (KUNCI AGAR MELAYANG) --- */
    .profil-modal-overlay {
        position: fixed; /* Membuat modal melayang di atas segalanya */
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%;
        background: rgba(0, 41, 75, 0.45); /* Biru gelap transparan */
        backdrop-filter: blur(10px); /* Efek blur di belakang modal */
        display: none; /* Sembunyi secara default */
        justify-content: center;
        align-items: center;
        z-index: 10000; /* Pastikan di atas sidebar dan konten */
        padding: 20px;
        transition: all 0.3s ease;
    }

    /* Class ini akan dipicu oleh JavaScript (.classList.add('active')) */
    .profil-modal-overlay.active { 
        display: flex; 
        animation: fadeInModal 0.3s ease; 
    }

    /* --- MODAL CARD (UKURAN COMPACT & MEWAH) --- */
    .profil-modal-card {
        background: rgba(255, 255, 255, 0.98);
        width: 100%;
        max-width: 480px; /* Ukuran tidak terlalu besar */
        border-radius: 30px;
        padding: 30px;
        position: relative;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.8);
    }

    /* AVATAR BULAT SEMPURNA */
    .profil-avatar-outer {
        margin-top: -85px; 
        margin-bottom: 15px;
        display: flex;
        justify-content: center;
    }
    .profil-avatar-outer img {
        width: 110px; 
        height: 110px;
        border-radius: 50%; /* Dibuat bulat agar premium */
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* TIPOGRAFI */
    .badge-nim { background: #f1f5f9; color: #64748b; padding: 5px 12px; border-radius: 10px; font-size: 11px; font-weight: 700; }
    .badge-tahun { background: rgba(0, 74, 135, 0.1); color: #004a87; padding: 5px 12px; border-radius: 10px; font-size: 11px; font-weight: 700; }

    /* INFO GROUPS */
    .info-group-full {
        display: flex;
        align-items: center;
        gap: 15px;
        background: rgba(0, 74, 135, 0.03);
        padding: 15px;
        border-radius: 18px;
        border: 1px dashed rgba(0, 74, 135, 0.2);
        margin-bottom: 15px;
    }

    .row-grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 12px; 
        margin-bottom: 15px;
    }

    .info-group {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        padding: 12px 15px;
        border-radius: 15px;
        border: 1px solid #f1f5f9;
    }

    /* ICON SVG MINIMALIS */
    .info-icon {
        width: 20px;
        height: 20px;
        color: #64748b;
        min-width: 20px;
        stroke-width: 1.5px !important;
    }

    .info-text label {
        display: block;
        font-size: 9px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 800;
        margin-bottom: 1px;
        letter-spacing: 0.5px;
    }
    .info-text p {
        margin: 0;
        color: #1e293b;
        font-weight: 700;
        font-size: 13px;
        line-height: 1.2;
    }

    /* BUTTONS */
    .btn-tutup-modal {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 18px;
        background: linear-gradient(135deg, #004a87 0%, #00335d 100%);
        color: white;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 10px 20px rgba(0, 74, 135, 0.2);
        cursor: pointer;
        transition: 0.3s;
    }

    .close-modal-btn {
        position: absolute;
        top: 20px;
        right: 25px;
        font-size: 24px;
        color: #cbd5e1;
        cursor: pointer;
        background: none;
        border: none;
    }

    .status-badge {
        padding: 6px 15px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 11px;
        margin-bottom: 15px;
    }
    .status-linier { background: #dcfce7; color: #166534; }
    .status-tidak { background: #fee2e2; color: #991b1b; }

    @keyframes fadeInModal { 
        from { opacity: 0; transform: translateY(-20px) scale(0.95); } 
        to { opacity: 1; transform: translateY(0) scale(1); } 
    }
</style>
</head>

<body>
    <?php echo $__env->make('admin.komponen.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.komponen.modal-profil', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <main class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Script dari halaman -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php if(session('success')): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Data berhasil disimpan',
        text: '<?php echo e(session("success")); ?>',
        confirmButtonColor:'#004a87',
        timer:2000,
        showConfirmButton:false
    });
    </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo e(session("error")); ?>'
    });
    </script>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtns = document.querySelectorAll('.dropdown-btn');

        dropdownBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.nav-dropdown');
                parent.classList.toggle('active');
            });
        });

        // Auto open jika di halaman tertentu
        if (window.location.href.includes('create') || window.location.href.includes('import')) {
            const activeDropdown = document.querySelector('.nav-dropdown');
            if (activeDropdown) activeDropdown.classList.add('active');
        }
    });

    function showAlumniDetail(alumni) {
    const modal = document.getElementById('profil-modal-overlay');
    
    // Set Data Identitas & Skripsi
    document.getElementById('modal-nama').innerText = alumni.nama_lengkap;
    document.getElementById('modal-email').innerText = alumni.email || '-';
    document.getElementById('modal-nohp').innerText = alumni.no_hp || '-';
    document.getElementById('modal-nim').innerText = 'NIM: ' + alumni.nim;
    document.getElementById('modal-tahun').innerText = 'Lulusan ' + alumni.tahun_lulus;
    document.getElementById('modal-skripsi').innerText = alumni.judul_skripsi || "Belum menginputkan judul skripsi.";

    // Set Foto
    const avatar = document.getElementById('modal-avatar');
    if(alumni.foto_profil) {
        avatar.src = "/storage/" + alumni.foto_profil;
    } else {
        avatar.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(alumni.nama_lengkap)}&background=004a87&color=fff&size=128`;
    }

    // Set Data Pekerjaan
    if(alumni.pekerjaan) {
        document.getElementById('modal-perusahaan').innerText = alumni.pekerjaan.nama_perusahaan || '-';
        document.getElementById('modal-jabatan').innerText = alumni.pekerjaan.jabatan || '-';
        document.getElementById('modal-bidang').innerText = alumni.pekerjaan.bidang_pekerjaan || '-';
        document.getElementById('modal-gaji').innerText = alumni.pekerjaan.gaji || 'Tidak dicantumkan';
        
        // Linearitas Badge
        const linBadge = document.getElementById('modal-linearitas');
        linBadge.innerText = alumni.pekerjaan.linearitas;
        linBadge.className = 'status-badge ' + (alumni.pekerjaan.linearitas === 'Linier' ? 'status-linier' : 'status-tidak');

        // LinkedIn
        const lnLink = document.getElementById('modal-linkedin');
        if(alumni.pekerjaan.link_linkedin) {
            lnLink.href = alumni.pekerjaan.link_linkedin;
            document.getElementById('linkedin-container').style.display = 'flex';
        } else {
            document.getElementById('linkedin-container').style.display = 'none';
        }
    } else {
        // Jika belum ada data pekerjaan
        document.getElementById('modal-perusahaan').innerText = 'Belum bekerja';
        document.getElementById('modal-jabatan').innerText = '-';
        document.getElementById('modal-bidang').innerText = '-';
        document.getElementById('modal-gaji').innerText = '-';
        document.getElementById('linkedin-container').style.display = 'none';
        document.getElementById('modal-linearitas').style.display = 'none';
    }

    modal.classList.add('active');
}

// Handler Tutup Modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('profil-modal-overlay');
    const closeBtns = ['close-profil-modal', 'btn-tutup-bawah'];
    
    closeBtns.forEach(id => {
        const btn = document.getElementById(id);
        if(btn) btn.onclick = () => modal.classList.remove('active');
    });

    // Tutup saat klik area luar
    window.onclick = (event) => {
        if (event.target == modal) modal.classList.remove('active');
    };
});
    </script>
    
</body>
</html><?php /**PATH D:\Aplikasi_Skripsi\gis-alumni\resources\views/admin/layout.blade.php ENDPATH**/ ?>