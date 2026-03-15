@extends('admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-create.css') }}">
@endpush

@section('content')
    <header class="top-header glass-panel">
        <h1>Tambah Alumni Baru</h1>
        <a href="{{ route('admin.alumni.index') }}" class="btn-kembali" style="background: rgba(255,255,255,0.5); padding: 10px 20px; border-radius: 12px; text-decoration: none; color: #004a87; font-weight: 600; font-size: 13px;">
            ← Batal
        </a>
    </header>
    <div class="glass-panel" style="padding: 30px;">
        <form action="{{ route('admin.alumni.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                
                <div class="form-section">
                    <h3 style="margin-bottom: 20px; color: #004a87;">Data Personal</h3>
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; font-size: 12px; font-weight:700; color:#64748b; margin-bottom:5px;">NIM</label>
                        <input type="text" name="nim" class="custom-input-admin" placeholder="Contoh: 2110131..." required>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; font-size: 12px; font-weight:700; color:#64748b; margin-bottom:5px;">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="custom-input-admin" required>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; font-size: 12px; font-weight:700; color:#64748b; margin-bottom:5px;">Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" class="custom-input-admin" value="2024" required>
                    </div>
                </div>

                <div class="form-section">
                    <h3 style="margin-bottom: 20px; color: #004a87;">Data Pekerjaan</h3>
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; font-size: 12px; font-weight:700; color:#64748b; margin-bottom:5px;">Instansi/Perusahaan</label>
                        <input type="text" name="nama_perusahaan" class="custom-input-admin" required>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; font-size: 12px; font-weight:700; color:#64748b; margin-bottom:5px;">Jabatan</label>
                        <input type="text" name="jabatan" class="custom-input-admin" required>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; font-size: 12px; font-weight:700; color:#64748b; margin-bottom:5px;">Linearitas</label>
                        <select name="linearitas" class="custom-input-admin">
                            <option value="Linier">Linier</option>
                            <option value="Tidak Linier">Tidak Linier</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <h3 style="margin-bottom: 15px; color: #004a87;">Lokasi Kerja</h3>
                <div id="map-tambah" style="height: 350px !important; min-height: 350px; width: 100%; border-radius: 20px;"></div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div>
                        <label style="display:block; font-size: 11px; font-weight:700; color:#64748b;">Latitude</label>
                        <input type="text" name="latitude" id="lat" class="custom-input-admin" style="background: rgba(255,255,255,0.3);" readonly required>
                    </div>
                    <div>
                        <label style="display:block; font-size: 11px; font-weight:700; color:#64748b;">Longitude</label>
                        <input type="text" name="longitude" id="lng" class="custom-input-admin" style="background: rgba(255,255,255,0.3);" readonly required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-tambah" style="width: 100%; justify-content: center; margin-top: 30px; padding: 18px;">
                Simpan Data Alumni
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/create.js') }}"></script>
@endpush