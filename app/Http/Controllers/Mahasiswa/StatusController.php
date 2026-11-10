<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\View\View;

class StatusController extends Controller
{
    public function index(): View
    {
        $query = Pendaftaran::query()->with('lowongan');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->whereKey(session('mahasiswa.pendaftaran_id', 0));
        }

        $record = $query->latest()->first();

        if (! $record) {
            return view('mahasiswa.status', [
                'pendaftaran' => null,
                'langkah' => [],
            ]);
        }

        $status = $record->status;
        $statusText = match ($status) {
            'disetujui' => 'Pendaftaran disetujui',
            'ditolak' => 'Pendaftaran ditolak',
            default => 'Menunggu keputusan dosen',
        };
        $isReviewed = $record->is_published;

        $pendaftaran = [
            'matkul' => $record->lowongan?->mata_kuliah ?? 'Lowongan tidak tersedia',
            'kode' => $record->lowongan?->kode ?? 'MK',
            'sks' => $record->lowongan?->sks ?? 0,
            'didaftarkan_pada' => $record->created_at?->translatedFormat('d M Y, H:i') ?? '-',
            'status_terkini' => $statusText,
            'nim' => $record->nim,
            'nama' => $record->nama,
            'nilai_matkul' => $record->nilai_matkul,
            'cv' => $record->cv_path ? basename($record->cv_path) : 'Tidak diminta',
            'transkrip' => $record->transkrip_path ? basename($record->transkrip_path) : 'Tidak diminta',
            'catatan_dosen' => $isReviewed ? $record->catatan_dosen : null,
        ];

        $langkah = [
            [
                'judul' => 'Terdaftar',
                'deskripsi' => 'Formulir dan dokumen berhasil dikirim.',
                'waktu' => $pendaftaran['didaftarkan_pada'],
                'selesai' => true,
            ],
            [
                'judul' => 'Verifikasi berkas',
                'deskripsi' => $isReviewed ? 'Dosen telah meninjau berkas pendaftaran.' : 'Dosen memeriksa kelengkapan dokumen.',
                'waktu' => $isReviewed ? 'Selesai' : 'Menunggu proses',
                'selesai' => $isReviewed,
            ],
            [
                'judul' => 'Seleksi / Interview',
                'deskripsi' => $status === 'disetujui'
                    ? 'Pendaftaran disetujui.'
                    : ($status === 'ditolak' ? 'Pendaftaran tidak dilanjutkan.' : 'Jadwal akan ditampilkan jika kamu lolos tahap berkas.'),
                'waktu' => $isReviewed ? 'Selesai' : 'Belum dimulai',
                'selesai' => $isReviewed,
            ],
            [
                'judul' => 'Hasil akhir',
                'deskripsi' => $isReviewed ? $statusText : 'Hasil diumumkan oleh dosen pengampu.',
                'waktu' => $isReviewed ? $record->updated_at?->translatedFormat('d M Y, H:i') : 'Belum diumumkan',
                'selesai' => $isReviewed,
            ],
        ];

        return view('mahasiswa.status', compact('pendaftaran', 'langkah'));
    }
}
