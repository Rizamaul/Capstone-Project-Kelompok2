<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Schema;

class TimesheetController extends Controller
{
    /**
     * Daftar timesheet mahasiswa yang perlu di-review.
     */
    public function index()
    {
        $entries = [];

        if (Schema::hasTable('timesheet')) {
            $entries = Timesheet::query()
                ->orderByDesc('tanggal')
                ->get()
                ->map(function (Timesheet $entry): array {
                    return [
                        'id' => $entry->id,
                        'nama' => 'Mahasiswa '. $entry->id,
                        'matakuliah' => 'Asistensi Praktikum',
                        'tanggal' => $entry->tanggal->format('d M Y'),
                        'jam' => (float) $entry->jumlah_jam,
                        'status' => $entry->status_approval === 'approved' ? 'Disetujui' : 'Menunggu',
                        'aktivitas' => $entry->uraian_pekerjaan,
                    ];
                })
                ->all();
        }

        if (empty($entries)) {
            $entries = [
                [
                    'id' => 1,
                    'nama' => 'Khairul Hilmi',
                    'matakuliah' => 'Algoritma Struktur Data',
                    'tanggal' => '26 Okt 2026',
                    'jam' => 3.5,
                    'status' => 'Menunggu',
                    'aktivitas' => 'Mendampingi praktikum dan membantu mahasiswa memperbaiki error pada tugas.',
                ],
                [
                    'id' => 2,
                    'nama' => 'Aisyah Putri',
                    'matakuliah' => 'Jaringan Komputer',
                    'tanggal' => '28 Okt 2026',
                    'jam' => 2,
                    'status' => 'Disetujui',
                    'aktivitas' => 'Menyiapkan modul praktikum dan mengawasi sesi lab.',
                ],
            ];
        }

        return view('dosen.timesheet.index', compact('entries'));
    }

    /**
     * Detail satu entri timesheet.
     */
    public function show(int $id)
    {
        $entry = Schema::hasTable('timesheet') ? Timesheet::find($id) : null;

        if (! $entry) {
            $entry = (object) [
                'id' => $id,
                'nama' => 'Khairul Hilmi',
                'nim' => '128973407',
                'mata_kuliah' => 'Algoritma Struktur Data',
                'kode_mata_kuliah' => 'KODE MK',
                'kelas' => '2402',
                'sks' => 3,
                'tanggal' => now()->subDays(1),
                'jumlah_jam' => 3.5,
                'uraian_pekerjaan' => 'Mendampingi praktikum dan membantu mahasiswa memperbaiki error pada tugas.',
                'judul_aktivitas' => 'Mendampingi Praktikum',
                'status_approval' => 'pending',
            ];
        }

        return view('dosen.timesheet.detail', ['entry' => $entry, 'id' => $id]);
    }
}
