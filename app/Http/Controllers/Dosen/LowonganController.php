<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    /**
     * Form buat lowongan baru.
     */
    public function create()
    {
        return view('dosen.lowongan.create');
    }

    /**
     * Simpan lowongan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'kode'        => ['required', 'string', 'max:20'],
            'sks'         => ['required', 'integer', 'min:1', 'max:6'],
            'kuota'       => ['required', 'integer', 'min:1'],
            'tenggat'     => ['required', 'date'],
            'jadwal'      => ['required', 'string', 'max:100'],
            'deskripsi'   => ['required', 'string', 'max:2000'],
            'syarat_ipk'  => ['nullable', 'numeric', 'min:0', 'max:4'],
        ]);

        // TODO: simpan ke database
        // Lowongan::create([...$validated, 'dosen_id' => auth()->id()]);

        return redirect()->route('dosen.dashboard')
                         ->with('success', 'Lowongan berhasil dibuat.');
    }
}
