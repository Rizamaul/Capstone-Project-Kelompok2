<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Timesheet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TimesheetController extends Controller
{
    public function index(): View
    {
        $entries = Timesheet::query()
            ->where('asisten_id', auth()->id() ?? 1)
            ->latest('tanggal')
            ->get()
            ->map(function (Timesheet $entry): array {
                return [
                    'course' => $entry->mata_kuliah ?? 'Asistensi Praktikum',
                    'week' => 'Minggu '.$entry->tanggal->weekOfYear,
                    'activity' => trim(($entry->aktivitas ? $entry->aktivitas.': ' : '').$entry->uraian_pekerjaan),
                    'date' => $entry->tanggal->translatedFormat('d M Y'),
                    'hours' => (float) $entry->jumlah_jam,
                    'status' => match ($entry->status_approval) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                    },
                ];
            })
            ->all();

        $totalJamDisetujui = Timesheet::query()
            ->where('asisten_id', auth()->id() ?? 1)
            ->where('status_approval', 'approved')
            ->sum('jumlah_jam');

        $matakuliahAktif = $this->activeCourses();

        return view('mahasiswa.timesheet', compact('entries', 'totalJamDisetujui', 'matakuliahAktif'));
    }

    public function store(Request $request): RedirectResponse
    {
        $matakuliahAktif = $this->activeCourses();

        $validated = $request->validate([
            'matakuliah' => ['required', 'string', Rule::in($matakuliahAktif)],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'jam' => ['required', 'numeric', 'min:0.5', 'max:40', 'multiple_of:0.5'],
            'aktivitas' => ['required', 'string', 'max:120'],
            'deskripsi' => ['required', 'string', 'max:1000'],
        ]);

        Timesheet::create([
            'asisten_id' => auth()->id() ?? 1,
            'tanggal' => $validated['tanggal'],
            'jumlah_jam' => (float) $validated['jam'],
            'mata_kuliah' => $validated['matakuliah'],
            'aktivitas' => $validated['aktivitas'],
            'uraian_pekerjaan' => $validated['deskripsi'],
            'status_approval' => 'pending',
        ]);

        return redirect()->route('mahasiswa.timesheet')
            ->with('success', 'Timesheet berhasil diajukan dan sedang menunggu review dosen.');
    }

    /**
     * @return array<int, string>
     */
    private function activeCourses(): array
    {
        return Pengumuman::query()
            ->where('status_aktif', true)
            ->where('tenggat', '>', now())
            ->orderBy('mata_kuliah')
            ->get()
            ->map(fn (Pengumuman $lowongan): string => trim(($lowongan->kode ? $lowongan->kode.' · ' : '').$lowongan->mata_kuliah))
            ->all();
    }
}
