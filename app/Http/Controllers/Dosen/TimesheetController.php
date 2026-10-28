<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

class TimesheetController extends Controller
{
    /**
     * Daftar timesheet mahasiswa yang perlu di-review.
     */
    public function index()
    {
        return view('dosen.timesheet.index');
    }

    /**
     * Detail satu entri timesheet.
     */
    public function show(int $id)
    {
        // TODO: ambil data timesheet berdasarkan $id dari database
        return view('dosen.timesheet.detail', compact('id'));
    }
}
