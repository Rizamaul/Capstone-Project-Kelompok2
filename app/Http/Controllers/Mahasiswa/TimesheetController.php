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
        $userId = auth()->id() ?? 1;

        $entries = Timesheet::query()
            ->where('asisten_id', $userId)
            ->latest('tanggal')
            ->get()
            ->map(function (Timesheet $entry): array {
                $rawCourse = $entry->mata_kuliah ?? 'Algoritma & Struktur Data';
                $parts = explode(' · ', $rawCourse, 2);
                $code = count($parts) === 2 ? $parts[0] : 'IF101';
                $courseName = count($parts) === 2 ? $parts[1] : $rawCourse;

                return [
                    'id' => $entry->id,
                    'course' => $courseName,
                    'raw_course' => $rawCourse,
                    'code' => $code,
                    'class' => '2402',
                    'sks' => 3,
                    'week' => 'Minggu '.$entry->tanggal->weekOfYear,
                    'title' => $entry->aktivitas ?: 'Mendampingi Praktikum',
                    'activity' => $entry->uraian_pekerjaan ?: 'Mendampingi sesi lab dan membantu mahasiswa.',
                    'date' => $entry->tanggal->translatedFormat('d M, Y'),
                    'time_range' => '08.00 - 10.00 WIB',
                    'hours' => (float) $entry->jumlah_jam,
                    'status' => match ($entry->status_approval) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                    },
                    'catatan_revisi' => $entry->catatan_revisi ?: 'Hasil pemeriksaan laporan kurang lengkap dan format rangkuman pertanyaan belum sesuai.',
                ];
            })
            ->all();

        $totalJamDisetujui = (float) Timesheet::query()
            ->where('asisten_id', $userId)
            ->where('status_approval', 'approved')
            ->sum('jumlah_jam');

        $pendingCount = Timesheet::query()
            ->where('asisten_id', $userId)
            ->where('status_approval', 'pending')
            ->count();

        $matakuliahAktif = $this->activeCourses();

        return view('mahasiswa.timesheet', compact('entries', 'totalJamDisetujui', 'pendingCount', 'matakuliahAktif'));
    }

    public function show(Timesheet $timesheet): View
    {
        $rawCourse = $timesheet->mata_kuliah ?? 'Algoritma & Struktur Data';
        $parts = explode(' · ', $rawCourse, 2);
        $code = count($parts) === 2 ? $parts[0] : 'IF101';
        $courseName = count($parts) === 2 ? $parts[1] : $rawCourse;

        return view('mahasiswa.timesheet-detail', [
            'entry' => $timesheet,
            'courseName' => $courseName,
            'code' => $code,
        ]);
    }

    public function edit(Timesheet $timesheet): View
    {
        $rawCourse = $timesheet->mata_kuliah ?? 'Algoritma & Struktur Data';
        $parts = explode(' · ', $rawCourse, 2);
        $code = count($parts) === 2 ? $parts[0] : 'IF101';
        $courseName = count($parts) === 2 ? $parts[1] : $rawCourse;
        $matakuliahAktif = $this->activeCourses();

        return view('mahasiswa.timesheet-edit', [
            'entry' => $timesheet,
            'courseName' => $courseName,
            'code' => $code,
            'matakuliahAktif' => $matakuliahAktif,
        ]);
    }

    public function update(Request $request, Timesheet $timesheet): RedirectResponse
    {
        $matakuliahAktif = $this->activeCourses();

        $validated = $request->validate([
            'matakuliah' => ['sometimes', 'required', 'string'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'jam' => ['required', 'numeric', 'min:0.5', 'max:40', 'multiple_of:0.5'],
            'aktivitas' => ['required', 'string', 'max:120'],
            'deskripsi' => ['required', 'string', 'max:1000'],
        ]);

        $timesheet->update([
            'tanggal' => $validated['tanggal'],
            'jumlah_jam' => (float) $validated['jam'],
            'mata_kuliah' => $validated['matakuliah'] ?? $timesheet->mata_kuliah,
            'aktivitas' => $validated['aktivitas'],
            'uraian_pekerjaan' => $validated['deskripsi'],
            'status_approval' => 'pending',
        ]);

        return redirect()->route('mahasiswa.timesheet.show', $timesheet->id)
            ->with('success', 'Timesheet berhasil diperbarui dan diajukan kembali untuk review.');
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
