<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    /**
     * Dummy in-memory data untuk list pendaftar di sisi dosen.
     * Nantinya diganti dengan query ke database/model.
     */
    private static array $pendaftar = [
        [
            'id' => 1,
            'lowongan_id' => 1,
            'nama' => 'Aisyah Putri',
            'nim' => '202310101',
            'nilai_matkul' => 'A',
            'ipk' => 3.8,
            'motivasi' => 'Saya siap membantu praktikum dan memahami materi dengan baik.',
            'portfolio' => 'https://example.com/portfolio/aisyah',
            'cv_path' => 'pendaftaran/2026/10/aisyah-putri-cv.pdf',
            'transkrip_path' => 'pendaftaran/2026/10/aisyah-putri-transkrip.pdf',
            'status' => 'menunggu',
            'created_at' => '2026-10-28T00:00:00Z',
        ],
        [
            'id' => 2,
            'lowongan_id' => 2,
            'nama' => 'Rafi Andika',
            'nim' => '202310102',
            'nilai_matkul' => 'AB',
            'ipk' => 3.7,
            'motivasi' => 'Saya memiliki pengalaman belajar struktur data dan siap mendampingi mahasiswa.',
            'portfolio' => null,
            'cv_path' => 'pendaftaran/2026/10/rafi-andika-cv.pdf',
            'transkrip_path' => 'pendaftaran/2026/10/rafi-andika-transkrip.pdf',
            'status' => 'disetujui',
            'created_at' => '2026-10-27T00:00:00Z',
        ],
    ];

    public static function getStoredPendaftar(): array
    {
        return self::$pendaftar;
    }

    public static function storePendaftarRecord(array $data): array
    {
        $record = array_merge([
            'id' => count(self::$pendaftar) + 1,
            'created_at' => now()->toISOString(),
        ], $data);

        self::$pendaftar[] = $record;

        return $record;
    }

    /**
     * API list pendaftar untuk sisi dosen.
     */
    public function index()
    {
        return response()->json(self::getStoredPendaftar());
    }

    /**
     * Data dummy lowongan — sama dengan LowonganController.
     * Nantunya diganti dengan query ke Model/Database.
     */
    private function getLowongan(int $id): array
    {
        $data = [
            1 => [
                'id' => 1,
                'kode' => 'IF101',
                'nama' => 'Pemrograman Dasar',
                'dosen' => 'Dr. Budi Santoso',
                'sks' => 3,
                'kuota' => 5,
                'terisi' => 2,
                'tenggat' => '30 Nov 2025',
                'jadwal' => 'Senin 08.00',
                'status' => 'Dibuka',
            ],
            2 => [
                'id' => 2,
                'kode' => 'IF205',
                'nama' => 'Struktur Data',
                'dosen' => 'Dr. Siti Rahayu',
                'sks' => 3,
                'kuota' => 4,
                'terisi' => 1,
                'tenggat' => '28 Nov 2025',
                'jadwal' => 'Rabu 10.00',
                'status' => 'Dibuka',
            ],
        ];

        abort_if(! isset($data[$id]), 404, 'Lowongan tidak ditemukan.');

        return $data[$id];
    }

    /**
     * Tampilkan form pendaftaran untuk lowongan tertentu.
     */
    public function create(int $id)
    {
        $lowongan = $this->getLowongan($id);

        return view('mahasiswa.pendaftaran', compact('lowongan'));
    }

    /**
     * Simpan data pendaftaran yang dikirimkan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lowongan_id' => ['required', 'integer'],
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20'],
            'nilai_matkul' => ['required', 'string'],
            'ipk' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'motivasi' => ['required', 'string', 'min:50', 'max:1500'],
            'portfolio' => ['nullable', 'url', 'max:255'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'transkrip' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'persetujuan' => ['required', 'accepted'],
        ]);

        $directory = 'pendaftaran/'.date('Y').'/'.date('m');

        $cvPath = $request->file('cv')->storeAs(
            $directory,
            Str::slug(pathinfo($validated['nama'], PATHINFO_FILENAME), '-').'-cv-'.Str::uuid().'.pdf',
            'public'
        );

        $transkripPath = $request->file('transkrip')->storeAs(
            $directory,
            Str::slug(pathinfo($validated['nama'], PATHINFO_FILENAME), '-').'-transkrip-'.Str::uuid().'.pdf',
            'public'
        );

        session()->flash('berkas_tersimpan', [
            'cv' => $cvPath,
            'transkrip' => $transkripPath,
            'cv_url' => Storage::disk('public')->url($cvPath),
            'transkrip_url' => Storage::disk('public')->url($transkripPath),
        ]);

        self::storePendaftarRecord([
            'lowongan_id' => $validated['lowongan_id'],
            'nama' => $validated['nama'],
            'nim' => $validated['nim'],
            'nilai_matkul' => $validated['nilai_matkul'],
            'ipk' => $validated['ipk'] ?? null,
            'motivasi' => $validated['motivasi'],
            'portfolio' => $validated['portfolio'] ?? null,
            'cv_path' => $cvPath,
            'transkrip_path' => $transkripPath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('mahasiswa.status')
            ->with('success', 'Pendaftaran berhasil dikirim. Berkas sudah disimpan di storage publik dan pantau status di halaman ini.');
    }
}