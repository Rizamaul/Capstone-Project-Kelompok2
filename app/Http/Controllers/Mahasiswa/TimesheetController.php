<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    /**
     * Tampilkan riwayat timesheet mahasiswa.
     */
    public function index()
    {
        // Data dummy — nantinya diganti query ke database
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

        // TODO: simpan ke database
        // Timesheet::create([...$validated, 'mahasiswa_id' => auth()->id(), 'status' => 'Menunggu']);

        return redirect()->route('mahasiswa.timesheet')
                         ->with('success', 'Timesheet berhasil diajukan dan sedang menunggu review dosen.');
    }
}
