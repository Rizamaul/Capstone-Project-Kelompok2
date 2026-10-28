<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy — nantinya diganti dengan query ke database
        $lowongans = [
            [
                'id'      => 1,
                'kode'    => 'IF101',
                'nama'    => 'Pemrograman Dasar',
                'dosen'   => 'Dr. Budi Santoso',
                'sks'     => 3,
                'kuota'   => 5,
                'terisi'  => 2,
                'tenggat' => '30 Nov 2025',
                'jadwal'  => 'Senin 08.00',
                'status'  => 'Dibuka',
            ],
            [
                'id'      => 2,
                'kode'    => 'IF205',
                'nama'    => 'Struktur Data',
                'dosen'   => 'Dr. Siti Rahayu',
                'sks'     => 3,
                'kuota'   => 4,
                'terisi'  => 1,
                'tenggat' => '28 Nov 2025',
                'jadwal'  => 'Rabu 10.00',
                'status'  => 'Dibuka',
            ],
        ];

        return view('mahasiswa.dashboard', compact('lowongans'));
    }
}
