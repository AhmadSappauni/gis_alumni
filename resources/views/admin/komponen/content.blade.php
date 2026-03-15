<main class="main-content">

    <div class="cards-grid">

        @foreach ($dataAlumni as $alumni)
            <div class="data-card glass-panel">

                <div class="card-header">
                    <div>
                        <h3>{{ $alumni->nama_lengkap }}</h3>
                        <div style="font-size:11px; color:var(--pilkom-blue-dark); font-weight:700; margin-top:3px;">
                            {{ $alumni->nim }}</div>
                    </div>
                    <span>Lulusan '{{ substr($alumni->tahun_lulus, 2) }}</span>
                </div>

                <div class="card-body">
                    @if ($alumni->nama_perusahaan)
                        <div class="info-row">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            <b>{{ $alumni->nama_perusahaan }}</b>
                        </div>
                        <div class="info-row">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ $alumni->jabatan }}</span>
                        </div>
                    @else
                        <div
                            style="padding: 10px; text-align:center; background:rgba(255,255,255,0.4); border-radius:10px; border:1px dashed #cbd5e1;">
                            <span style="color:#64748b; font-style:italic; font-size:12px;">Belum mengisi data
                                pekerjaan</span>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    @if ($alumni->linearitas == 'Linier')
                        <span class="badge-linier">Linier</span>
                    @elseif($alumni->linearitas == 'Tidak Linier')
                        <span class="badge-tidak">Tidak Linier</span>
                    @else
                        <span></span>
                    @endif

                    <div class="action-buttons">
                        <a href="#" class="btn-icon edit" title="Edit Data">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                        </a>
                        <a href="#" class="btn-icon delete" title="Hapus Data">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        @endforeach

    </div>
</main>
