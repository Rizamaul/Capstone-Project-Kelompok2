<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy — nantinya diganti query ke database
        $statistik = [
            'pengumuman_aktif' => 2,
            'total_pendaftar'  => 76,
            'kuota_terisi'     => 4,
            'kuota_total'      => 8,
            'review_berkas'    => 26,
        ];

        $lowongans = [
            [
                'nama'            => 'Algoritma Struktur Data',
                'kode'            => '2402',
                'sks'             => 3,
                'kuota'           => 4,
                'terisi'          => 3,
                'total_pelamar'   => 38,
                'belum_verifikasi'=> 19,
                'tenggat'         => '22 Okt, 2026',
                'sisa'            => 'Sisa Beberapa Jam Lagi',
                'status'          => 'Dibuka',
            ],
            [
                'nama'            => 'Jaringan Komputer',
                'kode'            => '2402',
                'sks'             => 3,
                'kuota'           => 4,
                'terisi'          => 1,
                'total_pelamar'   => 38,
                'belum_verifikasi'=> 19,
                'tenggat'         => '22 Okt, 2026',
                'sisa'            => 'Sisa Beberapa Jam Lagi',
                'status'          => 'Dibuka',
            ],
        ];

        return view('dosen.dashboard', compact('statistik', 'lowongans'));
    }
}
