<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    public function index()
    {
        // Data dummy — nantinya diganti query ke database berdasarkan user yang login
        $pendaftaran = [
            'matkul'          => 'Algoritma Struktur Data',
            'kode'            => '2403',
            'sks'             => 3,
            'didaftarkan_pada'=> '22 Okt 2026, 08:18',
            'status_terkini'  => 'Menunggu keputusan',
            'nim'             => '105222056',
            'nama'            => 'Nama Mahasiswa Aktif',
            'nilai_matkul'    => 'A-',
            'cv'              => 'CV.pdf',
            'transkrip'       => 'Transkrip.pdf',
        ];

        $langkah = [
            [
                'judul'    => 'Terdaftar',
                'deskripsi'=> 'Formulir dan dokumen berhasil dikirim.',
                'waktu'    => '22 Okt 2026 · 08:18',
                'selesai'  => true,
            ],
            [
                'judul'    => 'Verifikasi berkas',
                'deskripsi'=> 'Dosen memeriksa kelengkapan dokumen.',
                'waktu'    => 'Menunggu proses',
                'selesai'  => false,
            ],
            [
                'judul'    => 'Seleksi / Interview',
                'deskripsi'=> 'Jadwal akan ditampilkan jika kamu lolos tahap berkas.',
                'waktu'    => 'Belum dimulai',
                'selesai'  => false,
            ],
            [
                'judul'    => 'Hasil akhir',
                'deskripsi'=> 'Hasil diumumkan oleh dosen pengampu.',
                'waktu'    => 'Belum diumumkan',
                'selesai'  => false,
            ],
        ];

        return view('mahasiswa.status', compact('pendaftaran', 'langkah'));
    }
}
