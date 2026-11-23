<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use Illuminate\View\View;

class LowonganController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $lowongans = Pengumuman::query()
            ->withCount([
                'pendaftarans',
                'pendaftarans as accepted_count' => fn ($query) => $query
                    ->where('status_seleksi', 'disetujui')
                    ->where('is_published', true),
            ])
            ->latest()
            ->get()
            ->map(fn (Pengumuman $lowongan): array => $this->present($lowongan))
            ->all();

        if ($request->expectsJson()) {
            return response()->json($lowongans);
        }

        return view('dosen.lowongan.index', compact('lowongans'));
    }

    public function create(): View
    {
        return view('dosen.lowongan.create');
    }

    public function show(Request $request, int $id): JsonResponse|View
    {
        $lowongan = Pengumuman::query()
            ->withCount([
                'pendaftarans',
                'pendaftarans as accepted_count' => fn ($query) => $query
                    ->where('status_seleksi', 'disetujui')
                    ->where('is_published', true),
            ])
            ->findOrFail($id);
        $data = $this->present($lowongan);

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return view('dosen.lowongan.show', ['lowongan' => $data]);
    }

    public function edit(Request $request, int $id): JsonResponse|View
    {
        $lowongan = Pengumuman::findOrFail($id);
        $data = $this->present($lowongan);

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return view('dosen.lowongan.edit', ['lowongan' => $data]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->has('syarat_ipk') && is_string($request->input('syarat_ipk'))) {
            $request->merge([
                'syarat_ipk' => str_replace(',', '.', trim($request->input('syarat_ipk'))),
            ]);
        }

        $validated = $request->validate($this->rules(), [
            'syarat_ipk.gt' => 'Minimal IPK tidak boleh bernilai 0 atau minus.',
            'syarat_ipk.max' => 'Minimal IPK tidak boleh lebih dari 4.00.',
        ]);

        $lowongan = Pengumuman::create($this->attributes($validated) + [
            'dosen_id' => auth()->id() ?? 1,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lowongan berhasil dibuat.',
                'data' => $this->present($lowongan),
            ], 201);
        }

        return redirect()->route('dosen.lowongan.index')
            ->with('success', 'Lowongan berhasil dibuat.');
    }

    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $lowongan = Pengumuman::findOrFail($id);

        if ($request->has('syarat_ipk') && is_string($request->input('syarat_ipk'))) {
            $request->merge([
                'syarat_ipk' => str_replace(',', '.', trim($request->input('syarat_ipk'))),
            ]);
        }

        $validated = $request->validate($this->rules(partial: true), [
            'syarat_ipk.gt' => 'Minimal IPK tidak boleh bernilai 0 atau minus.',
            'syarat_ipk.max' => 'Minimal IPK tidak boleh lebih dari 4.00.',
        ]);

        $lowongan->update($this->attributes($validated));
        $data = $this->present($lowongan->refresh());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lowongan berhasil diperbarui.',
                'data' => $data,
            ]);
        }

        return redirect()->route('dosen.lowongan.index')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $lowongan = Pengumuman::findOrFail($id);

        if ($lowongan->pendaftarans()->exists()) {
            abort(409, 'Lowongan yang sudah memiliki pendaftar tidak dapat dihapus.');
        }

        $lowongan->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lowongan berhasil dihapus.',
                'deleted_id' => $id,
            ]);
        }

        return redirect()->route('dosen.lowongan.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    public function toggleStatus(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $lowongan = Pengumuman::findOrFail($id);
        $validated = $request->validate([
            'status' => ['sometimes', 'required', Rule::in(['aktif', 'tutup'])],
        ]);

        $lowongan->status_aktif = array_key_exists('status', $validated)
            ? $validated['status'] === 'aktif'
            : ! $lowongan->status_aktif;
        $lowongan->save();
        $data = $this->present($lowongan->refresh());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Status pengumuman berhasil diubah.',
                'data' => $data,
            ]);
        }

        return back()->with('success', 'Status pengumuman berhasil diubah.');
    }

    /**
     * @return array<string, array<int, string|In>>
     */
    private function rules(bool $partial = false): array
    {
        $required = $partial ? ['sometimes', 'required'] : ['required'];
        $statusRequired = $partial ? ['sometimes', 'required'] : ['sometimes'];

        return [
            'nama' => [...$required, 'string', 'max:255'],
            'kode' => [...$required, 'string', 'max:20'],
            'sks' => ['nullable', 'integer', 'min:1', 'max:6'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'semester' => ['nullable', 'string', 'max:60'],
            'kuota' => [...$required, 'integer', 'min:1'],
            'jadwal' => ['nullable', 'string', 'max:100'],
            'jadwal_hari' => ['nullable', 'string', 'max:50'],
            'jadwal_jam' => ['nullable', 'string', 'max:50'],
            'tenggat' => [...$required, 'date'],
            'syarat_nilai' => ['nullable', 'string', 'max:10'],
            'syarat_ipk' => ['nullable', 'numeric', 'gt:0', 'max:4'],
            'syarat' => ['nullable', 'string', 'max:2000'],
            'syarat_tambahan' => ['sometimes', 'array'],
            'manfaat' => ['sometimes', 'array'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'berkas_diminta' => ['sometimes', 'array'],
            'berkas_diminta.*' => ['string', 'max:100'],
            'berkas_diminta_present' => ['sometimes', 'accepted'],
            'status' => [...$statusRequired, Rule::in(['aktif', 'tutup'])],
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function attributes(array $validated): array
    {
        $mapping = [
            'nama' => 'mata_kuliah',
            'kode' => 'kode',
            'sks' => 'sks',
            'kelas' => 'kelas',
            'semester' => 'semester',
            'kuota' => 'kuota',
            'jadwal' => 'jadwal',
            'tenggat' => 'tenggat',
            'syarat_nilai' => 'syarat_nilai',
            'syarat_ipk' => 'syarat_ipk',
            'syarat' => 'syarat',
            'deskripsi' => 'deskripsi',
            'berkas_diminta' => 'berkas_diminta',
        ];

        $attributes = [];

        foreach ($mapping as $input => $column) {
            if (array_key_exists($input, $validated)) {
                $attributes[$column] = $input === 'berkas_diminta'
                    ? json_encode($validated[$input], JSON_THROW_ON_ERROR)
                    : $validated[$input];
            }
        }

        if (array_key_exists('berkas_diminta_present', $validated) && ! array_key_exists('berkas_diminta', $validated)) {
            $attributes['berkas_diminta'] = json_encode([], JSON_THROW_ON_ERROR);
        }

        if ((empty($attributes['jadwal']) || $attributes['jadwal'] === 'Senin 08.00 - 10.00 WIB') && (! empty($validated['jadwal_hari']) || ! empty($validated['jadwal_jam']))) {
            $attributes['jadwal'] = trim(($validated['jadwal_hari'] ?? '') . ' ' . ($validated['jadwal_jam'] ?? ''));
        }

        if (! empty($validated['syarat_tambahan']) && empty($attributes['syarat'])) {
            $attributes['syarat'] = implode("\n", array_filter($validated['syarat_tambahan']));
        }
        if (empty($attributes['syarat'])) {
            $attributes['syarat'] = 'Wajib memenuhi seluruh kriteria akademik dan ketentuan praktikum.';
        }

        if (! empty($validated['manfaat']) && empty($attributes['deskripsi'])) {
            $attributes['deskripsi'] = implode("\n", array_filter($validated['manfaat']));
        }
        if (empty($attributes['deskripsi'])) {
            $attributes['deskripsi'] = 'Mendampingi sesi praktikum di laboratorium dan membimbing mahasiswa.';
        }

        if (! array_key_exists('sks', $attributes) || empty($attributes['sks'])) {
            $attributes['sks'] = 3;
        }

        if (! array_key_exists('jadwal', $attributes) || empty($attributes['jadwal'])) {
            $attributes['jadwal'] = 'Senin 08.00 - 10.00 WIB';
        }

        if (array_key_exists('status', $validated)) {
            $attributes['status_aktif'] = $validated['status'] === 'aktif';
        } else {
            $attributes['status_aktif'] = true;
        }

        return $attributes;
    }

    /**
     * @return array<string, mixed>
     */
    private function present(Pengumuman $lowongan): array
    {
        $terisi = $lowongan->accepted_count ?? $lowongan->pendaftarans()
            ->where('status_seleksi', 'disetujui')
            ->where('is_published', true)
            ->count();

        return [
            'id' => $lowongan->id,
            'nama' => $lowongan->mata_kuliah,
            'kode' => $lowongan->kode ?? 'MK',
            'sks' => $lowongan->sks,
            'kelas' => $lowongan->kelas,
            'semester' => $lowongan->semester,
            'kuota' => $lowongan->kuota,
            'terisi' => $terisi,
            'total_pendaftar' => $lowongan->pendaftarans_count ?? $lowongan->pendaftarans()->count(),
            'jadwal' => $lowongan->jadwal ?? 'Belum diatur',
            'tenggat' => $lowongan->tenggat?->format('Y-m-d'),
            'tenggat_input' => $lowongan->tenggat?->format('Y-m-d\TH:i'),
            'status' => $lowongan->status_aktif
                && $lowongan->tenggat?->isFuture()
                && $terisi < $lowongan->kuota
                    ? 'aktif'
                    : 'tutup',
            'status_aktif' => $lowongan->status_aktif ? 'aktif' : 'tutup',
            'deskripsi' => $lowongan->deskripsi ?? $lowongan->syarat,
            'syarat' => $lowongan->syarat_nilai ?? $lowongan->syarat,
            'syarat_nilai' => $lowongan->syarat_nilai,
            'syarat_ipk' => $lowongan->syarat_ipk,
            'berkas_diminta' => $this->documents($lowongan),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function documents(Pengumuman $lowongan): array
    {
        $documents = json_decode((string) $lowongan->berkas_diminta, true);

        return is_array($documents) ? $documents : ['CV', 'Transkrip nilai'];
    }
}
