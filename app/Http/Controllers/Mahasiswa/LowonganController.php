<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class LowonganController extends Controller
{
    /**
     * Semua data lowongan disimpan di sini sebagai data dummy.
     * Nantinya diganti query ke Model/Database.
     */
    private function getData(): array
    {
        return [
            [
                'id'       => 1,
                'kode'     => 'IF101',
                'nama'     => 'Pemrograman Dasar',
                'dosen'    => 'Dr. Budi Santoso',
                'sks'      => 3,
                'kuota'    => 5,
                'terisi'   => 2,
                'tenggat'  => '30 Nov 2025',
                'jadwal'   => 'Senin 08.00',
                'status'   => 'Dibuka',
                'deskripsi'=> 'Mata kuliah ini memperkenalkan konsep dasar pemrograman menggunakan bahasa Python. Asisten bertanggung jawab mendampingi praktikum mingguan dan memeriksa laporan.',
                'syarat'   => 'B+',
            ],
            [
                'id'       => 2,
                'kode'     => 'IF205',
                'nama'     => 'Struktur Data',
                'dosen'    => 'Dr. Siti Rahayu',
                'sks'      => 3,
                'kuota'    => 4,
                'terisi'   => 1,
                'tenggat'  => '28 Nov 2025',
                'jadwal'   => 'Rabu 10.00',
                'status'   => 'Dibuka',
                'deskripsi'=> 'Mata kuliah Struktur Data membahas implementasi stack, queue, tree, dan graph. Asisten membantu mahasiswa memahami konsep dan memandu sesi praktikum.',
                'syarat'   => 'A-',
            ],
            [
                'id'       => 3,
                'kode'     => 'IF310',
                'nama'     => 'Jaringan Komputer',
                'dosen'    => 'Dr. Ahmad Fauzi',
                'sks'      => 3,
                'kuota'    => 3,
                'terisi'   => 3,
                'tenggat'  => '15 Nov 2025',
                'jadwal'   => 'Jumat 13.00',
                'status'   => 'Ditutup',
                'deskripsi'=> 'Mata kuliah ini membahas konsep jaringan komputer mulai dari model OSI hingga protokol TCP/IP. Rekrutmen untuk semester ini telah ditutup.',
                'syarat'   => 'B',
            ],
        ];
    }

    /**
     * Daftar semua lowongan.
     */
    public function index()
    {
        $lowongans = $this->getData();

        return view('mahasiswa.lowongan', compact('lowongans'));
    }

    /**
     * Detail satu lowongan berdasarkan ID.
     */
    public function show(int $id)
    {
        $lowongan = collect($this->getData())->firstWhere('id', $id);

        abort_if(is_null($lowongan), 404, 'Lowongan tidak ditemukan.');

        return view('mahasiswa.detail-lowongan', compact('lowongan'));
    }
}
