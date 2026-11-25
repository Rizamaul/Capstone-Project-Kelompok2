<?php

namespace App\Providers;

use App\Models\Pendaftaran;
use App\Models\Timesheet;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.dosen', function ($view): void {
            $notifications = collect();

            $pendingApplicants = Pendaftaran::query()
                ->with('lowongan')
                ->where('status', 'menunggu')
                ->latest('updated_at')
                ->take(5)
                ->get();

            foreach ($pendingApplicants as $pendaftaran) {
                $matkul = $pendaftaran->lowongan?->mata_kuliah ?? 'Lowongan Asisten';

                if (! $pendaftaran->berkas_diverifikasi && $pendaftaran->status_berkas !== 'diverifikasi') {
                    $title = 'Pendaftar Baru Menunggu Verifikasi';
                    $message = "{$pendaftaran->nama} melamar pada {$matkul}.";
                    $type = 'warning';
                } elseif (! $pendaftaran->jadwal_wawancara) {
                    $title = 'Berkas Terverifikasi · Atur Wawancara';
                    $message = "Berkas {$pendaftaran->nama} ({$matkul}) siap dijadwalkan.";
                    $type = 'info';
                } else {
                    $title = 'Menunggu Hasil Seleksi';
                    $message = "{$pendaftaran->nama} ({$matkul}) telah dijadwalkan wawancara.";
                    $type = 'info';
                }

                $notifications->push([
                    'title' => $title,
                    'message' => $message,
                    'time' => $pendaftaran->updated_at?->diffForHumans() ?? 'Baru saja',
                    'timestamp' => $pendaftaran->updated_at?->timestamp ?? 0,
                    'url' => route('dosen.pendaftar.show', $pendaftaran),
                    'type' => $type,
                ]);
            }

            $pendingTimesheets = Timesheet::query()
                ->with('asisten')
                ->where('status_approval', 'pending')
                ->latest('updated_at')
                ->take(5)
                ->get();

            foreach ($pendingTimesheets as $ts) {
                $asistenName = $ts->asisten?->name ?? 'Asisten Praktikum';
                $jam = number_format((float) $ts->jumlah_jam, 1, '.', '');
                $mk = $ts->mata_kuliah ?? 'Asistensi';

                $notifications->push([
                    'title' => 'Timesheet Menunggu Review',
                    'message' => "{$asistenName} mengajukan {$jam} jam ({$mk}).",
                    'time' => $ts->updated_at?->diffForHumans() ?? 'Baru saja',
                    'timestamp' => $ts->updated_at?->timestamp ?? 0,
                    'url' => route('dosen.timesheet.show', $ts->id),
                    'type' => 'warning',
                ]);
            }

            $dosenNotifications = $notifications
                ->sortByDesc('timestamp')
                ->take(8)
                ->values()
                ->all();

            $view->with('dosenNotifications', $dosenNotifications);
        });

        View::composer('layouts.mahasiswa', function ($view): void {
            $notifications = collect();

            $pendaftaranQuery = Pendaftaran::query()->with('lowongan');
            if (auth()->check()) {
                $pendaftaranQuery->where('user_id', auth()->id());
            } elseif (session('mahasiswa.pendaftaran_id')) {
                $pendaftaranQuery->where('id', session('mahasiswa.pendaftaran_id'));
            }

            $pendaftarans = $pendaftaranQuery
                ->latest('updated_at')
                ->take(5)
                ->get();

            foreach ($pendaftarans as $pendaftaran) {
                $matkul = $pendaftaran->lowongan?->mata_kuliah ?? 'Asistensi Praktikum';

                if ($pendaftaran->status === 'disetujui') {
                    $title = 'Pendaftaran Disetujui';
                    $message = "Selamat! Lamaran Anda untuk {$matkul} telah diterima.";
                    $type = 'success';
                } elseif ($pendaftaran->status === 'ditolak' || $pendaftaran->status_berkas === 'ditolak') {
                    $title = $pendaftaran->status_berkas === 'ditolak' ? 'Berkas Pendaftaran Ditolak' : 'Pendaftaran Ditolak';
                    $message = "Lamaran Anda untuk {$matkul} perlu dicek pada detail status.";
                    $type = 'danger';
                } elseif ($pendaftaran->jadwal_wawancara) {
                    $title = 'Jadwal Wawancara Tersedia';
                    $message = "Wawancara {$matkul}: {$pendaftaran->jadwal_wawancara}.";
                    $type = 'info';
                } elseif ($pendaftaran->berkas_diverifikasi || $pendaftaran->status_berkas === 'diverifikasi') {
                    $title = 'Berkas Terverifikasi';
                    $message = "Berkas pendaftaran {$matkul} telah diverifikasi dosen.";
                    $type = 'success';
                } else {
                    $title = 'Pendaftaran Terkirim';
                    $message = "Lamaran {$matkul} sedang menunggu peninjauan dosen.";
                    $type = 'warning';
                }

                $notifications->push([
                    'title' => $title,
                    'message' => $message,
                    'time' => $pendaftaran->updated_at?->diffForHumans() ?? 'Baru saja',
                    'timestamp' => $pendaftaran->updated_at?->timestamp ?? 0,
                    'url' => route('mahasiswa.status.show', $pendaftaran->id),
                    'type' => $type,
                ]);
            }

            $reviewedTimesheets = Timesheet::query()
                ->where('asisten_id', auth()->id() ?? 1)
                ->whereIn('status_approval', ['approved', 'rejected'])
                ->latest('updated_at')
                ->take(5)
                ->get();

            foreach ($reviewedTimesheets as $ts) {
                $jam = number_format((float) $ts->jumlah_jam, 1, '.', '');
                $mk = $ts->mata_kuliah ?? 'Asistensi';
                $isApproved = $ts->status_approval === 'approved';

                $notifications->push([
                    'title' => $isApproved ? 'Timesheet Disetujui' : 'Timesheet Ditolak',
                    'message' => $isApproved
                        ? "Timesheet {$jam} jam ({$mk}) telah disetujui dosen."
                        : "Timesheet {$jam} jam ({$mk}) ditolak. Cek catatan revisi.",
                    'time' => $ts->updated_at?->diffForHumans() ?? 'Baru saja',
                    'timestamp' => $ts->updated_at?->timestamp ?? 0,
                    'url' => route('mahasiswa.timesheet.show', $ts->id),
                    'type' => $isApproved ? 'success' : 'danger',
                ]);
            }

            $mahasiswaNotifications = $notifications
                ->sortByDesc('timestamp')
                ->take(8)
                ->values()
                ->all();

            $view->with('mahasiswaNotifications', $mahasiswaNotifications);
        });
    }
}
