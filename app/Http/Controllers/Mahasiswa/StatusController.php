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
            default => $record->hasil_wawancara === 'lulus'
                ? 'Menunggu keputusan akhir dosen'
                : 'Menunggu keputusan dosen',
        };
        $isReviewed = $record->is_published;
        
        $statusBerkas = $record->status_berkas ?? ($record->berkas_diverifikasi ? 'diverifikasi' : 'menunggu');
        $isVerified = $statusBerkas === 'diverifikasi' || $record->berkas_diverifikasi;
        $isRejectedBerkas = ($statusBerkas === 'ditolak');
        $hasInterview = !empty($record->jadwal_wawancara);
        $interviewFailed = $record->hasil_wawancara === 'gagal';
        $finalDecisionRejected = $isReviewed && $status === 'ditolak' && ! $isRejectedBerkas && ! $interviewFailed;

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
            'metode_wawancara' => $record->metode_wawancara,
            'waktu_wawancara' => $record->waktu_wawancara,
            'lokasi_wawancara' => $record->lokasi_wawancara,
            'catatan_wawancara' => $record->catatan_wawancara,
            'hasil_wawancara' => $record->hasil_wawancara,
            'catatan_hasil_wawancara' => $record->catatan_hasil_wawancara,
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
                'status_langkah' => $isRejectedBerkas ? 'gagal' : ($isVerified ? 'selesai' : 'menunggu'),
            ],
            [
                'judul' => 'Seleksi / Interview',
                'deskripsi' => $isRejectedBerkas
                    ? 'Tidak dilanjutkan karena verifikasi berkas gagal.'
                    : ($interviewFailed
                        ? 'Wawancara tidak berhasil: '.($record->catatan_hasil_wawancara ?: 'Pendaftaran dihentikan.')
                        : ($record->hasil_wawancara === 'lulus'
                            ? 'Wawancara berhasil. Menunggu keputusan akhir dosen.'
                            : ($hasInterview
                                ? 'Jadwal wawancara tersedia pada notifikasi di halaman ini.'
                                : 'Wawancara belum dijadwalkan.'))),
                'waktu' => $isRejectedBerkas || $interviewFailed ? 'Tidak dilanjutkan' : ($record->hasil_wawancara !== null ? 'Selesai' : ($hasInterview ? 'Jadwal tersedia' : 'Belum')),
                'selesai' => $record->hasil_wawancara === 'lulus',
                'status_langkah' => $isRejectedBerkas
                    ? 'dilewati'
                    : ($interviewFailed
                        ? 'gagal'
                        : ($record->hasil_wawancara === 'lulus' ? 'selesai' : ($hasInterview ? 'notifikasi' : 'menunggu'))),
            ],
            [
                'judul' => 'Hasil akhir',
                'deskripsi' => $isRejectedBerkas
                    ? 'Tidak diproses karena berkas ditolak.'
                    : ($interviewFailed
                        ? 'Tidak diproses karena wawancara tidak berhasil.'
                        : ($isReviewed ? $statusText : 'Menunggu keputusan akhir dosen.')),
                'waktu' => $isRejectedBerkas || $interviewFailed
                    ? 'Tidak dilanjutkan'
                    : ($isReviewed ? $record->updated_at?->translatedFormat('d M Y, H:i') : 'Belum'),
                'selesai' => $isReviewed,
                'status_langkah' => $isRejectedBerkas || $interviewFailed
                    ? 'dilewati'
                    : ($isReviewed
                        ? ($status === 'disetujui' ? 'selesai' : ($finalDecisionRejected ? 'gagal' : 'dilewati'))
                        : 'menunggu'),
            ],
        ];

        return view('mahasiswa.detail-status', compact('pendaftaran', 'langkah'));
    }
}
