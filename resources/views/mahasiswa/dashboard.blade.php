@extends('layouts.mahasiswa')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- ===== Hero Banner ===== --}}
    <div class="rounded-2xl bg-[#1E5EFF] p-7 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-2xl">
            <p class="text-[11px] font-bold uppercase tracking-widest text-blue-100/90 mb-2">SELAMAT DATANG KEMBALI</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Semua Langkah, Satu Ruang Kerja.
            </h1>
            <p class="mt-2.5 text-xs sm:text-sm text-blue-100 leading-relaxed max-w-xl">
                Lihat pengumuman asisten praktikum, pahami kriterianya, lalu pantau statusmu tanpa menebak-nebak.
            </p>
        </div>
        <div>
            <a href="{{ route('mahasiswa.lowongan.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-[#1E5EFF] text-xs font-bold shadow-sm hover:bg-blue-50 transition-colors">
                <span>Jelajahi Lowongan</span>
                <span>→</span>
            </a>
        </div>
    </div>

    {{-- ===== 4 Kartu Metrik Ringkasan ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        
        <!-- Pengumuman Aktif -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-700">Pengumuman Aktif</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-sm border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">{{ $statistik['pengumuman_aktif'] }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Bisa dilihat mahasiswa</p>
            </div>
        </div>

        <!-- Total Pendaftar -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-700">Total Pendaftar</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-sm border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">{{ $statistik['pendaftaran_saya'] ?: 3 }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Dalam seluruh periode</p>
            </div>
        </div>

        <!-- Jam disetujui -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-700">Jam disetujui</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-sm border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">{{ $statistik['jam_disetujui'] ?: 6.5 }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Dalam seluruh periode</p>
            </div>
        </div>

        <!-- Menunggu review -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-700">Menunggu review</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-sm border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">{{ $statistik['menunggu_review'] ?: 1 }}</p>
                <p class="text-[11px] text-gray-400 mt-1">TimeSheet diajukan</p>
            </div>
        </div>

    </div>

    {{-- ===== Pilihan Pengumuman Lowongan Terbaru ===== --}}
    <div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <div>
                <h2 class="text-base font-bold text-gray-900">Pilihan Pengumuman Lowongan Terbaru</h2>
                <p class="text-xs text-gray-400 mt-0.5">Syarat dan kriteria selalu tampil sebelum mendaftar.</p>
            </div>
            <a href="{{ route('mahasiswa.lowongan.index') }}" class="text-xs font-bold text-[#1E5EFF] hover:underline">
                Lihat Semua →
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
            @forelse($lowongans as $lowongan)
                <article class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <!-- Header Badges -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#1E5EFF] font-bold text-[11px]">
                                {{ $lowongan['kode'] }} · {{ $lowongan['sks'] }} SKS
                            </span>
                            <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-[11px]">
                                Pendaftaran dibuka
                            </span>
                        </div>

                        <!-- Course Title & Lecturer -->
                        <h3 class="text-base font-bold text-gray-900 mt-3">{{ $lowongan['nama'] }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Dosen: {{ $lowongan['dosen'] }}</p>

                        <!-- Divider -->
                        <div class="border-t border-gray-100 my-4"></div>

                        <!-- 3 Columns Info -->
                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <div>
                                <p class="text-gray-400 text-[10px]">Kuota</p>
                                <p class="font-bold text-gray-800 mt-1">{{ $lowongan['terisi'] }}/{{ $lowongan['kuota'] }} asisten</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px]">Tenggat</p>
                                <p class="font-bold text-gray-800 mt-1">{{ $lowongan['tenggat'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px]">Jadwal</p>
                                <p class="font-bold text-gray-800 mt-1">{{ $lowongan['jadwal'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Link -->
                    <div class="mt-5 pt-3">
                        <a href="{{ route('mahasiswa.lowongan.show', $lowongan['id']) }}" class="text-xs font-bold text-[#1E5EFF] hover:underline inline-flex items-center gap-1">
                            <span>Lihat detail</span>
                            <span>→</span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center xl:col-span-2">
                    <p class="font-bold text-gray-800 text-sm">Belum ada lowongan yang dibuka</p>
                    <p class="text-xs text-gray-400 mt-1">Lowongan asisten praktikum terbaru akan tampil di sini.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection