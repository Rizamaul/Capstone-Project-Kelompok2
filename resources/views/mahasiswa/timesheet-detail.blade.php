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

    <!-- HEADER DETAIL -->
    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Detail Timesheet Asistensi Praktikum</h1>
        <p class="text-xs text-gray-500">Periksa rincian kegiatan, jadwal asistensi, durasi jam kerja, dan status verifikasi dosen pengampu.</p>
    </div>

    <!-- KARTU RINCIAN (MENYESUAIKAN TAMPILAN DOSEN) -->
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
                    'approved' => 'bg-emerald-50 text-emerald-700',
                    'rejected' => 'bg-red-50 text-red-700',
                    default => 'bg-amber-50 text-amber-700'
                };
                $badgeText = match ($statusApproval) {
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => 'Menunggu'
                };
            @endphp
            <span class="px-3.5 py-1.5 rounded-full font-bold text-xs flex items-center gap-1.5 {{ $badgeStyle }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $statusApproval === 'approved' ? 'bg-emerald-500' : ($statusApproval === 'rejected' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                <span>{{ $badgeText }}</span>
            </span>
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

        <!-- KETERANGAN ALASAN DITOLAK (JIKA DITOLAK ATAU ADA CATATAN REVISI) -->
        @if($entry->status_approval === 'rejected' || $entry->catatan_revisi)
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50/70 p-5 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <h3 class="font-bold text-red-900 text-sm">Keterangan Alasan Ditolak</h3>
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
