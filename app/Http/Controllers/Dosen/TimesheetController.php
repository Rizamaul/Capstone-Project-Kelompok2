<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet; // Pastikan model ini sudah ada

class TimesheetController extends Controller
{
    /**
     * Daftar timesheet mahasiswa yang perlu di-review.
     */
    public function index()
    {
        // Opsional: Kirim data timesheet ke view
        // $timesheets = Timesheet::with('mahasiswa')->orderBy('tanggal', 'desc')->get();
        // return view('dosen.timesheet.index', compact('timesheets'));
        
        return view('dosen.timesheet.index');
    }

    /**
     * Detail satu entri timesheet.
     */
    public function show(int $id)
    {
        // Menerapkan TODO: ambil data timesheet berdasarkan $id
        $timesheet = Timesheet::findOrFail($id);
        
        return view('dosen.timesheet.detail', compact('timesheet', 'id'));
    }

    /**
     * [Handler] Approve Timesheet
     */
    public function approve(Request $request, int $id)
    {
        $timesheet = Timesheet::findOrFail($id);

        $timesheet->update([
            'status'               => 'Disetujui',
            'catatan_revisi_dosen' => null, // Bersihkan catatan jika disetujui
        ]);

        return redirect()->back()->with('success', 'Timesheet berhasil disetujui.');
    }

    /**
     * [Handler] Reject/Revisi Timesheet
     */
    public function reject(Request $request, int $id)
    {
        // Validasi wajib mengisi catatan revisi
        $request->validate([
            'catatan_revisi_dosen' => ['required', 'string', 'min:5']
        ], 
        [
            'catatan_revisi_dosen.required' => 'Catatan revisi wajib diisi agar mahasiswa tahu alasannya.'
        ]);

        $timesheet = Timesheet::findOrFail($id);

        $timesheet->update([
            'status'               => 'Ditolak',
            'catatan_revisi_dosen' => $request->catatan_revisi_dosen,
        ]);

        return redirect()->back()->with('success', 'Timesheet ditolak dan dikembalikan untuk direvisi.');
    }
}