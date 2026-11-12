<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TimesheetController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        $query = Timesheet::query()->with('asisten')->latest('tanggal');

        if (! empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($query) use ($search): void {
                $query->where('mata_kuliah', 'like', "%{$search}%")
                    ->orWhere('aktivitas', 'like', "%{$search}%")
                    ->orWhere('uraian_pekerjaan', 'like', "%{$search}%")
                    ->orWhereHas('asisten', fn ($asistenQuery) => $asistenQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status_approval', $filters['status']);
        }

        $entries = $query->paginate(15)->withQueryString();
        $totalJamDisetujui = (float) Timesheet::query()->where('status_approval', 'approved')->sum('jumlah_jam');

        $statistik = [
            'disetujui' => Timesheet::query()->where('status_approval', 'approved')->count(),
            'menunggu' => Timesheet::query()->where('status_approval', 'pending')->count(),
            'total_jam' => $totalJamDisetujui,
            'total' => Timesheet::query()->count(),
            'estimasi_honor' => $totalJamDisetujui * 35000,
        ];

        return view('dosen.timesheet.index', compact('entries', 'statistik', 'filters'));
    }

    public function show(int $id): View
    {
        $entry = Timesheet::query()->with('asisten')->findOrFail($id);

        return view('dosen.timesheet.detail', ['entry' => $entry, 'id' => $id]);
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
}
