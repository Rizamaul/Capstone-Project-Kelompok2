<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PendaftaranController extends Controller
{
    /**
     * Tampilkan form pendaftaran untuk lowongan tertentu.
     */
    public function create(int $id)
    {
        // Ganti query dummy dengan pemanggilan ke database Pengumuman
        $lowongan = Pengumuman::findOrFail($id);

        return view('mahasiswa.pendaftaran', compact('lowongan'));
    }

    /**
     * [Mahasiswa] Simpan pendaftaran baru & upload berkas
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lowongan_id'  => ['required', 'exists:pengumuman,id'],
            'nama'         => ['required', 'string', 'max:255'],
            'nim'          => ['required', 'string', 'max:20'],
            'nilai_matkul' => ['required', 'string'],
            'ipk'          => ['nullable', 'numeric', 'min:0', 'max:4'],
            'motivasi'     => ['required', 'string', 'min:50', 'max:1500'],
            'portfolio'    => ['nullable', 'url', 'max:255'],
            'cv'           => ['required', 'file', 'mimes:pdf', 'max:2048'], // PDF max 2MB
            'transkrip'    => ['required', 'file', 'mimes:pdf', 'max:2048'], // PDF max 2MB
            'persetujuan'  => ['required', 'accepted'],
        ]);

        $user_id = auth()->id(); // Asumsi mahasiswa sudah login

        // Cegah pendaftaran ganda (1 mahasiswa hanya 1x daftar di lowongan yang sama)
        $sudahDaftar = Pendaftaran::where('user_id', $user_id)
            ->where('lowongan_id', $validated['lowongan_id'])
            ->exists();

        if ($sudahDaftar) {
            return back()->withInput()->withErrors(['lowongan_id' => 'Anda sudah terdaftar pada lowongan ini.']);
        }

        // Penamaan direktori dan file
        $directory = 'pendaftaran/' . date('Y') . '/' . date('m');
        $baseName = Str::slug(pathinfo($validated['nama'], PATHINFO_FILENAME), '-');
        
        $cvPath = $request->file('cv')->storeAs(
            $directory,
            $baseName . '-cv-' . Str::uuid() . '.pdf',
            'public'
        );

        $transkripPath = $request->file('transkrip')->storeAs(
            $directory,
            $baseName . '-transkrip-' . Str::uuid() . '.pdf',
            'public'
        );

        // Flash message untuk frontend
        session()->flash('berkas_tersimpan', [
            'cv'            => $cvPath,
            'transkrip'     => $transkripPath,
            'cv_url'        => Storage::disk('public')->url($cvPath),
            'transkrip_url' => Storage::disk('public')->url($transkripPath),
        ]);

        // Simpan ke database menggunakan Model
        Pendaftaran::create([
            'user_id'        => $user_id,
            'lowongan_id'    => $validated['lowongan_id'],
            'nama'           => $validated['nama'],
            'nim'            => $validated['nim'],
            'nilai_matkul'   => $validated['nilai_matkul'],
            'ipk'            => $validated['ipk'] ?? null,
            'motivasi'       => $validated['motivasi'],
            'portfolio'      => $validated['portfolio'] ?? null,
            'cv_path'        => $cvPath,
            'transkrip_path' => $transkripPath,
            'status'         => 'menunggu',
            'status_seleksi' => 'menunggu',
            'is_published'   => false,
        ]);

        return redirect()->route('mahasiswa.status')
            ->with('success', 'Pendaftaran berhasil dikirim. Berkas sudah disimpan di storage publik dan pantau status di halaman ini.');
    }

    /**
     * [Mahasiswa] Update pendaftaran (Timpa file lama) - US-06
     */
    public function update(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('pengumuman')
            ->firstOrFail();

        // Validasi Tenggat Waktu (Hanya bisa edit sebelum tenggat)
        if (Carbon::now()->greaterThan($pendaftaran->pengumuman->tenggat)) {
            return back()->withErrors(['tenggat' => 'Masa pendaftaran dan edit data sudah ditutup.']);
        }

        $validated = $request->validate([
            'nilai_matkul' => ['nullable', 'string'],
            'ipk'          => ['nullable', 'numeric', 'min:0', 'max:4'],
            'motivasi'     => ['nullable', 'string', 'min:50', 'max:1500'],
            'portfolio'    => ['nullable', 'url', 'max:255'],
            'cv'           => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'transkrip'    => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        $updateData = [];
        if ($request->filled('nilai_matkul')) $updateData['nilai_matkul'] = $validated['nilai_matkul'];
        if ($request->filled('ipk'))          $updateData['ipk'] = $validated['ipk'];
        if ($request->filled('motivasi'))     $updateData['motivasi'] = $validated['motivasi'];
        if ($request->filled('portfolio'))    $updateData['portfolio'] = $validated['portfolio'];

        $directory = 'pendaftaran/' . date('Y') . '/' . date('m');
        $baseName = Str::slug(pathinfo($pendaftaran->nama, PATHINFO_FILENAME), '-');

        // Logika Overwrite CV
        if ($request->hasFile('cv')) {
            if ($pendaftaran->cv_path && Storage::disk('public')->exists($pendaftaran->cv_path)) {
                Storage::disk('public')->delete($pendaftaran->cv_path);
            }
            $updateData['cv_path'] = $request->file('cv')->storeAs(
                $directory, 
                $baseName . '-cv-' . Str::uuid() . '.pdf', 
                'public'
            );
        }

        // Logika Overwrite Transkrip
        if ($request->hasFile('transkrip')) {
            if ($pendaftaran->transkrip_path && Storage::disk('public')->exists($pendaftaran->transkrip_path)) {
                Storage::disk('public')->delete($pendaftaran->transkrip_path);
            }
            $updateData['transkrip_path'] = $request->file('transkrip')->storeAs(
                $directory, 
                $baseName . '-transkrip-' . Str::uuid() . '.pdf', 
                'public'
            );
        }

        $pendaftaran->update($updateData);

        return redirect()->route('mahasiswa.status')
            ->with('success', 'Data pendaftaran dan berkas Anda berhasil diperbarui.');
    }
    
    /**
     * API/Tampilan untuk mengambil riwayat status pendaftaran mahasiswa
     */
    public function status(Request $request)
    {
        // Ambil seluruh riwayat pendaftaran mahasiswa yang sedang login
        $riwayatPendaftaran = Pendaftaran::where('user_id', auth()->id())
                                ->with('pengumuman')
                                ->get();
                                
        // Lakukan sanitasi data (Sembunyikan status internal yang belum di-publish dosen)
        $riwayatSanitized = $riwayatPendaftaran->map(function ($pendaftaran) {
            $data = $pendaftaran->toArray();
            
            unset($data['catatan_dosen']); // Selalu sembunyikan catatan dosen dari mahasiswa
            
            if (!$pendaftaran->is_published) {
                unset($data['status_seleksi']);
                unset($data['is_published']);
                // status publik otomatis sudah diatur sebagai 'menunggu' di database
            }
            return $data;
        });

        if ($request->wantsJson()) {
            return response()->json($riwayatSanitized);
        }
        
        // Kembalikan ke view frontend status
        return view('mahasiswa.status', compact('riwayatSanitized'));
    }

    public function getListPendaftarDosen(Request $request, $lowongan_id)
    {
        // Ambil semua pendaftar pada lowongan tertentu
        $pendaftar = Pendaftaran::where('lowongan_id', $lowongan_id)->get();

        // Data dikembalikan utuh (tanpa di-sanitize/dihapus) karena Dosen berhak melihat catatan_dosen dan status_seleksi internal
        return response()->json([
            'message' => 'Berhasil mengambil daftar mahasiswa pendaftar.',
            'data'    => $pendaftar
        ]);
    }
}