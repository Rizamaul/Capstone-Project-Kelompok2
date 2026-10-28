<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Data dummy lowongan — sama dengan LowonganController.
     * Nantinya diganti dengan query ke Model/Database.
     */
    private function getLowongan(int $id): array
    {
        $data = [
            1 => [
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
            2 => [
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

        abort_if(! isset($data[$id]), 404, 'Lowongan tidak ditemukan.');

        return $data[$id];
    }

    /**
     * Tampilkan form pendaftaran untuk lowongan tertentu.
     */
    public function create(int $id)
    {
        $lowongan = $this->getLowongan($id);

        return view('mahasiswa.pendaftaran', compact('lowongan'));
    }

    /**
     * Simpan data pendaftaran yang dikirimkan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lowongan_id'  => ['required', 'integer'],
            'nama'         => ['required', 'string', 'max:255'],
            'nim'          => ['required', 'string', 'max:20'],
            'nilai_matkul' => ['required', 'string'],
            'ipk'          => ['nullable', 'numeric', 'min:0', 'max:4'],
            'motivasi'     => ['required', 'string', 'min:50', 'max:1500'],
            'portfolio'    => ['nullable', 'url', 'max:255'],
            'cv'           => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'transkrip'    => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'persetujuan'  => ['required', 'accepted'],
        ]);

        // TODO: simpan ke database dan file storage
        // $request->file('cv')->store('pendaftaran/cv');
        // $request->file('transkrip')->store('pendaftaran/transkrip');

        return redirect()->route('mahasiswa.status')
                         ->with('success', 'Pendaftaran berhasil dikirim. Pantau status di halaman ini.');
    }
}
