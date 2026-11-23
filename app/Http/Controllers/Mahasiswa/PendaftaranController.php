<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PendaftaranController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $pendaftarans = Pendaftaran::query()
            ->with('lowongan')
            ->when(
                auth()->check(),
                fn (EloquentBuilder $query) => $query->where('user_id', auth()->id()),
                fn (EloquentBuilder $query) => $query->whereKey(session('mahasiswa.pendaftaran_id', 0))
            )
            ->latest()
            ->get()
            ->map(fn (Pendaftaran $pendaftaran): array => $this->present($pendaftaran))
            ->all();

        return response()->json($pendaftarans);
    }

    public function create(int $id): View
    {
        $lowongan = Pengumuman::findOrFail($id);

        abort_unless($this->isOpen($lowongan), 404, 'Pendaftaran lowongan ini sudah ditutup.');

        return view('mahasiswa.pendaftaran', [
            'lowongan' => [
                'id' => $lowongan->id,
                'nama' => $lowongan->mata_kuliah,
                'syarat_nilai' => $lowongan->syarat_nilai,
                'syarat_ipk' => $lowongan->syarat_ipk,
                'nilai_options' => $this->allowedGrades($lowongan),
                'berkas_diminta' => $this->documents($lowongan),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lowongan_id' => ['required', 'integer', 'exists:pengumuman,id'],
        ]);
        $lowongan = Pengumuman::findOrFail($validated['lowongan_id']);

        abort_unless($this->isOpen($lowongan), 404, 'Pendaftaran lowongan ini sudah ditutup.');

        $requiredDocuments = $this->documents($lowongan);
        $allowedGrades = $this->allowedGrades($lowongan);
        $ipkRules = $lowongan->syarat_ipk === null
            ? ['nullable', 'numeric', 'min:0', 'max:4']
            : ['required', 'numeric', 'min:'.$lowongan->syarat_ipk, 'max:4'];

        $validated = array_merge($validated, $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pendaftaran', 'nim')
                    ->where(fn (QueryBuilder $query): QueryBuilder => $query->where('lowongan_id', $lowongan->id)),
            ],
            'nilai_matkul' => ['required', Rule::in($allowedGrades)],
            'ipk' => $ipkRules,
            'motivasi' => ['required', 'string', 'min:50', 'max:1500'],
            'portfolio' => ['nullable', 'url', 'max:255'],
            'cv' => [
                in_array('CV', $requiredDocuments, true) ? 'required' : 'nullable',
                'file',
                'mimes:pdf',
                'max:2048',
            ],
            'transkrip' => [
                in_array('Transkrip nilai', $requiredDocuments, true) ? 'required' : 'nullable',
                'file',
                'mimes:pdf',
                'max:2048',
            ],
            'persetujuan' => ['required', 'accepted'],
        ]));

        $filePaths = [];

        try {
            $pendaftaran = DB::transaction(function () use ($request, $validated, $lowongan, &$filePaths): Pendaftaran {
                $lockedLowongan = Pengumuman::query()
                    ->lockForUpdate()
                    ->findOrFail($lowongan->id);

                if (! $this->isOpen($lockedLowongan)) {
                    throw ValidationException::withMessages([
                        'lowongan_id' => 'Pendaftaran lowongan ini sudah ditutup.',
                    ]);
                }

                if (Pendaftaran::query()
                    ->where('lowongan_id', $lockedLowongan->id)
                    ->where('nim', $validated['nim'])
                    ->exists()) {
                    throw ValidationException::withMessages([
                        'nim' => 'NIM ini sudah terdaftar pada lowongan tersebut.',
                    ]);
                }

                $directory = 'pendaftaran/'.now()->format('Y/m');
                $name = Str::slug($validated['nama']);
                $cvPath = $request->hasFile('cv')
                    ? $request->file('cv')->storeAs($directory, $name.'-cv-'.Str::uuid().'.pdf', 'public')
                    : null;
                if (is_string($cvPath)) {
                    $filePaths[] = $cvPath;
                }

                $transkripPath = $request->hasFile('transkrip')
                    ? $request->file('transkrip')->storeAs($directory, $name.'-transkrip-'.Str::uuid().'.pdf', 'public')
                    : null;
                if (is_string($transkripPath)) {
                    $filePaths[] = $transkripPath;
                }

                if (($request->hasFile('cv') && $cvPath === false)
                    || ($request->hasFile('transkrip') && $transkripPath === false)) {
                    throw new RuntimeException('Dokumen pendaftaran gagal disimpan.');
                }

                return Pendaftaran::create([
                    'user_id' => auth()->id(),
                    'lowongan_id' => $lockedLowongan->id,
                    'nama' => $validated['nama'],
                    'nim' => $validated['nim'],
                    'nilai_matkul' => $validated['nilai_matkul'],
                    'ipk' => $validated['ipk'] ?? null,
                    'motivasi' => $validated['motivasi'],
                    'portfolio' => $validated['portfolio'] ?? null,
                    'cv_path' => $cvPath,
                    'transkrip_path' => $transkripPath,
                    'status' => 'menunggu',
                    'status_seleksi' => 'menunggu',
                    'is_published' => false,
                ]);
            });
        } catch (Throwable $exception) {
            if ($filePaths !== []) {
                Storage::disk('public')->delete($filePaths);
            }

            throw $exception;
        }

        session(['mahasiswa.pendaftaran_id' => $pendaftaran->id]);

        return redirect()->route('mahasiswa.status')
            ->with('success', 'Pendaftaran berhasil dikirim. Pantau statusnya di halaman ini.');
    }

    public function edit(int $id): View
    {
        $pendaftaran = Pendaftaran::with('lowongan')->findOrFail($id);
        $lowongan = $pendaftaran->lowongan;

        return view('mahasiswa.edit-pendaftaran', [
            'pendaftaran' => $pendaftaran,
            'lowongan' => [
                'id' => $lowongan?->id,
                'nama' => $lowongan?->mata_kuliah ?? 'Mata Kuliah',
                'kode' => $lowongan?->kode ?? 'IF101',
                'sks' => $lowongan?->sks ?? 3,
                'syarat_nilai' => $lowongan?->syarat_nilai,
                'syarat_ipk' => $lowongan?->syarat_ipk,
                'nilai_options' => $lowongan ? $this->allowedGrades($lowongan) : ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'],
                'berkas_diminta' => $lowongan ? $this->documents($lowongan) : ['CV', 'Transkrip nilai'],
            ],
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $pendaftaran = Pendaftaran::with('lowongan')->findOrFail($id);
        $lowongan = $pendaftaran->lowongan;

        $allowedGrades = $lowongan ? $this->allowedGrades($lowongan) : ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'];
        $minIpk = $lowongan?->syarat_ipk;
        $ipkRules = $minIpk === null
            ? ['nullable', 'numeric', 'gt:0', 'max:4']
            : ['required', 'numeric', 'min:'.$minIpk, 'max:4'];

        if ($request->has('ipk') && is_string($request->input('ipk'))) {
            $request->merge([
                'ipk' => str_replace(',', '.', trim($request->input('ipk'))),
            ]);
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pendaftaran', 'nim')
                    ->where(fn (\Illuminate\Database\Query\Builder $query) => $query->where('lowongan_id', $pendaftaran->lowongan_id))
                    ->ignore($pendaftaran->id),
            ],
            'nilai_matkul' => ['required', Rule::in($allowedGrades)],
            'ipk' => $ipkRules,
            'motivasi' => ['required', 'string', 'min:50', 'max:1500'],
            'portfolio' => ['nullable', 'url', 'max:255'],
            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'transkrip' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        $updateData = [
            'nama' => $validated['nama'],
            'nim' => $validated['nim'],
            'nilai_matkul' => $validated['nilai_matkul'],
            'ipk' => $validated['ipk'],
            'motivasi' => $validated['motivasi'],
            'portfolio' => $validated['portfolio'] ?? null,
        ];

        $directory = 'pendaftaran/'.now()->format('Y/m');
        $name = Str::slug($validated['nama']);

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->storeAs($directory, $name.'-cv-'.Str::uuid().'.pdf', 'public');
            if ($cvPath) {
                $updateData['cv_path'] = $cvPath;
            }
        }

        if ($request->hasFile('transkrip')) {
            $transkripPath = $request->file('transkrip')->storeAs($directory, $name.'-transkrip-'.Str::uuid().'.pdf', 'public');
            if ($transkripPath) {
                $updateData['transkrip_path'] = $transkripPath;
            }
        }

        $pendaftaran->update($updateData);

        return redirect()->route('mahasiswa.status.show', $pendaftaran->id)
            ->with('success', 'Ringkasan pendaftaran berhasil diperbarui.');
    }

    private function isOpen(Pengumuman $lowongan): bool
    {
        $accepted = $lowongan->pendaftarans()
            ->where('status_seleksi', 'disetujui')
            ->where('is_published', true)
            ->count();

        return $lowongan->status_aktif
            && $lowongan->tenggat?->isFuture()
            && $accepted < $lowongan->kuota;
    }

    /**
     * @return array<int, string>
     */
    private function documents(Pengumuman $lowongan): array
    {
        $documents = json_decode((string) $lowongan->berkas_diminta, true);

        return is_array($documents) ? $documents : ['CV', 'Transkrip nilai'];
    }

    /**
     * @return array<int, string>
     */
    private function allowedGrades(Pengumuman $lowongan): array
    {
        $gradeScale = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'E'];
        $minimumGradeIndex = array_search($lowongan->syarat_nilai, $gradeScale, true);

        return $minimumGradeIndex === false
            ? $gradeScale
            : array_slice($gradeScale, 0, $minimumGradeIndex + 1);
    }

    /**
     * @return array<string, mixed>
     */
    private function present(Pendaftaran $pendaftaran): array
    {
        return [
            'id' => $pendaftaran->id,
            'lowongan_id' => $pendaftaran->lowongan_id,
            'matakuliah' => $pendaftaran->lowongan?->mata_kuliah,
            'nama' => $pendaftaran->nama,
            'nim' => $pendaftaran->nim,
            'nilai_matkul' => $pendaftaran->nilai_matkul,
            'ipk' => $pendaftaran->ipk,
            'motivasi' => $pendaftaran->motivasi,
            'portfolio' => $pendaftaran->portfolio,
            'cv_path' => $pendaftaran->cv_path,
            'transkrip_path' => $pendaftaran->transkrip_path,
            'status' => $pendaftaran->status,
            'status_seleksi' => $pendaftaran->is_published ? $pendaftaran->status_seleksi : null,
            'created_at' => $pendaftaran->created_at?->toISOString(),
        ];
    }
}
