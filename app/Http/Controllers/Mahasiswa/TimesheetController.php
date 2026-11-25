<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
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
        $courseMetaMap = $this->courseMetaMap();

        $entries = Timesheet::query()
            ->where('asisten_id', $userId)
            ->latest('tanggal')
            ->get()
            ->map(function (Timesheet $entry) use ($courseMetaMap): array {
                $rawCourse = $entry->mata_kuliah ?? 'Algoritma & Struktur Data';
                $parts = explode(' · ', $rawCourse, 2);
                $meta = $courseMetaMap[$rawCourse] ?? null;
                $code = $meta['kode'] ?? (count($parts) === 2 ? $parts[0] : '—');
                $courseName = count($parts) === 2 ? $parts[1] : $rawCourse;

                return [
                    'id' => $entry->id,
                    'course' => $courseName,
                    'raw_course' => $rawCourse,
                    'code' => $code,
                    'class' => $meta['kelas'] ?? null,
                    'sks' => $meta['sks'] ?? 3,
                    'week' => 'Minggu '.$entry->tanggal->weekOfYear,
                    'title' => $entry->aktivitas ?: 'Mendampingi Praktikum',
                    'activity' => $entry->uraian_pekerjaan ?: 'Mendampingi sesi lab dan membantu mahasiswa.',
                    'date' => $entry->tanggal->translatedFormat('d M, Y'),
                    'time_range' => $meta['jadwal'] ?? '08.00 - 11.00 WIB',
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
        $isAsistenAktif = count($matakuliahAktif) > 0;

        return view('mahasiswa.timesheet', compact('entries', 'totalJamDisetujui', 'pendingCount', 'matakuliahAktif', 'isAsistenAktif'));
    }

    public function show(Timesheet $timesheet): View
    {
        $rawCourse = $timesheet->mata_kuliah ?? 'Algoritma & Struktur Data';
        $parts = explode(' · ', $rawCourse, 2);
        $meta = $this->courseMetaMap()[$rawCourse] ?? null;
        $code = $meta['kode'] ?? (count($parts) === 2 ? $parts[0] : '—');
        $courseName = count($parts) === 2 ? $parts[1] : $rawCourse;

        return view('mahasiswa.timesheet-detail', [
            'entry' => $timesheet,
            'courseName' => $courseName,
            'code' => $code,
            'kelas' => $meta['kelas'] ?? null,
            'sks' => $meta['sks'] ?? 3,
        ]);
    }

    public function edit(Timesheet $timesheet): View
    {
        abort_if($timesheet->status_approval === 'approved', 403, 'Timesheet yang sudah disetujui tidak dapat diubah lagi.');

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
        abort_if($timesheet->status_approval === 'approved', 403, 'Timesheet yang sudah disetujui tidak dapat diubah lagi.');

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

        if ($matakuliahAktif === []) {
            return back()
                ->withInput()
                ->withErrors([
                    'matakuliah' => 'Pengisian timesheet hanya tersedia untuk mahasiswa yang sudah berstatus asisten praktikum (pendaftaran disetujui).',
                ]);
        }

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
        $query = Pendaftaran::query()
            ->with('lowongan')
            ->where('status', 'disetujui');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } elseif (session('mahasiswa.pendaftaran_id')) {
            $query->where('id', session('mahasiswa.pendaftaran_id'));
        }

        return $query
            ->get()
            ->map(fn (Pendaftaran $pendaftaran): ?Pengumuman => $pendaftaran->lowongan)
            ->filter()
            ->unique('id')
            ->sortBy('mata_kuliah')
            ->map(fn (Pengumuman $lowongan): string => trim(($lowongan->kode ? $lowongan->kode.' · ' : '').$lowongan->mata_kuliah))
            ->values()
            ->all();
    }

    /**
     * @return array<string, array{kode: string, kelas: ?string, sks: int, jadwal: ?string}>
     */
    private function courseMetaMap(): array
    {
        $map = [];

        foreach (Pengumuman::query()->latest()->get() as $lowongan) {
            $key = trim(($lowongan->kode ? $lowongan->kode.' · ' : '').$lowongan->mata_kuliah);
            $meta = [
                'kode' => $lowongan->kode ?? '—',
                'kelas' => $lowongan->kelas ?: null,
                'sks' => (int) ($lowongan->sks ?? 3),
                'jadwal' => $lowongan->jadwal ?: null,
            ];
            $map[$key] = $meta;
            $map[$lowongan->mata_kuliah] = $map[$lowongan->mata_kuliah] ?? $meta;
        }

        return $map;
    }
}
