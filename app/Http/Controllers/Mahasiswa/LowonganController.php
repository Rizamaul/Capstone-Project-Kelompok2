<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class LowonganController extends Controller
{
    public function index(): View
    {
        $lowongans = $this->queryLowongans()
            ->get()
            ->map(fn (Pengumuman $lowongan): array => $this->present($lowongan))
            ->all();

        return view('mahasiswa.lowongan', compact('lowongans'));
    }

    public function show(int $id): View
    {
        $lowongan = $this->queryLowongans()->findOrFail($id);

        return view('mahasiswa.detail-lowongan', [
            'lowongan' => $this->present($lowongan),
        ]);
    }

    /**
     * @return Builder<Pengumuman>
     */
    private function queryLowongans(): Builder
    {
        return Pengumuman::query()
            ->with('dosen')
            ->withCount([
                'pendaftarans as terisi' => fn ($query) => $query
                    ->where('status_seleksi', 'disetujui')
                    ->where('is_published', true),
            ])
            ->latest();
    }

    /**
     * @return array<string, mixed>
     */
    private function present(Pengumuman $lowongan): array
    {
        $isOpen = $lowongan->status_aktif
            && $lowongan->tenggat?->isFuture()
            && $lowongan->terisi < $lowongan->kuota;

        return [
            'id' => $lowongan->id,
            'kode' => $lowongan->kode ?? 'MK',
            'nama' => $lowongan->mata_kuliah,
            'dosen' => $lowongan->dosen?->name ?? 'Dosen pengampu',
            'sks' => $lowongan->sks,
            'kuota' => $lowongan->kuota,
            'terisi' => $lowongan->terisi,
            'tenggat' => $lowongan->tenggat?->translatedFormat('d M Y'),
            'tenggat_iso' => $lowongan->tenggat?->toDateString(),
            'jadwal' => $lowongan->jadwal ?? 'Belum diatur',
            'kelas' => $lowongan->kelas,
            'semester' => $lowongan->semester,
            'status' => $isOpen ? 'Dibuka' : 'Ditutup',
            'deskripsi' => $lowongan->deskripsi ?? $lowongan->syarat,
            'syarat' => $lowongan->syarat_nilai,
            'syarat_nilai' => $lowongan->syarat_nilai,
            'syarat_ipk' => $lowongan->syarat_ipk,
            'persyaratan' => $lowongan->syarat,
            'berkas_diminta' => $this->documents($lowongan),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function documents(Pengumuman $lowongan): array
    {
        $documents = json_decode((string) $lowongan->berkas_diminta, true);

        return is_array($documents) ? $documents : ['CV', 'Transkrip nilai'];
    }
}
