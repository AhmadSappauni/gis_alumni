document.addEventListener("DOMContentLoaded", function() {
    
    // Tangkap tombol toggle dari HTML
    const tombolCluster = document.getElementById('toggle-cluster-map'); 
    
    if(tombolCluster) {
        tombolCluster.addEventListener('change', function() {
            // Ubah variabel global agar filter.js tahu statusnya
            window.statusClusterAktif = this.checked; 
            
            // Perintahkan filter.js untuk merefresh peta
            if (typeof window.perbaruiTampilanPeta === 'function') {
                window.perbaruiTampilanPeta();
            }
        });
    }

});