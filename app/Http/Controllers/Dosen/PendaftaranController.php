<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
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
