<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    /**
     * Data dummy untuk pengumuman lowongan dosen.
     * Digunakan sebagai placeholder sampai data benar-benar dipindah ke database/model.
     */
    private static array $lowongans = [
        [
            'id'         => 1,
            'nama'       => 'Pemrograman Dasar',
            'kode'       => 'IF101',
            'sks'        => 3,
            'kuota'      => 5,
            'tenggat'    => '2026-11-30',
            'jadwal'     => 'Senin 08.00',
            'status'     => 'aktif',
            'deskripsi'  => 'Mata kuliah ini memperkenalkan konsep dasar pemrograman menggunakan bahasa Python.',
            'syarat_ipk' => 3.0,
        ],
        [
            'id'         => 2,
            'nama'       => 'Struktur Data',
            'kode'       => 'IF205',
            'sks'        => 3,
            'kuota'      => 4,
            'tenggat'    => '2026-11-28',
            'jadwal'     => 'Rabu 10.00',
            'status'     => 'tutup',
            'deskripsi'  => 'Membahas implementasi stack, queue, tree, dan graph untuk kebutuhan praktikum.',
            'syarat_ipk' => 3.25,
        ],
    ];

    private function getLowongans(): array
    {
        return self::$lowongans;
    }

    private function findLowongan(int $id): ?array
    {
        return collect(self::$lowongans)->firstWhere('id', $id);
    }

    /**
     * Daftar semua pengumuman lowongan milik dosen.
     */
    public function index(Request $request)
    {
        $lowongans = $this->getLowongans();

        if ($request->expectsJson()) {
            return response()->json($lowongans);
        }

        return response()->json($lowongans);
    }

    /**
     * Form buat lowongan baru.
     */
    public function create()
    {
        return view('dosen.lowongan.create');
    }

    /**
     * Tampilkan detail satu lowongan.
     */
    public function show(Request $request, int $id)
    {
        $lowongan = $this->findLowongan($id);

        abort_if(is_null($lowongan), 404, 'Pengumuman tidak ditemukan.');

        if ($request->expectsJson()) {
            return response()->json($lowongan);
        }

        return response()->json($lowongan);
    }

    /**
     * Form edit lowongan.
     */
    public function edit(Request $request, int $id)
    {
        $lowongan = $this->findLowongan($id);

        abort_if(is_null($lowongan), 404, 'Pengumuman tidak ditemukan.');

        if ($request->expectsJson()) {
            return response()->json($lowongan);
        }

        return response()->json($lowongan);
    }

    /**
     * Simpan lowongan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'kode'       => ['required', 'string', 'max:20'],
            'sks'        => ['required', 'integer', 'min:1', 'max:6'],
            'kuota'      => ['required', 'integer', 'min:1'],
            'tenggat'    => ['required', 'date'],
            'jadwal'     => ['required', 'string', 'max:100'],
            'deskripsi'  => ['required', 'string', 'max:2000'],
            'status'     => ['required', 'in:aktif,tutup'],
            'syarat_ipk' => ['nullable', 'numeric', 'min:0', 'max:4'],
        ]);

        $lowongans = self::$lowongans;
        $nextId = collect($lowongans)->max('id') + 1;

        $lowongans[] = [
            'id'         => $nextId,
            'nama'       => $validated['nama'],
            'kode'       => strtoupper($validated['kode']),
            'sks'        => (int) $validated['sks'],
            'kuota'      => (int) $validated['kuota'],
            'tenggat'    => $validated['tenggat'],
            'jadwal'     => $validated['jadwal'],
            'status'     => $validated['status'],
            'deskripsi'  => $validated['deskripsi'],
            'syarat_ipk' => $validated['syarat_ipk'] ?? null,
        ];

        self::$lowongans = $lowongans;

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lowongan berhasil dibuat.',
                'data'    => end(self::$lowongans),
            ], 201);
        }

        return redirect()->route('dosen.dashboard')
            ->with('success', 'Lowongan berhasil dibuat.');
    }

    /**
     * Update pengumuman lowongan yang sudah ada.
     */
    public function update(Request $request, int $id)
    {
        $lowongan = $this->findLowongan($id);

        abort_if(is_null($lowongan), 404, 'Pengumuman tidak ditemukan.');

        $validated = $request->validate([
            'nama'       => ['sometimes', 'required', 'string', 'max:255'],
            'kode'       => ['sometimes', 'required', 'string', 'max:20'],
            'sks'        => ['sometimes', 'required', 'integer', 'min:1', 'max:6'],
            'kuota'      => ['sometimes', 'required', 'integer', 'min:1'],
            'tenggat'    => ['sometimes', 'required', 'date'],
            'jadwal'     => ['sometimes', 'required', 'string', 'max:100'],
            'deskripsi'  => ['sometimes', 'required', 'string', 'max:2000'],
            'status'     => ['sometimes', 'required', 'in:aktif,tutup'],
            'syarat_ipk' => ['nullable', 'numeric', 'min:0', 'max:4'],
        ]);

        foreach (self::$lowongans as $index => $item) {
            if ($item['id'] !== $id) {
                continue;
            }

            self::$lowongans[$index] = array_merge($item, [
                'nama'       => $validated['nama'] ?? $item['nama'],
                'kode'       => strtoupper($validated['kode'] ?? $item['kode']),
                'sks'        => isset($validated['sks']) ? (int) $validated['sks'] : $item['sks'],
                'kuota'      => isset($validated['kuota']) ? (int) $validated['kuota'] : $item['kuota'],
                'tenggat'    => $validated['tenggat'] ?? $item['tenggat'],
                'jadwal'     => $validated['jadwal'] ?? $item['jadwal'],
                'status'     => $validated['status'] ?? $item['status'],
                'deskripsi'  => $validated['deskripsi'] ?? $item['deskripsi'],
                'syarat_ipk' => array_key_exists('syarat_ipk', $validated) ? $validated['syarat_ipk'] : $item['syarat_ipk'],
            ]);

            $lowongan = self::$lowongans[$index];
            break;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lowongan berhasil diperbarui.',
                'data'    => $lowongan,
            ]);
        }

        return redirect()->route('dosen.dashboard')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * Hapus pengumuman lowongan.
     */
    public function destroy(Request $request, int $id)
    {
        $lowongan = $this->findLowongan($id);

        abort_if(is_null($lowongan), 404, 'Pengumuman tidak ditemukan.');

        self::$lowongans = array_values(array_filter(self::$lowongans, fn ($item) => $item['id'] !== $id));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lowongan berhasil dihapus.',
                'deleted_id' => $id,
            ]);
        }

        return redirect()->route('dosen.dashboard')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    /**
     * Toggle status pengumuman antara aktif dan tutup.
     */
    public function toggleStatus(Request $request, int $id)
    {
        $lowongan = $this->findLowongan($id);

        abort_if(is_null($lowongan), 404, 'Pengumuman tidak ditemukan.');

        $status = $request->input('status', $lowongan['status'] === 'aktif' ? 'tutup' : 'aktif');

        if (! in_array($status, ['aktif', 'tutup'], true)) {
            $status = $lowongan['status'] === 'aktif' ? 'tutup' : 'aktif';
        }

        foreach (self::$lowongans as $index => $item) {
            if ($item['id'] !== $id) {
                continue;
            }

            self::$lowongans[$index]['status'] = $status;
            $lowongan = self::$lowongans[$index];
            break;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Status pengumuman berhasil diubah.',
                'data'    => $lowongan,
            ]);
        }

        return back()->with('success', 'Status pengumuman berhasil diubah.');
    }
}
