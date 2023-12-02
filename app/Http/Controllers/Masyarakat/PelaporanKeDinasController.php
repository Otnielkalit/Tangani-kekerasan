<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\c;
use App\Models\PelaporanKeDinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PelaporanKeDinasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $laporan = PelaporanKeDinas::all();
            return response()->json(['data' => $laporan], 200);
        } catch (\Exception $e) {
            // Log pesan kesalahan
            Log::error($e->getMessage());
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
            $validatedData = $request->validate([
                'judul_pelaporan' => 'required|string',
                'isi_laporan' => 'required|string',
                'visibility' => 'required|in:anonim,public',
                'tanggal_kejadian' => 'required|date',
                'lokasi_kejadian' => 'required|string',
                'file' => 'nullable|mimes:pdf,doc,docx|max:2048',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $tanggal_kejadian = Carbon::parse($validatedData['tanggal_kejadian'])->format('Y-m-d');
            $file_path = $request->file('file') ? $request->file('file')->store('files', 'public') : null;
            $gambar = $request->file('gambar') ? $request->file('gambar')->store('images', 'public') : null;
            $pelaporan = PelaporanKeDinas::create([
                'user_id' => auth()->id(),
                'judul_pelaporan' => $validatedData['judul_pelaporan'],
                'isi_laporan' => $validatedData['isi_laporan'],
                'visibility' => $validatedData['visibility'],
                'file_path' => $file_path,
                'gambar' => $gambar,
                'tanggal_kejadian' => $tanggal_kejadian,
                'lokasi_kejadian' => $validatedData['lokasi_kejadian'],
            ]);
            return response()->json(['message' => 'Pelaporan berhasil disimpan', 'data' => $pelaporan], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan pelaporan'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $laporan = PelaporanKeDinas::find($id);
            if (!$laporan) {
                return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
            }
            return response()->json(['data' => $laporan], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil detail laporan'], 500);
        }
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
    public function update(Request $request, $id)
    {
        try {
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
            $laporan = PelaporanKeDinas::find($id);
            if (!$laporan) {
                return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
            }
            if ($laporan->file_path) {
                Storage::disk('public')->delete($laporan->file_path);
            }

            if ($laporan->gambar) {
                Storage::disk('public')->delete($laporan->gambar);
            }
            $file_path = $request->file('file') ? $request->file('file')->store('files', 'public') : null;
            $gambar = $request->file('gambar') ? $request->file('gambar')->store('images', 'public') : null;
            $laporan->update([
                'judul_pelaporan' => $validatedData['judul_pelaporan'],
                'isi_laporan' => $validatedData['isi_laporan'],
                'visibility' => $validatedData['visibility'],
                'file_path' => $file_path,
                'gambar' => $gambar,
                'tanggal_kejadian' => $validatedData['tanggal_kejadian'],
                'lokasi_kejadian' => $validatedData['lokasi_kejadian'],
            ]);
            return response()->json(['message' => 'Laporan berhasil diupdate', 'data' => $laporan], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengupdate laporan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $pelaporan = PelaporanKeDinas::findOrFail($id);
        if ($pelaporan->file_path) {
            Storage::disk('public')->delete($pelaporan->file_path);
        }
        if ($pelaporan->gambar) {
            Storage::disk('public')->delete($pelaporan->gambar);
        }
        $pelaporan->delete();
        return response()->json(['message' => 'Laporan berhasil dihapus'], 200);
    }
}
