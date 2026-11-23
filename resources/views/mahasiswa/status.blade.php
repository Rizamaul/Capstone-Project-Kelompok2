@extends('layouts.mahasiswa')
@section('title', 'Status Pendaftaran')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold text-[#1E5EFF] tracking-wider uppercase">PANTAU PROGRES</p>
            <h1 class="text-2xl font-extrabold text-gray-900 mt-1">Status Pendaftaran</h1>
            <p class="text-xs text-gray-500 mt-1">Setiap perubahan status ditampilkan agar kamu tahu langkah berikutnya.</p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.lowongan.index') }}" class="text-xs font-semibold text-gray-500 hover:text-[#1E5EFF] transition-colors">
                ← Lihat Pengumuman
            </a>
        </div>
    </div>

    <!-- DAFTAR KARTU STATUS PENDAFTARAN -->
    <div class="space-y-5">
        @forelse($pendaftarans as $pendaftaran)
            @php
                $statusBadgeClass = match($pendaftaran->status) {
                    'disetujui' => 'bg-[#E8FAF0] text-emerald-700',
                    'ditolak' => 'bg-red-50 text-red-700',
                    default => 'bg-[#FEF9E7] text-amber-700',
                };
                $statusDotClass = match($pendaftaran->status) {
                    'disetujui' => 'bg-emerald-500',
                    'ditolak' => 'bg-red-500',
                    default => 'bg-amber-500',
                };
                $statusLabel = match($pendaftaran->status) {
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu',
                };
            @endphp

            <article class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs hover:shadow-md transition-shadow">
                
                <!-- Baris Atas: Kode/SKS & Status Badge (Rata Kanan) -->
                <div class="flex items-center justify-between gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-[#EDF5FF] text-[#1E5EFF] font-bold text-[11px]">
                        {{ $pendaftaran->lowongan?->kode ?? 'IF101' }} · {{ $pendaftaran->lowongan?->sks ?? 3 }} SKS
                    </span>
                    <div class="flex items-center gap-2">
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 {{ $statusBadgeClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDotClass }}"></span>
                            <span>{{ $statusLabel }}</span>
                        </span>
                        <span class="sr-only">
                            {{ $pendaftaran->status === 'disetujui' ? 'Pendaftaran disetujui' : ($pendaftaran->status === 'ditolak' ? 'Pendaftaran ditolak' : 'Menunggu keputusan dosen') }}
                        </span>
                    </div>
                </div>

                <!-- Judul Mata Kuliah & Tanggal Pendaftaran -->
                <h2 class="text-lg font-bold text-gray-900 mt-3">{{ $pendaftaran->lowongan?->mata_kuliah ?? 'Algoritma Struktur Data' }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">Didaftarkan Terakhir {{ $pendaftaran->created_at?->translatedFormat('d M Y, H:i') ?? '22 Okt 2026, 08:18' }}</p>

                <!-- Baris Bawah: Kotak Info Nilai/Berkas & Tombol Lihat Detail -->
                <div class="mt-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="bg-gray-50/80 rounded-xl px-5 py-3 border border-gray-100 flex items-center gap-8 sm:gap-12 text-xs">
                        <div>
                            <p class="text-gray-400 text-[10px]">Nilai</p>
                            <p class="font-bold text-gray-900 mt-0.5">{{ $pendaftaran->nilai_matkul ?: 'A-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px]">Dokumen CV</p>
                            <p class="font-bold text-gray-900 mt-0.5 truncate max-w-[140px] sm:max-w-[180px]">{{ $pendaftaran->cv_path ? basename($pendaftaran->cv_path) : 'CV1.pdf' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px]">Transkip</p>
                            <p class="font-bold text-gray-900 mt-0.5 truncate max-w-[140px] sm:max-w-[180px]">{{ $pendaftaran->transkrip_path ? basename($pendaftaran->transkrip_path) : 'Transkip1.pdf' }}</p>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('mahasiswa.status.show', $pendaftaran->id) }}" 
                           class="w-full md:w-auto px-6 py-2.5 bg-[#1E5EFF] text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-xs inline-flex items-center justify-center gap-1.5">
                            <span>Lihat Detail</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

                @if($pendaftaran->is_published && $pendaftaran->catatan_dosen)
                    <div class="mt-3.5 p-3.5 bg-blue-50/60 rounded-xl border border-blue-100 text-xs text-blue-900">
                        <span class="font-bold">Catatan dosen:</span> {{ $pendaftaran->catatan_dosen }}
                    </div>
                @endif

            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
                <p class="text-base font-bold text-gray-800">Belum ada riwayat pendaftaran</p>
                <p class="text-xs text-gray-400 mt-1">Anda belum melamar lowongan asisten praktikum pada periode ini.</p>
                <a href="{{ route('mahasiswa.lowongan.index') }}" 
                   class="mt-4 inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#1E5EFF] text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-xs">
                    Jelajahi Lowongan →
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
