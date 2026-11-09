<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmumkanHasilController extends Controller
{
    /**
     * 1. Menyimpan Evaluasi Dosen (Belum Diumumkan)
     * Mahasiswa tidak bisa melihat ini karena status publik belum berubah.
     */
    public function simpanEvaluasi(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate([
            'status_seleksi' => 'required|in:menunggu,disetujui,ditolak',
            'catatan_dosen'  => 'nullable|string',
        ]);

        $pendaftaran->update([
            'status_seleksi' => $validated['status_seleksi'],
            'catatan_dosen'  => $validated['catatan_dosen'],
        ]);

        return response()->json([
            'message' => 'Evaluasi berhasil disimpan. Status mahasiswa belum berubah sebelum hasil diumumkan.',
            'data'    => $pendaftaran
        ]);
    }

    /**
     * 2. Logika Tombol "Umumkan Hasil"
     * Menjalankan update massal untuk satu lowongan_id.
     */
    public function umumkanHasil(Request $request, $lowongan_id)
    {
        DB::transaction(function () use ($lowongan_id) {
            // Ambil semua pendaftar pada lowongan tersebut
            $daftarPendaftar = Pendaftaran::where('lowongan_id', $lowongan_id)->get();

            foreach ($daftarPendaftar as $pendaftar) {
                // Pindahkan status internal (status_seleksi) ke status publik (status)
                $statusAkhir = in_array($pendaftar->status_seleksi, ['disetujui', 'ditolak']) 
                                ? $pendaftar->status_seleksi 
                                : 'menunggu';

                $pendaftar->update([
                    'status'       => $statusAkhir, // Status ini yang dibaca mahasiswa
                    'is_published' => true,
                ]);
            }
        });

        return response()->json([
            'message' => 'Hasil seleksi berhasil diumumkan! Mahasiswa sekarang dapat melihat status terbaru mereka di dashboard.'
        ]);
    }
}