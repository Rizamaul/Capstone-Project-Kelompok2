<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use Carbon\Carbon;

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

        /* TODO Real Database Query:
        $entries = Timesheet::where('user_id', auth()->id())->orderBy('tanggal', 'desc')->get();
        $totalJamDisetujui = $entries->where('status', 'Disetujui')->sum('jam');
        */

        return view('mahasiswa.timesheet', compact('entries', 'totalJamDisetujui', 'matakuliahAktif'));
    }

    /**
     * Simpan entri timesheet baru dengan validasi Max 40 Jam/Minggu.
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

        $userId = auth()->id();
        $tanggalInput = Carbon::parse($validated['tanggal']);
        
        // Menentukan rentang awal (Senin) dan akhir (Minggu) dari tanggal input
        $startOfWeek = $tanggalInput->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $endOfWeek   = $tanggalInput->copy()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');

        // Menghitung akumulasi jam pada minggu yang sama di database
        $jamSudahAda = Timesheet::where('user_id', $userId) // atau 'mahasiswa_id', sesuaikan dengan database Anda
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->where('status', '!=', 'Ditolak') // Abaikan jam yang ditolak dosen
            ->sum('jam');

        // Validasi: Tolak jika total jam minggu ini melebihi 40 jam
        if (($jamSudahAda + $validated['jam']) > 40) {
            return back()->withInput()->withErrors([
                'jam' => "Total jam kerja minggu ini melebihi batas maksimal 40 jam. (Sudah terisi: {$jamSudahAda} jam. Input baru: {$validated['jam']} jam)."
            ]);
        }

        // Menyimpan data ke database
        Timesheet::create([
            'user_id'    => $userId, // atau 'mahasiswa_id', sesuaikan dengan migrasi
            'matakuliah' => $validated['matakuliah'],
            'tanggal'    => $validated['tanggal'],
            'jam'        => $validated['jam'],
            'aktivitas'  => $validated['aktivitas'],
            'deskripsi'  => $validated['deskripsi'],
            'status'     => 'Menunggu',
        ]);

        return redirect()->route('mahasiswa.timesheet')
                         ->with('success', 'Timesheet berhasil diajukan dan sedang menunggu review dosen.');
    }
}