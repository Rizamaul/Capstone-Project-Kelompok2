@extends('layouts.mahasiswa')
@section('title', 'Detail Timesheet')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- KEMBALI -->
    <div>
        <a href="{{ route('mahasiswa.timesheet') }}" class="text-xs font-semibold text-[#1E5EFF] hover:underline inline-flex items-center gap-1">
            <span>← Kembali ke Riwayat Timesheet</span>
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800 shadow-xs flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- HEADER DETAIL & TOMBOL EDIT -->
    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 mb-1">Detail Timesheet Asistensi Praktikum</h1>
            <p class="text-xs text-gray-500">Periksa rincian kegiatan, jadwal asistensi, durasi jam kerja, dan status verifikasi dosen pengampu.</p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.timesheet.edit', $entry->id) }}" 
               class="px-5 py-2.5 bg-[#1E5EFF] hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-colors shadow-xs inline-flex items-center gap-2 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Timesheet</span>
            </a>
        </div>
    </div>

    <!-- KARTU RINCIAN -->
    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-5 text-xs">

        <!-- Info Asisten / Mahasiswa & Status -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold text-sm border border-blue-100">
                    {{ strtoupper(substr($entry->asisten?->name ?? 'M', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $entry->asisten?->name ?? 'Nama Mahasiswa Aktif' }}</p>
                    <p class="text-gray-400">NIM : {{ $entry->asisten?->nim ?? '105223045' }}</p>
                </div>
            </div>
            
            @php
                $statusApproval = $entry->status_approval;
                $badgeStyle = match ($statusApproval) {
                    'approved' => 'bg-[#EBF9F1] text-[#22C55E]',
                    'rejected' => 'bg-[#FEECEC] text-[#EF4444]',
                    default => 'bg-amber-50 text-amber-700'
                };
                $badgeText = match ($statusApproval) {
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => 'Menunggu'
                };
            @endphp
            <div class="flex items-center gap-2">
                <a href="{{ route('mahasiswa.timesheet.edit', $entry->id) }}" 
                   class="px-3.5 py-1.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300 font-bold text-xs inline-flex items-center gap-1.5 transition-colors cursor-pointer"
                   title="Edit Timesheet">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <span>Edit</span>
                </a>
                <span class="px-3.5 py-1.5 rounded-full font-bold text-xs flex items-center gap-1.5 {{ $badgeStyle }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $statusApproval === 'approved' ? 'bg-[#22C55E]' : ($statusApproval === 'rejected' ? 'bg-[#EF4444]' : 'bg-amber-500') }}"></span>
                    <span>{{ $badgeText }}</span>
                </span>
            </div>
        </div>

        <!-- Detail Data Grid -->
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Mata Kuliah</span>
            <span class="font-semibold text-gray-800 text-right">{{ $courseName }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Kode Mata Kuliah</span>
            <span class="font-mono font-semibold text-gray-800 text-right">{{ $code }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Kelas</span>
            <span class="font-semibold text-gray-800 text-right">2402</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Total SKS</span>
            <span class="font-semibold text-gray-800 text-right">3 SKS</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Tanggal Aktivitas</span>
            <span class="font-semibold text-gray-800 text-right">{{ $entry->tanggal->translatedFormat('d M, Y') }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Jumlah Jam</span>
            <span class="font-semibold text-[#1E5EFF] text-right">{{ number_format((float) $entry->jumlah_jam, 1, ',', '.') }} Jam</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Aktivitas Utama</span>
            <span class="font-semibold text-gray-800 text-right">{{ $entry->aktivitas ?: 'Mendampingi Praktikum' }}</span>
        </div>

        <!-- Deskripsi Pekerjaan -->
        <div class="pt-2">
            <p class="font-bold text-gray-800 mb-2">Uraian / Deskripsi Pekerjaan</p>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-gray-700 leading-relaxed whitespace-pre-line">
{{ $entry->uraian_pekerjaan }}
            </div>
        </div>

        <!-- KETERANGAN ALASAN DITOLAK -->
        @if($entry->status_approval === 'rejected' || $entry->catatan_revisi)
            <div class="mt-6 rounded-2xl border border-red-200 bg-[#FFF5F5] p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-base">⚠️</span>
                        <h3 class="font-bold text-red-900 text-sm">Keterangan Alasan Ditolak</h3>
                    </div>
                    <a href="{{ route('mahasiswa.timesheet.edit', $entry->id) }}" class="text-xs font-bold text-red-700 hover:text-red-900 underline">
                        Perbaiki Sekarang →
                    </a>
                </div>
                <div class="bg-white p-4 rounded-xl border border-red-100 text-xs text-red-900 leading-relaxed whitespace-pre-line shadow-2xs">
{{ $entry->catatan_revisi ?: 'Hasil pemeriksaan laporan kurang lengkap dan format rangkuman pertanyaan belum sesuai.' }}
                </div>
                <p class="text-[11px] text-red-700 leading-normal">
                    Silakan perbaiki data jam kerja atau hubungi dosen pengampu jika membutuhkan klarifikasi lebih lanjut.
                </p>
            </div>
        @endif

    </div>

</div>
@endsection
