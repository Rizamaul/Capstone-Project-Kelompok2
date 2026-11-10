<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use App\Models\Timesheet;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $lowonganTersedia = Pengumuman::query()
            ->with('dosen')
            ->withCount([
                'pendaftarans as terisi' => fn ($query) => $query
                    ->where('status_seleksi', 'disetujui')
                    ->where('is_published', true),
            ])
            ->where('status_aktif', true)
            ->where('tenggat', '>', now())
            ->latest()
            ->get()
            ->filter(fn (Pengumuman $lowongan): bool => $lowongan->terisi < $lowongan->kuota)
            ->values();

        $lowongans = $lowonganTersedia
            ->take(4)
            ->map(fn (Pengumuman $lowongan): array => [
                'id' => $lowongan->id,
                'kode' => $lowongan->kode ?? 'MK',
                'nama' => $lowongan->mata_kuliah,
                'dosen' => $lowongan->dosen?->name ?? 'Dosen pengampu',
                'sks' => $lowongan->sks,
                'kuota' => $lowongan->kuota,
                'terisi' => $lowongan->terisi,
                'tenggat' => $lowongan->tenggat?->translatedFormat('d M Y'),
                'jadwal' => $lowongan->jadwal ?? 'Belum diatur',
            ])
            ->values()
            ->all();

        $userId = auth()->id() ?? 1;
        $pendaftaranQuery = Pendaftaran::query();

        if (auth()->check()) {
            $pendaftaranQuery->where('user_id', $userId);
        } else {
            $pendaftaranQuery->whereKey(session('mahasiswa.pendaftaran_id', 0));
        }

        $statistik = [
            'pengumuman_aktif' => $lowonganTersedia->count(),
            'pendaftaran_saya' => $pendaftaranQuery->count(),
            'jam_disetujui' => (float) Timesheet::query()
                ->where('asisten_id', $userId)
                ->where('status_approval', 'approved')
                ->sum('jumlah_jam'),
            'menunggu_review' => Timesheet::query()
                ->where('asisten_id', $userId)
                ->where('status_approval', 'pending')
                ->count(),
        ];

        return view('mahasiswa.dashboard', compact('lowongans', 'statistik'));
    }
}
