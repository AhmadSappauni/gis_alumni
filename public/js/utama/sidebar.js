// =====================================================================
// LOGIKA SIDEBAR MENU (Statistik & Mahasiswa)
// =====================================================================
const btnOpenSidebar = document.getElementById("open-sidebar");
const btnCloseSidebar = document.getElementById("close-sidebar");
const mainSidebar = document.getElementById("main-sidebar");
const sidebarOverlay = document.getElementById("sidebar-overlay");

// Fungsi membuka sidebar
function bukaSidebar() {
    mainSidebar.classList.add("active");
    sidebarOverlay.classList.add("active");
}

// Fungsi menutup sidebar
function tutupSidebar() {
    mainSidebar.classList.remove("active");
    sidebarOverlay.classList.remove("active");
}

// Pemicu tombol
btnOpenSidebar.addEventListener("click", bukaSidebar);
btnCloseSidebar.addEventListener("click", tutupSidebar);

// Klik area gelap untuk menutup
sidebarOverlay.addEventListener("click", tutupSidebar);
