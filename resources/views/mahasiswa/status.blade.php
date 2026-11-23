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
    <div class="space-y-4">
        @forelse($pendaftarans as $pendaftaran)
            @php
                $statusColor = match($pendaftaran->status) {
                    'disetujui' => 'bg-emerald-50 text-emerald-700',
                    'ditolak' => 'bg-red-50 text-red-700',
                    default => 'bg-amber-50 text-amber-700',
                };
                $statusLabel = match($pendaftaran->status) {
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu',
                };
                $statusSubtitle = match($pendaftaran->status) {
                    'disetujui' => 'Pendaftaran disetujui',
                    'ditolak' => 'Pendaftaran ditolak',
                    default => 'Menunggu keputusan dosen',
                };
            @endphp

            <article class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex-1">
                        <!-- Badges -->
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded-lg bg-[#EDF5FF] text-[#1E5EFF] font-bold text-[11px]">
                                {{ $pendaftaran->lowongan?->kode ?? 'IF101' }} · {{ $pendaftaran->lowongan?->sks ?? 3 }} SKS
                            </span>
                            <span class="px-3 py-1 rounded-full font-bold text-[11px] flex items-center gap-1.5 {{ $statusColor }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $pendaftaran->status === 'disetujui' ? 'bg-emerald-500' : ($pendaftaran->status === 'ditolak' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                                <span>{{ $statusLabel }}</span>
                            </span>
                            <span class="text-[11px] text-gray-400 font-medium">({{ $statusSubtitle }})</span>
                        </div>

                        <!-- Title & Submission Date -->
                        <h2 class="text-lg font-bold text-gray-900">{{ $pendaftaran->lowongan?->mata_kuliah ?? 'Algoritma Struktur Data' }}</h2>
                        <p class="text-xs text-gray-400 mt-1">Didaftarkan Terakhir {{ $pendaftaran->created_at?->translatedFormat('d M Y, H:i') ?? '22 Okt 2026, 08:18' }}</p>

                        <!-- Academic & Documents Info Box -->
                        <div class="grid grid-cols-3 gap-4 bg-gray-50/80 rounded-xl p-4 border border-gray-100 mt-4 max-w-lg text-xs">
                            <div>
                                <p class="text-gray-400 text-[10px]">Nilai</p>
                                <p class="font-bold text-gray-800 mt-1">{{ $pendaftaran->nilai_matkul ?: 'A-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px]">Dokumen CV</p>
                                <p class="font-bold text-gray-800 mt-1 truncate">{{ $pendaftaran->cv_path ? basename($pendaftaran->cv_path) : 'CV1.pdf' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px]">Transkip</p>
                                <p class="font-bold text-gray-800 mt-1 truncate">{{ $pendaftaran->transkrip_path ? basename($pendaftaran->transkrip_path) : 'Transkip1.pdf' }}</p>
                            </div>
                        </div>

                        @if($pendaftaran->is_published && $pendaftaran->catatan_dosen)
                            <div class="mt-3 p-3 bg-blue-50/60 rounded-xl border border-blue-100 text-xs text-blue-900 max-w-lg">
                                <span class="font-bold">Catatan dosen:</span> {{ $pendaftaran->catatan_dosen }}
                            </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <div class="lg:self-center shrink-0">
                        <a href="{{ route('mahasiswa.status.show', $pendaftaran->id) }}" 
                           class="px-6 py-2.5 bg-[#1E5EFF] text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-xs inline-flex items-center gap-1.5">
                            <span>Lihat Detail</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
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
