<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\c;
use App\Models\PelaporanKePolisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class PelaporanKePolisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Ambil semua data pelaporan dari database
            $laporan = PelaporanKePolisi::all();

            // Memberikan respons dengan data laporan
            return response()->json(['data' => $laporan], 200);
        } catch (\Exception $e) {
            // Log pesan kesalahan
            Log::error($e->getMessage());

            // Memberikan respons dengan pesan kesalahan
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data pelaporan'], 500);
        }
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
        try {
            // Validasi data yang diterima dari request
            $validatedData = $request->validate([
                'judul_pelaporan' => 'required|string',
                'isi_laporan' => 'required|string',
                'visibility' => 'required|in:anonim,public',
                'tanggal_kejadian' => 'required|date',
                'lokasi_kejadian' => 'required|string',
                'file' => 'nullable|mimes:pdf,doc,docx|max:2048',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                // tambahkan aturan validasi lainnya sesuai kebutuhan
            ]);

            // Ubah format tanggal menjadi 'YYYY-MM-DD'
            $tanggal_kejadian = Carbon::parse($validatedData['tanggal_kejadian'])->format('Y-m-d');

            // Menyimpan file jika ada
            $file_path = $request->file('file') ? $request->file('file')->store('files', 'public') : null;

            // Menyimpan gambar jika ada
            $gambar = $request->file('gambar') ? $request->file('gambar')->store('images', 'public') : null;

            // Membuat entri baru dalam database
            $pelaporan = PelaporanKePolisi::create([
                'user_id' => auth()->id(),
                'judul_pelaporan' => $validatedData['judul_pelaporan'],
                'isi_laporan' => $validatedData['isi_laporan'],
                'visibility' => $validatedData['visibility'],
                'file_path' => $file_path,
                'gambar' => $gambar,
                'tanggal_kejadian' => $tanggal_kejadian, // Gunakan format yang valid
                'lokasi_kejadian' => $validatedData['lokasi_kejadian'],
            ]);

            // Memberikan respons dengan data pelaporan yang baru dibuat
            return response()->json(['message' => 'Pelaporan berhasil disimpan', 'data' => $pelaporan], 201);
        } catch (\Exception $e) {
            // Log pesan kesalahan
            Log::error($e->getMessage());

            // Memberikan respons dengan pesan kesalahan
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan pelaporan'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(c $c)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(c $c)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, c $c)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(c $c)
    {
        //
    }
}
