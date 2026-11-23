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
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function index(): View
    {
        $pendaftarans = Pendaftaran::query()
            ->with('lowongan')
            ->latest()
            ->get();

        return view('dosen.pendaftar.index', compact('pendaftarans'));
    }

    public function show(Pendaftaran $pendaftaran): View
    {
        $pendaftaran->load('lowongan');

        return view('dosen.pendaftaran.show', compact('pendaftaran'));
    }

    public function verifikasiBerkas(Request $request, Pendaftaran $pendaftaran): JsonResponse|RedirectResponse
    {
        $action = $request->input('status', 'diverifikasi');
        $validated = $request->validate([
            'status' => ['required', 'in:diverifikasi,ditolak'],
            'alasan' => ['nullable', 'required_if:status,ditolak', 'string', 'max:1000'],
        ]);

        if ($action === 'ditolak') {
            $pendaftaran->update([
                'status_berkas' => 'ditolak',
                'berkas_diverifikasi' => false,
                'catatan_berkas' => $validated['alasan'] ?? 'Berkas persyaratan belum lengkap atau tidak valid.',
                'waktu_verifikasi_berkas' => now(),
            ]);
            $msg = 'Berkas pendaftaran ditolak dengan alasan.';
        } else {
            $pendaftaran->update([
                'status_berkas' => 'diverifikasi',
                'berkas_diverifikasi' => true,
                'catatan_berkas' => null,
                'waktu_verifikasi_berkas' => now(),
            ]);
            $msg = 'Status berkas pendaftaran berhasil diverifikasi.';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status_berkas' => $pendaftaran->status_berkas,
                'message' => $msg,
                'waktu' => $pendaftaran->waktu_verifikasi_berkas?->translatedFormat('d M Y, H:i'),
            ]);
        }

        return back()->with('success', $msg);
    }

    public function aturWawancara(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validate([
            'jadwal_wawancara' => ['required', 'string'],
            'lokasi_wawancara' => ['required', 'string', 'max:255'],
            'catatan_wawancara' => ['nullable', 'string', 'max:1000'],
        ]);

        $rawJadwal = $validated['jadwal_wawancara'];
        try {
            $parsedDate = Carbon::parse($rawJadwal);
            $formattedJadwal = $parsedDate->translatedFormat('l, d M Y, H:i') . ' WIB';
        } catch (\Throwable $e) {
            $formattedJadwal = $rawJadwal;
        }

        $pendaftaran->update([
            'jadwal_wawancara' => $formattedJadwal,
            'lokasi_wawancara' => $validated['lokasi_wawancara'],
            'catatan_wawancara' => $validated['catatan_wawancara'] ?? null,
            'berkas_diverifikasi' => true,
            'status_berkas' => $pendaftaran->status_berkas === 'ditolak' ? 'ditolak' : 'diverifikasi',
            'waktu_verifikasi_berkas' => $pendaftaran->waktu_verifikasi_berkas ?? now(),
        ]);

        return back()->with('success', 'Jadwal dan lokasi wawancara berhasil disimpan.');
    }

    public function updateStatus(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validate([
            'status_seleksi' => ['required', 'in:disetujui,ditolak'],
            'catatan_dosen' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($pendaftaran, $validated): void {
            $lowongan = Pengumuman::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->lowongan_id);
            $currentPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->id);

            abort_if(
                $currentPendaftaran->is_published || $currentPendaftaran->status !== 'menunggu',
                409,
                'Pendaftaran ini sudah ditinjau.'
            );

            if ($validated['status_seleksi'] === 'disetujui') {
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

        return back()->with('success', 'Keputusan pendaftaran berhasil diumumkan.');
    }
}
