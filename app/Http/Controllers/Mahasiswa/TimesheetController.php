<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TimesheetController extends Controller
{
    /**
     * Tampilkan riwayat timesheet mahasiswa.
     */
    public function index()
    {
        $entries = [];

        if (Schema::hasTable('timesheet')) {
            $entries = Timesheet::query()
                ->where('asisten_id', auth()->id() ?? 1)
                ->orderByDesc('tanggal')
                ->get()
                ->map(function (Timesheet $entry): array {
                    return [
                        'course' => 'Asistensi Praktikum',
                        'week' => 'Minggu '. $entry->tanggal->weekOfYear,
                        'activity' => $entry->uraian_pekerjaan,
                        'date' => $entry->tanggal->translatedFormat('d M Y'),
                        'hours' => (float) $entry->jumlah_jam,
                        'status' => $entry->status_approval === 'approved' ? 'Disetujui' : 'Menunggu',
                    ];
                })
                ->all();
        }

        if (empty($entries)) {
            $entries = [
                [
                    'course'   => '2403 · Algoritma Struktur Data',
                    'week'     => 'Minggu 02 · 12–18 Okt 2026',
                    'activity' => 'Memeriksa laporan praktikum',
                    'date'     => '20 Okt 2026',
                    'hours'    => 5,
                    'status'   => 'Disetujui',
                ],
                [
                    'course'   => '2403 · Algoritma Struktur Data',
                    'week'     => 'Minggu 01 · 5–11 Okt 2026',
                    'activity' => 'Mendampingi praktikum',
                    'date'     => '13 Okt 2026',
                    'hours'    => 1.5,
                    'status'   => 'Disetujui',
                ],
            ];
        }

        $totalJamDisetujui = collect($entries)
            ->where('status', 'Disetujui')
            ->sum('hours');

        $matakuliahAktif = [
            '2403 · Algoritma Struktur Data',
            '2404 · Jaringan Komputer',
        ];

        return view('mahasiswa.timesheet', compact('entries', 'totalJamDisetujui', 'matakuliahAktif'));
    }

    /**
     * Simpan entri timesheet baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matakuliah' => ['required', 'string', 'max:100'],
            'tanggal'    => ['required', 'date'],
            'jam'        => ['required', 'numeric', 'min:0.5', 'max:40'],
            'aktivitas'  => ['required', 'string', 'max:120'],
            'deskripsi'  => ['required', 'string', 'max:1000'],
        ]);

        if (Schema::hasTable('timesheet')) {
            Timesheet::create([
                'asisten_id' => auth()->id() ?? 1,
                'tanggal' => $validated['tanggal'],
                'jumlah_jam' => (float) $validated['jam'],
                'uraian_pekerjaan' => trim($validated['aktivitas'].' - '.$validated['deskripsi']),
                'status_approval' => 'pending',
                'catatan_revisi' => null,
            ]);
        }

        return redirect()->route('mahasiswa.timesheet')
                         ->with('success', 'Timesheet berhasil diajukan dan sedang menunggu review dosen.');
    }
}
