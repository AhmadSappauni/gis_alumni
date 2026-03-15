<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAlumniController extends Controller
{
    public function index()
    {
        // Mengambil data alumni dan menggabungkannya (JOIN) dengan tabel pekerjaans
        $dataAlumni = DB::table('alumnis')
            ->leftJoin('pekerjaans', 'alumnis.nim', '=', 'pekerjaans.nim')
            ->select(
                'alumnis.nim', 
                'alumnis.nama_lengkap', 
                'alumnis.tahun_lulus',
                'pekerjaans.nama_perusahaan',
                'pekerjaans.jabatan',
                'pekerjaans.linearitas'
            )
            ->orderBy('alumnis.tahun_lulus', 'desc')
            ->get();

        return view('admin.index', compact('dataAlumni'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nim' => 'required|unique:alumnis,nim',
            'nama_lengkap' => 'required',
            'tahun_lulus' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // Simpan ke dua tabel secara bersamaan
        DB::transaction(function () use ($request) {
            // 1. Simpan ke tabel alumnis
            DB::table('alumnis')->insert([
                'nim' => $request->nim,
                'nama_lengkap' => $request->nama_lengkap,
                'tahun_lulus' => $request->tahun_lulus,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Simpan ke tabel pekerjaans
            DB::table('pekerjaans')->insert([
                'nim' => $request->nim,
                'nama_perusahaan' => $request->nama_perusahaan,
                'jabatan' => $request->jabatan,
                'linearitas' => $request->linearitas,
                'alamat_lengkap' => $request->alamat_lengkap,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('admin.alumni.index')->with('success', 'Data Alumni Berhasil Ditambahkan!');
    }
}