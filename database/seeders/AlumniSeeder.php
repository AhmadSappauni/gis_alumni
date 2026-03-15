<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumniSeeder extends Seeder
{
    public function run()
    {
        // 1. Masukkan Data Alumni
        DB::table('alumnis')->insert([
            ['nim' => '2110131220001', 'nama_lengkap' => 'Andi Pratama', 'tahun_lulus' => 2024, 'created_at' => now(), 'updated_at' => now()],
            ['nim' => '2110131220002', 'nama_lengkap' => 'Budi Santoso', 'tahun_lulus' => 2024, 'created_at' => now(), 'updated_at' => now()],
            ['nim' => '2110131220003', 'nama_lengkap' => 'Citra Kirana', 'tahun_lulus' => 2025, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Masukkan Data Pekerjaan & Titik Koordinat
        DB::table('pekerjaans')->insert([
            [
                'nim' => '2110131220001',
                'nama_perusahaan' => 'SMKN 1 Banjarmasin',
                'jabatan' => 'Guru TIK',
                'linearitas' => 'Linier', // Nanti marker warna biru
                'alamat_lengkap' => 'Jl. Mulawarman, Banjarmasin',
                'latitude' => -3.3194300, 
                'longitude' => 114.5908100,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nim' => '2110131220002',
                'nama_perusahaan' => 'Bank Kalsel Cabang Utama',
                'jabatan' => 'Customer Service',
                'linearitas' => 'Tidak Linier', // Nanti marker warna merah
                'alamat_lengkap' => 'Jl. Lambung Mangkurat, Banjarmasin',
                'latitude' => -3.3244200,
                'longitude' => 114.5901500,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nim' => '2110131220003',
                'nama_perusahaan' => 'Diskominfo Banjarbaru',
                'jabatan' => 'Staff IT / Programmer',
                'linearitas' => 'Linier', // Nanti marker warna biru
                'alamat_lengkap' => 'Jl. Pangeran Suriansyah, Banjarbaru',
                'latitude' => -3.4400500,
                'longitude' => 114.8300600,
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}