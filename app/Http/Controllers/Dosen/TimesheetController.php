<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Timesheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TimesheetController extends Controller
{
    public function index(Request $request): View
    {
        [$query, $filters] = $this->filteredQuery($request);

        $entries = $query->paginate(15)->withQueryString();
        $statistik = $this->statistics();

        $matakuliahOptions = Timesheet::query()
            ->whereNotNull('mata_kuliah')
            ->distinct()
            ->orderBy('mata_kuliah')
            ->pluck('mata_kuliah')
            ->all();

        $courseMetaMap = $this->courseMetaMap();

        return view('dosen.timesheet.index', compact('entries', 'statistik', 'filters', 'matakuliahOptions', 'courseMetaMap'));
    }

    public function exportPdf(Request $request): Response
    {
        [$query, $filters] = $this->filteredQuery($request);

        $entries = $query->get();
        $totalJamDisetujui = (float) $entries
            ->where('status_approval', 'approved')
            ->sum('jumlah_jam');

        $statistik = [
            'total' => $entries->count(),
            'disetujui' => $entries->where('status_approval', 'approved')->count(),
            'menunggu' => $entries->where('status_approval', 'pending')->count(),
            'ditolak' => $entries->where('status_approval', 'rejected')->count(),
            'total_jam' => $totalJamDisetujui,
            'estimasi_honor' => $totalJamDisetujui * 35000,
        ];

        $generatedAt = now()->translatedFormat('d M Y, H:i').' WIB';

        $pdf = Pdf::loadView('dosen.timesheet.pdf', compact('entries', 'statistik', 'filters', 'generatedAt'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-timesheet-asisten-'.now()->format('Ymd-His').'.pdf');
    }

    public function show(int $id): View
    {
        $entry = Timesheet::query()->with('asisten')->findOrFail($id);
        $courseMetaMap = $this->courseMetaMap();
        $meta = $courseMetaMap[$entry->mata_kuliah ?? ''] ?? null;
        $parts = explode(' · ', (string) ($entry->mata_kuliah ?? ''), 2);
        $code = $meta['kode'] ?? (count($parts) === 2 ? $parts[0] : '—');
        $courseName = count($parts) === 2 ? $parts[1] : ($entry->mata_kuliah ?? 'Asistensi Praktikum');
        $kelas = $meta['kelas'] ?? null;
        $sks = $meta['sks'] ?? 3;

        return view('dosen.timesheet.detail', [
            'entry' => $entry,
            'id' => $id,
            'code' => $code,
            'courseName' => $courseName,
            'kelas' => $kelas,
            'sks' => $sks,
        ]);
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'catatan_revisi' => ['nullable', 'required_if:status,rejected', 'string', 'max:2000'],
        ]);

        $entry = Timesheet::findOrFail($id);
        abort_if($entry->status_approval !== 'pending', 409, 'Timesheet ini sudah ditinjau.');

        $entry->update([
            'status_approval' => $validated['status'],
            'catatan_revisi' => $validated['catatan_revisi'] ?? null,
        ]);

        return redirect()->route('dosen.timesheet.show', $entry)
            ->with('success', 'Keputusan timesheet berhasil disimpan.');
    }

    public function approvePending(): RedirectResponse
    {
        $updated = Timesheet::query()
            ->where('status_approval', 'pending')
            ->update([
                'status_approval' => 'approved',
                'catatan_revisi' => null,
                'updated_at' => now(),
            ]);

        return back()->with('success', "{$updated} timesheet menunggu berhasil disetujui.");
    }

    /**
     * Build the filtered timesheet query shared by listing and PDF export.
     *
     * @return array{0: Builder<Timesheet>, 1: array<string, mixed>}
     */
    private function filteredQuery(Request $request): array
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
            'matakuliah' => ['nullable', 'string', 'max:150'],
            'semester' => ['nullable', 'string', 'max:60'],
        ]);

        $query = Timesheet::query()->with('asisten')->latest('tanggal');

        if (! empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('mata_kuliah', 'like', "%{$search}%")
                    ->orWhere('aktivitas', 'like', "%{$search}%")
                    ->orWhere('uraian_pekerjaan', 'like', "%{$search}%")
                    ->orWhereHas('asisten', fn ($asistenQuery) => $asistenQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%"));
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status_approval', $filters['status']);
        }

        if (! empty($filters['matakuliah'])) {
            $query->where('mata_kuliah', 'like', "%{$filters['matakuliah']}%");
        }

        return [$query, $filters];
    }

    /**
     * @return array<string, float|int>
     */
    private function statistics(): array
    {
        $totalJamDisetujui = (float) Timesheet::query()->where('status_approval', 'approved')->sum('jumlah_jam');
        $jamMingguan = (float) Timesheet::query()
            ->where('status_approval', 'approved')
            ->whereBetween('tanggal', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])
            ->sum('jumlah_jam');
        $jamBulanan = (float) Timesheet::query()
            ->where('status_approval', 'approved')
            ->whereBetween('tanggal', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('jumlah_jam');

        return [
            'disetujui' => Timesheet::query()->where('status_approval', 'approved')->count(),
            'menunggu' => Timesheet::query()->where('status_approval', 'pending')->count(),
            'total_jam' => $totalJamDisetujui,
            'jam_mingguan' => $jamMingguan,
            'jam_bulanan' => $jamBulanan,
            'total' => Timesheet::query()->count(),
            'estimasi_honor' => $totalJamDisetujui * 35000,
        ];
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
