<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatusController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pendaftaran::query()->with('lowongan');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $sessionId = session('mahasiswa.pendaftaran_id', 0);
            if ($sessionId) {
                $query->where('id', $sessionId);
            }
        }

        $pendaftarans = $query->latest()->get();

        return view('mahasiswa.status', compact('pendaftarans'));
    }

    public function show(int $id): View
    {
        $record = Pendaftaran::query()->with('lowongan')->findOrFail($id);

        $status = $record->status;
        $statusText = match ($status) {
            'disetujui' => 'Pendaftaran disetujui',
            'ditolak' => 'Pendaftaran ditolak',
            default => 'Menunggu keputusan dosen',
        };
        $isReviewed = $record->is_published;
        
        $statusBerkas = $record->status_berkas ?? ($record->berkas_diverifikasi ? 'diverifikasi' : 'menunggu');
        $isVerified = ($statusBerkas === 'diverifikasi' || $isReviewed);
        $isRejectedBerkas = ($statusBerkas === 'ditolak');
        $hasInterview = !empty($record->jadwal_wawancara);

        $pendaftaran = [
            'id' => $record->id,
            'matkul' => $record->lowongan?->mata_kuliah ?? 'Algoritma Struktur Data',
            'kode' => $record->lowongan?->kode ?? 'IF101',
            'sks' => $record->lowongan?->sks ?? 3,
            'didaftarkan_pada' => $record->created_at?->translatedFormat('d M Y, H:i') ?? '22 Okt 2026, 08:18',
            'status' => $status,
            'status_terkini' => $statusText,
            'nim' => $record->nim,
            'nama' => $record->nama,
            'nilai_matkul' => $record->nilai_matkul,
            'cv' => $record->cv_path ? basename($record->cv_path) : 'CV1.pdf',
            'transkrip' => $record->transkrip_path ? basename($record->transkrip_path) : 'Transkip1.pdf',
            'catatan_dosen' => $isReviewed ? $record->catatan_dosen : null,
            'berkas_diverifikasi' => $isVerified,
            'status_berkas' => $statusBerkas,
            'catatan_berkas' => $record->catatan_berkas,
            'jadwal_wawancara' => $record->jadwal_wawancara,
            'lokasi_wawancara' => $record->lokasi_wawancara,
            'catatan_wawancara' => $record->catatan_wawancara,
        ];

        $langkah = [
            [
                'judul' => 'Terdaftar',
                'deskripsi' => 'Formulir dan dokumen berhasil dikirim.',
                'waktu' => $pendaftaran['didaftarkan_pada'],
                'selesai' => true,
                'status_langkah' => 'selesai',
            ],
            [
                'judul' => 'Verifikasi berkas',
                'deskripsi' => $isVerified
                    ? 'Dosen telah meninjau berkas pendaftaran.'
                    : ($isRejectedBerkas ? 'Berkas ditolak: '.($record->catatan_berkas ?: 'Dokumen tidak memenuhi persyaratan.') : 'Dosen belum meninjau berkas pendaftaran.'),
                'waktu' => $isVerified ? ($record->waktu_verifikasi_berkas?->translatedFormat('d M Y, H:i') ?? 'Selesai') : ($isRejectedBerkas ? 'Ditolak' : 'Belum'),
                'selesai' => $isVerified,
                'status_langkah' => $isVerified ? 'selesai' : ($isRejectedBerkas ? 'ditolak' : 'menunggu'),
            ],
            [
                'judul' => 'Seleksi / Interview',
                'deskripsi' => $hasInterview
                    ? 'Wawancara dijadwalkan: '.$record->jadwal_wawancara.' di '.$record->lokasi_wawancara
                    : ($status === 'disetujui'
                        ? 'Pendaftaran disetujui.'
                        : ($status === 'ditolak' ? 'Pendaftaran tidak dilanjutkan.' : 'Pendaftaran belum disetujui.')),
                'waktu' => $hasInterview ? 'Dijadwalkan' : ($isReviewed ? 'Selesai' : 'Belum'),
                'selesai' => (bool) ($hasInterview || $isReviewed),
                'status_langkah' => ($hasInterview || $isReviewed) ? 'selesai' : 'menunggu',
            ],
            [
                'judul' => 'Hasil akhir',
                'deskripsi' => $isReviewed ? $statusText : 'Pendaftaran belum disetujui',
                'waktu' => $isReviewed ? $record->updated_at?->translatedFormat('d M Y, H:i') : 'Belum',
                'selesai' => $isReviewed,
                'status_langkah' => $isReviewed ? ($status === 'disetujui' ? 'selesai' : 'ditolak') : 'menunggu',
            ],
        ];

        return view('mahasiswa.detail-status', compact('pendaftaran', 'langkah'));
    }
}
