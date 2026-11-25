<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function index(): View
    {
        $pendaftarans = Pendaftaran::query()
            ->with(['lowongan', 'mahasiswa'])
            ->latest()
            ->get();

        return view('dosen.pendaftar.index', compact('pendaftarans'));
    }

    public function show(Pendaftaran $pendaftaran): View
    {
        $pendaftaran->load(['lowongan', 'mahasiswa']);

        return view('dosen.pendaftaran.show', compact('pendaftaran'));
    }

    public function verifikasiBerkas(Request $request, Pendaftaran $pendaftaran): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:diverifikasi,ditolak'],
            'alasan' => ['nullable', 'required_if:status,ditolak', 'string', 'max:1000'],
        ]);

        $message = $validated['status'] === 'ditolak'
            ? 'Berkas dan seluruh pendaftaran ditolak.'
            : 'Status berkas pendaftaran berhasil diverifikasi.';

        $pendaftaran = DB::transaction(function () use ($pendaftaran, $validated): Pendaftaran {
            $currentPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->id);

            $this->ensureApplicationIsOpen($currentPendaftaran);

            if ($validated['status'] === 'ditolak') {
                $reason = $validated['alasan'];
                $currentPendaftaran->update([
                    'status_berkas' => 'ditolak',
                    'berkas_diverifikasi' => false,
                    'catatan_berkas' => $reason,
                    'waktu_verifikasi_berkas' => now(),
                    'status' => 'ditolak',
                    'status_seleksi' => 'ditolak',
                    'catatan_dosen' => $reason,
                    'is_published' => true,
                ]);
            } else {
                $currentPendaftaran->update([
                    'status_berkas' => 'diverifikasi',
                    'berkas_diverifikasi' => true,
                    'catatan_berkas' => null,
                    'waktu_verifikasi_berkas' => now(),
                ]);
            }

            return $currentPendaftaran->refresh();
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status_berkas' => $pendaftaran->status_berkas,
                'status' => $pendaftaran->status,
                'is_published' => $pendaftaran->is_published,
                'message' => $message,
                'waktu' => $pendaftaran->waktu_verifikasi_berkas?->translatedFormat('d M Y, H:i'),
            ]);
        }

        return back()->with('success', $message);
    }

    public function aturWawancara(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validate([
            'metode_wawancara' => ['required', Rule::in(['langsung', 'online'])],
            'waktu_wawancara' => ['required', 'date', 'after:now'],
            'lokasi_wawancara' => [
                'required',
                'string',
                'max:500',
                Rule::when($request->input('metode_wawancara') === 'online', ['url']),
            ],
            'catatan_wawancara' => ['required', 'string', 'min:20', 'max:2000'],
        ]);

        DB::transaction(function () use ($pendaftaran, $validated): void {
            $currentPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->id);

            $this->ensureApplicationIsOpen($currentPendaftaran);

            abort_unless(
                $currentPendaftaran->status_berkas === 'diverifikasi' || $currentPendaftaran->berkas_diverifikasi,
                409,
                'Verifikasi berkas harus selesai sebelum wawancara dijadwalkan.'
            );
            abort_if(
                $currentPendaftaran->hasil_wawancara !== null,
                409,
                'Hasil wawancara sudah ditetapkan.'
            );

            $waktuWawancara = Carbon::parse($validated['waktu_wawancara']);
            $currentPendaftaran->update([
                'jadwal_wawancara' => $waktuWawancara->translatedFormat('l, d M Y, H:i'),
                'metode_wawancara' => $validated['metode_wawancara'],
                'waktu_wawancara' => $waktuWawancara,
                'lokasi_wawancara' => $validated['lokasi_wawancara'],
                'catatan_wawancara' => $validated['catatan_wawancara'],
            ]);
        });

        return back()->with('success', 'Jadwal wawancara berhasil disimpan. Pendaftaran tetap menunggu hasil wawancara.');
    }

    public function simpanHasilWawancara(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validate([
            'hasil_wawancara' => ['required', Rule::in(['lulus', 'gagal'])],
            'catatan_hasil_wawancara' => ['nullable', 'required_if:hasil_wawancara,gagal', 'string', 'min:10', 'max:2000'],
        ]);

        DB::transaction(function () use ($pendaftaran, $validated): void {
            $currentPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->id);

            $this->ensureApplicationIsOpen($currentPendaftaran);
            abort_unless(
                $currentPendaftaran->status_berkas === 'diverifikasi' || $currentPendaftaran->berkas_diverifikasi,
                409,
                'Verifikasi berkas harus selesai sebelum hasil wawancara disimpan.'
            );
            abort_if($currentPendaftaran->jadwal_wawancara === null, 409, 'Jadwalkan wawancara sebelum menetapkan hasil.');
            abort_if($currentPendaftaran->hasil_wawancara !== null, 409, 'Hasil wawancara sudah ditetapkan.');

            $isPassed = $validated['hasil_wawancara'] === 'lulus';

            $currentPendaftaran->update([
                'hasil_wawancara' => $validated['hasil_wawancara'],
                'catatan_hasil_wawancara' => $validated['catatan_hasil_wawancara'] ?? null,
                'status' => $isPassed ? 'menunggu' : 'ditolak',
                'status_seleksi' => $isPassed ? 'menunggu' : 'ditolak',
                'catatan_dosen' => $isPassed ? null : $validated['catatan_hasil_wawancara'],
                'is_published' => ! $isPassed,
            ]);
        });

        return back()->with(
            'success',
            $validated['hasil_wawancara'] === 'lulus'
                ? 'Hasil wawancara dicatat lulus. Tentukan keputusan akhir pendaftaran.'
                : 'Hasil wawancara tidak lulus dan seluruh pendaftaran telah ditolak.'
        );
    }

    public function simpanKeputusanAkhir(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validate([
            'status_seleksi' => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan_dosen' => ['nullable', 'required_if:status_seleksi,ditolak', 'string', 'min:10', 'max:2000'],
        ]);

        DB::transaction(function () use ($pendaftaran, $validated): void {
            $currentPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->id);

            $this->ensureApplicationIsOpen($currentPendaftaran);
            abort_unless($currentPendaftaran->hasil_wawancara === 'lulus', 409, 'Wawancara harus dinyatakan lulus sebelum keputusan akhir.');

            if ($validated['status_seleksi'] === 'disetujui') {
                $lowongan = Pengumuman::query()
                    ->lockForUpdate()
                    ->findOrFail($currentPendaftaran->lowongan_id);
                $terisi = $lowongan->pendaftarans()
                    ->where('status_seleksi', 'disetujui')
                    ->where('is_published', true)
                    ->count();

                abort_if($terisi >= $lowongan->kuota, 409, 'Kuota lowongan sudah terpenuhi.');
            }

            $currentPendaftaran->update([
                'status' => $validated['status_seleksi'],
                'status_seleksi' => $validated['status_seleksi'],
                'catatan_dosen' => $validated['catatan_dosen'] ?? null,
                'is_published' => true,
            ]);
        });

        return back()->with('success', 'Keputusan akhir pendaftaran berhasil diumumkan.');
    }

    private function ensureApplicationIsOpen(Pendaftaran $pendaftaran): void
    {
        abort_if(
            $pendaftaran->is_published || $pendaftaran->status !== 'menunggu',
            409,
            'Pendaftaran ini sudah ditinjau dan tidak dapat diubah.'
        );
    }
}
