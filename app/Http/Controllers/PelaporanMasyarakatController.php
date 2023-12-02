<?php

namespace App\Http\Controllers;

use App\Models\PelaporanMasyarakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelaporanMasyarakatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporanMasyarakats = PelaporanMasyarakat::all();
        return response()->json($laporanMasyarakats, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_pelaporan' => 'required|string',
            'isi_laporan' => 'required|string',
            'pengaduan' => 'required|in:kekerasan,seksual',
            'tanggal_kejadian' => 'required|date',
            'lokasi_kejadian' => 'required|string',
            'file_path' => 'nullable|mimes:pdf,doc,docx', // Ubah sesuai dengan jenis file yang diinginkan
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000', // Ubah sesuai dengan jenis gambar yang diizinkan dan batas ukuran
            'visibility' => 'nullable|in:anonim,public', // Jika null, default akan menjadi 'public'
        ]);

        $user = Auth::user();
        $visibility = $request->input('visibility', 'public');
        $tanggal_kejadian = date("Y-m-d", strtotime($request->tanggal_kejadian));

        $laporan = new PelaporanMasyarakat([
            'user_id' => $user->id,
            'judul_pelaporan' => $request->judul_pelaporan,
            'isi_laporan' => $request->isi_laporan,
            'pengaduan' => $request->pengaduan,
            'tanggal_kejadian' => $tanggal_kejadian,
            'lokasi_kejadian' => $request->lokasi_kejadian,
            'visibility' => $visibility,
        ]);

        // Simpan nama file jika ada
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = $file->getClientOriginalName(); // Mendapatkan nama asli file
            $file->storeAs('uploads', $fileName); // Menyimpan file ke dalam direktori 'uploads'
            $laporan->file_path = $fileName; // Simpan nama file di kolom file_path
        }

        // Simpan nama file gambar jika ada
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = $image->getClientOriginalName(); // Mendapatkan nama asli gambar
            $image->storeAs('images', $imageName); // Menyimpan gambar ke dalam direktori 'images'
            $laporan->gambar = $imageName; // Simpan nama file gambar di kolom gambar
        }

        $laporan->save();

        return response()->json($laporan, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(PelaporanMasyarakat $pelaporanMasyarakat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PelaporanMasyarakat $pelaporanMasyarakat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PelaporanMasyarakat $pelaporanMasyarakat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PelaporanMasyarakat $pelaporanMasyarakat)
    {
        //
    }
}
