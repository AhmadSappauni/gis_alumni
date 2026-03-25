@extends('admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-create.css') }}">
    <style>
        /* FIX TOMBOL NAVIGASI: Supaya 'Sebelumnya' di kiri dan 'Lanjut' di kanan */
        .btn-navigation {
            display: flex !important;
            justify-content: space-between !important;
            /* Ini kuncinya */
            align-items: center !important;
            margin-top: 40px !important;
            padding-top: 25px !important;
            border-top: 1px solid rgba(0, 0, 0, 0.08) !important;
            width: 100% !important;
        }

        #nextBtn {
            margin-left: auto;
            /* Memaksa tombol Lanjut ke kanan jika tombol Sebelumnya sembunyi */
            background: #004a87 !important;
            color: white !important;
            padding: 12px 30px !important;
            border-radius: 12px !important;
            border: none !important;
            font-weight: 700 !important;
            transition: 0.3s;
        }

        #prevBtn {
            background: #94a3b8 !important;
            color: white !important;
            padding: 12px 30px !important;
            border-radius: 12px !important;
            border: none !important;
            font-weight: 700 !important;
        }

        /* Styling Step Wizard agar konsisten */
        .step.active {
            background: #004a87 !important;
            border-color: #fff !important;
            box-shadow: 0 0 0 4px rgba(0, 74, 135, 0.2) !important;
        }

        .btn-batal {
            background: #fdb813;
            padding: 10px 18px;
            border-radius: 12px;
            text-decoration: none;
            color: #004a87;
            font-weight: 700;
            font-size: 13px;
        }
    </style>
@endpush

@section('content')
    <header class="top-header glass-panel">
        <h1>Edit Data Alumni</h1>
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="{{ route('admin.alumni.index') }}" class="btn-batal">← Kembali</a>
        </div>
    </header>

    <div class="glass-panel" style="padding: 40px; max-width: 1000px; margin: 0 auto;">
        <div class="progress-container">
            <div id="progress-bar"></div>
        </div>
        <div class="step-wizard">
            <div class="step active" id="s1">1</div>
            <div class="step" id="s2">2</div>
            <div class="step" id="s3">3</div>
        </div>

        @if (session('error'))
            <div style="background:#fee2e2;color:#991b1b;padding:10px;margin-bottom:20px;border-radius:8px;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;margin-bottom:15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.alumni.update', $alumni->nim) }}" method="POST" enctype="multipart/form-data"
            id="wizardForm">
            @csrf
            @method('PUT')

            <div class="form-step active" id="step1">
                <h3 style="color: var(--pilkom-blue-dark); margin-bottom: 25px;">Data Pribadi & Akademik</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label class="label-admin">NIM</label>
                        <input type="text" name="nim" id="nim" class="custom-input-admin"
                            value="{{ old('nim', $alumni->nim) }}" placeholder="211013...">
                        <small id="nim-status"></small>
                    </div>
                    <div>
                        <label class="label-admin">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="custom-input-admin"
                            value="{{ old('nama_lengkap', $alumni->nama_lengkap) }}" required>
                    </div>
                    <div>
                        <label class="label-admin">Angkatan</label>
                        <input type="number" name="angkatan" class="custom-input-admin"
                            value="{{ old('angkatan', $alumni->angkatan) }}">
                    </div>
                    <div>
                        <label class="label-admin">Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" class="custom-input-admin"
                            value="{{ old('tahun_lulus', $alumni->tahun_lulus) }}">
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <label class="label-admin">Judul Skripsi</label>
                    <textarea name="judul_skripsi" class="custom-input-admin" rows="2">{{ old('judul_skripsi', $alumni->judul_skripsi) }}</textarea>
                </div>
                <div style="margin-top: 20px; background: rgba(0,74,135,0.03); padding: 20px; border-radius: 15px;">
                    <label class="label-admin">Foto Profil</label>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div style="position: relative;">
                            <img src="{{ asset('storage/' . $alumni->foto_profil) }}"
                                style="width:100px; height:100px; object-fit:cover; border-radius:20px; border:3px solid white; box-shadow:0 10px 20px rgba(0,0,0,0.1);">
                            <div
                                style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); background: #004a87; color: white; padding: 2px 10px; border-radius: 10px; font-size: 10px; white-space: nowrap;">
                                Foto Saat Ini
                            </div>
                        </div>
                        <div style="flex-grow: 1;">
                            <input type="file" name="foto" id="foto" class="custom-input-admin">
                            <small style="color: #64748b; display: block; margin-top: 8px;">Pilih file baru jika ingin
                                mengganti foto</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-step" id="step2">
                <h3 style="color: var(--pilkom-blue-dark); margin-bottom: 25px;">Informasi Karir</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label class="label-admin">Pekerjaan / Jabatan</label>
                        <input type="text" name="jabatan" class="custom-input-admin"
                            value="{{ old('jabatan', $alumni->pekerjaan->jabatan ?? '') }}"
                            placeholder="Software Engineer">
                    </div>
                    <div>
                        <label class="label-admin">Kategori Bidang</label>
                        <select name="bidang" class="custom-input-admin">
                            @foreach (['IT & Software', 'Pendidikan / Guru', 'Pemerintahan', 'Wiraswasta'] as $bidang)
                                <option value="{{ $bidang }}"
                                    {{ old('bidang', $alumni->pekerjaan->bidang_pekerjaan ?? '') == $bidang ? 'selected' : '' }}>
                                    {{ $bidang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label-admin">Linearitas</label>
                        <select name="linearitas" class="custom-input-admin">
                            <option value="Linier"
                                {{ old('linearitas', $alumni->pekerjaan->linearitas ?? '') == 'Linier' ? 'selected' : '' }}>
                                Linier</option>
                            <option value="Tidak Linier"
                                {{ old('linearitas', $alumni->pekerjaan->linearitas ?? '') == 'Tidak Linier' ? 'selected' : '' }}>
                                Tidak Linier</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-admin">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" class="custom-input-admin"
                            value="{{ old('nama_perusahaan', $alumni->pekerjaan->nama_perusahaan ?? '') }}">
                    </div>
                    <div>
                        <label class="label-admin">Estimasi Gaji (Opsional)</label>
                        <input type="text" name="gaji" class="custom-input-admin"
                            value="{{ old('gaji', $alumni->pekerjaan->gaji ?? '') }}" placeholder="Rp 5.000.000">
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <label class="label-admin">Link LinkedIn</label>
                    <input type="url" name="linkedin" class="custom-input-admin"
                        value="{{ old('linkedin', $alumni->pekerjaan->link_linkedin ?? '') }}"
                        placeholder="https://linkedin.com/in/username">
                </div>
            </div>

            <div class="form-step" id="step3">
                <h3 style="color: var(--pilkom-blue-dark); margin-bottom: 25px;">Konfirmasi Lokasi</h3>
                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px;">
                    <div>
                        <div id="map-tambah"
                            style="height: 400px; border-radius: 20px; border: 4px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div>
                            <label class="label-admin">Kota / Kabupaten</label>
                            <input type="text" name="kota" id="kota" class="custom-input-admin"
                                value="{{ $alumni->pekerjaan->kota ?? '' }}">
                        </div>
                        <div>
                            <label class="label-admin">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" id="alamat_lengkap" class="custom-input-admin" rows="3" readonly>{{ $alumni->pekerjaan->alamat_lengkap ?? '' }}</textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <input type="text" name="latitude" id="lat" class="custom-input-admin"
                                placeholder="Lat" readonly>
                            <input type="text" name="longitude" id="lng" class="custom-input-admin"
                                placeholder="Lng" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div id="review-box"
                style="display:none; margin-top:35px; padding:25px; border-radius:20px; background: rgba(255,255,255,0.6); border: 1px solid white;">
                <h4
                    style="margin-bottom:20px; color:#004a87; display:flex; align-items:center; gap:10px; font-weight: 800;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Konfirmasi Perubahan Data
                </h4>
                <div class="review-grid">
                    <div class="review-item"><b>Identitas</b><span id="review_nama">-</span><br><small id="review_nim"
                            style="color:#64748b"></small></div>
                    <div class="review-item"><b>Akademik</b><span id="review_angkatan_lulus">-</span></div>
                    <div class="review-item"><b>Karir</b><span id="review_jabatan">-</span><br><small
                            id="review_perusahaan" style="color:#64748b"></small></div>
                    <div class="review-item"><b>Lokasi</b><span id="review_kota">-</span></div>
                </div>
            </div>

            <div class="btn-navigation">
                <button type="button" class="btn-tambah" id="prevBtn" onclick="nextPrev(-1)">Sebelumnya</button>

                <button type="button" class="btn-tambah" id="nextBtn" onclick="nextPrev(1)">Lanjut</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Definisikan koordinat sebelum memanggil create.js
        var oldLat = {{ $alumni->pekerjaan->latitude ?? -3.316694 }};
        var oldLng = {{ $alumni->pekerjaan->longitude ?? 114.590111 }};

        document.addEventListener("DOMContentLoaded", function() {
            // Beri jeda sedikit agar map di create.js terinisialisasi
            setTimeout(() => {
                if (typeof map !== 'undefined') {
                    map.setView([oldLat, oldLng], 15);
                    // Hapus marker lama jika ada dan tambah yang baru sesuai koordinat alumni
                    L.marker([oldLat, oldLng]).addTo(map)
                        .bindPopup(
                            "Lokasi saat ini: {{ $alumni->pekerjaan->nama_perusahaan ?? 'Alumni' }}")
                        .openPopup();
                }
            }, 800);
        });
    </script>
    <script src="{{ asset('js/admin/create.js') }}"></script>
@endpush
