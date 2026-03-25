<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Pekerjaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class AdminAlumniController extends Controller
{
    public function index()
    {
        $dataAlumni = Alumni::with('pekerjaan')
            ->orderBy('tahun_lulus', 'desc')
            ->paginate(10);

        return view('admin.index', compact('dataAlumni'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'nim' => 'required|unique:alumnis,nim',
            'nama_lengkap' => 'required',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|numeric',
            'tahun_lulus' => 'required|numeric',
            'nama_perusahaan' => 'required',
            'jabatan' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('alumni_foto', 'public');
        }

        try {

            DB::transaction(function () use ($request, $fotoPath) {

                Alumni::create([
                    'nim' => $request->nim,
                    'nama_lengkap' => $request->nama_lengkap,
                    'email' => $request->email,
                    'no_hp' => $request->no_hp,
                    'angkatan' => $request->angkatan,
                    'tahun_lulus' => $request->tahun_lulus,
                    'judul_skripsi' => $request->judul_skripsi,
                    'foto_profil' => $fotoPath
                ]);

                Pekerjaan::create([
                    'nim' => $request->nim,
                    'nama_perusahaan' => $request->nama_perusahaan,
                    'jabatan' => $request->jabatan,
                    'bidang_pekerjaan' => $request->bidang,
                    'gaji' => $request->gaji,
                    'kota' => $request->kota,
                    'alamat_lengkap' => $request->alamat_lengkap,
                    'link_linkedin' => $request->linkedin,
                    'linearitas' => $request->linearitas,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]);
            });

            return redirect()->route('admin.alumni.index')
                ->with('success', 'Data Alumni berhasil ditambahkan');
        } catch (\Exception $e) {

            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }

            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
    public function checkNim(Request $request)
    {
        $nim = $request->nim;

        $exists = Alumni::where('nim', $nim)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    public function importPage()
    {
        return view('admin.import.import-excel');
    }

    public function importPreview(Request $request)
    {
        $file = $request->file('file');

        $data = Excel::toArray([], $file);

        $rows = $data[0];

        array_shift($rows); // hapus header excel

        return response()->json($rows);
    }

    public function importStore(Request $request)
    {
        $rows = json_decode($request->rows, true);
        $success = 0;
        $skip = 0;

        foreach ($rows as $row) {
            $nim = $row[0];

            if (Alumni::where('nim', $nim)->exists()) {
                $skip++;
                continue;
            }

            $lokasiPencarian = $row[5] ?? $row[3] ?? null; 
            $lat = null;
            $lng = null;
            
            if ($lokasiPencarian && $lokasiPencarian !== '-') {
                try {
                    // Proses Geocoding via Nominatim API
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'User-Agent' => 'WebGIS-Alumni-ULM' // Wajib ada User-Agent
                    ])->get("https://nominatim.openstreetmap.org/search", [
                        'q'      => $lokasiPencarian,
                        'format' => 'json',
                        'limit'  => 1
                    ]);

                    if ($response->successful() && isset($response->json()[0])) {
                        $lat = $response->json()[0]['lat'];
                        $lng = $response->json()[0]['lon'];
                    }
                    
                    // Jeda 0.5 detik agar tidak melanggar aturan Nominatim (Max 1 req/sec)
                    usleep(500000); 
                } catch (\Exception $e) {
                    // Jika gagal, biarkan lat & lng tetap null
                }
            }

            $rawLinearitas = $row[6] ?? 'Tidak Linier';
            if (str_contains(strtolower($rawLinearitas), 'line')) {
                $fixLinearitas = 'Linier'; 
            } else {
                $fixLinearitas = 'Tidak Linier';
            }

            Alumni::create([
                'nim' => $row[0],
                'nama_lengkap' => $row[1],
                'email' => $row[7] ?? null,
                'no_hp' => $row[8] ?? null,
                'tahun_lulus' => $row[2],
                'angkatan' => null,
                'judul_skripsi' => null,
                'foto_profil' => null
            ]);

            Pekerjaan::create([
                'nim' => $row[0],
                'nama_perusahaan' => $row[3] ?? '-',
                'jabatan' => $row[4] ?? '-',
                'bidang_pekerjaan' => '-',
                'gaji' => null,
                'kota' => $row[5] ?? '-',
                'alamat_lengkap' => $row[5] ?? '-',
                'link_linkedin' => null,
                'linearitas' => $fixLinearitas,
                'latitude' => $lat,
                'longitude' => $lng,
            ]);

            $success++;
        }

        return response()->json([
            'success' => $success,
            'skip' => $skip
        ]);
    }
    public function destroy($nim)
    {
        try {
            $alumni = Alumni::where('nim', $nim)->firstOrFail();

            // 1. Hapus Foto Profil jika ada di storage
            if ($alumni->foto_profil && Storage::disk('public')->exists($alumni->foto_profil)) {
                Storage::disk('public')->delete($alumni->foto_profil);
            }

            // 2. Hapus data (Relasi Pekerjaan akan ikut terhapus jika di DB diset Cascade, 
            // jika tidak, kita hapus manual di sini)
            DB::transaction(function () use ($alumni) {
                $alumni->pekerjaan()->delete(); // Hapus data di tabel pekerjaans
                $alumni->delete(); // Hapus data di tabel alumnis
            });

            return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil dihapus!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function edit($nim)
    {
        $alumni = Alumni::with('pekerjaan')->where('nim', $nim)->firstOrFail();
        return view('admin.edit', compact('alumni'));
    }

    public function update(Request $request, $nim)
    {
        $alumni = Alumni::where('nim', $nim)->firstOrFail();

        $request->validate([
            'nim' => 'required|unique:alumnis,nim,' . $alumni->nim . ',nim',
            'nama_lengkap' => 'required',
            'tahun_lulus' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $fotoPath = $alumni->foto_profil;

        // Jika ada upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($alumni->foto_profil) {
                Storage::disk('public')->delete($alumni->foto_profil);
            }
            $fotoPath = $request->file('foto')->store('alumni_foto', 'public');
        }

        try {
            DB::transaction(function () use ($request, $alumni, $fotoPath) {
                // Update data Alumni
                $alumni->update([
                    'nim' => $request->nim,
                    'nama_lengkap' => $request->nama_lengkap,
                    'email' => $request->email,
                    'no_hp' => $request->no_hp,
                    'angkatan' => $request->angkatan,
                    'tahun_lulus' => $request->tahun_lulus,
                    'judul_skripsi' => $request->judul_skripsi,
                    'foto_profil' => $fotoPath
                ]);

                // Update data Pekerjaan (Gunakan update NIM jika NIM berubah)
                $alumni->pekerjaan()->update([
                    'nim' => $request->nim,
                    'nama_perusahaan' => $request->nama_perusahaan,
                    'jabatan' => $request->jabatan,
                    'bidang_pekerjaan' => $request->bidang,
                    'linearitas' => $request->linearitas,
                    'kota' => $request->kota,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            });

            return redirect()->route('admin.alumni.index')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }
}
