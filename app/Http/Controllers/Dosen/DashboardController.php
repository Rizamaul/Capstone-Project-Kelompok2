<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use App\Models\Timesheet;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $lowongans = Pengumuman::query()
            ->withCount([
                'pendaftarans',
                'pendaftarans as terisi' => fn ($query) => $query
                    ->where('status_seleksi', 'disetujui')
                    ->where('is_published', true),
                'pendaftarans as review_count' => fn ($query) => $query
                    ->where('status_seleksi', 'menunggu')
                    ->where('is_published', false),
            ])
            ->latest()
            ->take(5)
            ->get();

        $statistik = [
            'pengumuman_aktif' => Pengumuman::query()
                ->where('status_aktif', true)
                ->where('tenggat', '>', now())
                ->count(),
            'total_pendaftar' => Pendaftaran::query()->count(),
            'kuota_terisi' => Pendaftaran::query()
                ->where('status_seleksi', 'disetujui')
                ->where('is_published', true)
                ->count(),
            'kuota_total' => (int) Pengumuman::query()
                ->where('status_aktif', true)
                ->sum('kuota'),
            'review_berkas' => Pendaftaran::query()
                ->where('status_seleksi', 'menunggu')
                ->where('is_published', false)
                ->count(),
            'timesheet_menunggu' => Timesheet::query()
                ->where('status_approval', 'pending')
                ->count(),
        ];

        return view('dosen.dashboard', compact('lowongans', 'statistik'));
    }
}
