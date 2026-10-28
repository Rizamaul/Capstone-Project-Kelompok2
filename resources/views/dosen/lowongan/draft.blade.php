@extends('layouts.dosen')

@section('title', 'Draft Lowongan - Portal Dosen')

@section('content')
<div class="max-w-7xl mx-auto pb-16">

    {{-- HERO BANNER --}}
    <div class="bg-white rounded-2xl border border-gray-200/80 p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-[#2058B2] tracking-tight">Draft Pengumuman Rekrutmen Asisten Dosen</h1>
            <p class="text-sm text-gray-500 mt-2 max-w-xl leading-relaxed">
                Lanjutkan pengisian form rekrutmen asisten praktikum, kelola lowongan
                yang belum di publikasikan atau jadwalkan publikasi lowongan.
            </p>
        </div>
        <a href="{{ route('dosen.lowongan.create') }}"
           class="bg-[#2058B2] text-white px-6 py-4 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/25 whitespace-nowrap flex items-center justify-center gap-2 shrink-0">
            <span class="text-lg leading-none">+</span>
            Buat Pengumuman Baru
        </a>
    </div>

    {{-- 4 KARTU STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Card 1: Total Draft --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between shadow-sm hover:border-blue-200 transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-800">Total Draft</span>
                <div class="p-2 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-4xl font-bold text-gray-900 tracking-tight">2</span>
                <span class="text-xs font-semibold text-gray-400">Mata Kuliah</span>
            </div>
            <a href="#" class="text-xs font-semibold text-[#1E5EFF] hover:underline">2 Matakuliah Masih terbuka</a>
        </div>

        {{-- Card 2: Siap Publikasi --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between shadow-sm hover:border-blue-200 transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-800">Siap Publikasi</span>
                <div class="p-2 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-4xl font-bold text-gray-900 tracking-tight">76</span>
                <span class="text-xs font-semibold text-gray-400">Mahasiswa</span>
            </div>
            <a href="#" class="text-xs font-semibold text-[#1E5EFF] hover:underline">Perlu verifikasi berkas</a>
        </div>

        {{-- Card 3: Kuota Terpenuhi --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between shadow-sm hover:border-blue-200 transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-800">n</span>
                <div class="p-2 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-1 mb-4">
                <span class="text-4xl font-bold text-gray-900 tracking-tight">4<span class="text-2xl text-gray-400 font-bold">/8</span></span>
                <span class="text-xs font-semibold text-gray-400 ml-1">Asisten</span>
            </div>
            <a href="#" class="text-xs font-semibold text-[#1E5EFF] hover:underline">Target kuota terpenuhi sedikit lagi</a>
        </div>

        {{-- Card 4: Berkas Masuk --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between shadow-sm hover:border-blue-200 transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-800">n</span>
                <div class="p-2 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-4xl font-bold text-gray-900 tracking-tight">26</span>
                <span class="text-xs font-semibold text-gray-400">Berkas Masuk</span>
            </div>
            <a href="#" class="text-xs font-semibold text-[#1E5EFF] hover:underline">Batas Verifikasi : 3 hari lagi</a>
        </div>

    </div>

    {{-- FILTER BAR --}}
    <div class="bg-white rounded-2xl border border-gray-200/80 p-4 mb-8 shadow-sm">
        <div class="flex items-center gap-3 w-full flex-wrap sm:flex-nowrap">

            {{-- Search Input --}}
            <div class="relative flex-1 min-w-[180px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       id="draft-search"
                       class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white placeholder-gray-400 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF]"
                       placeholder="Cari pendaftar, matakuliah, atau berkas lainnya...">
            </div>

            {{-- Dropdown Status --}}
            <div class="relative shrink-0" x-data="{ open: false, selected: 'Semua Status' }">
                <button @click="open = !open" @click.away="open = false" type="button"
                        class="flex items-center justify-between gap-2 px-4 py-2.5 bg-[#EBF1FF] border border-gray-200 rounded-xl text-sm font-medium text-[#1E5EFF] hover:bg-blue-100 focus:outline-none whitespace-nowrap">
                    <span x-text="selected"></span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="absolute left-0 mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-lg z-30 py-1" style="display: none;">
                    <a href="#" @click.prevent="selected = 'Semua Status'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Semua Status</a>
                    <a href="#" @click.prevent="selected = 'Draft'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Draft</a>
                    <a href="#" @click.prevent="selected = 'Siap Publikasi'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Siap Publikasi</a>
                    <a href="#" @click.prevent="selected = 'Terjadwal'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Terjadwal</a>
                </div>
            </div>

            {{-- Dropdown Semester --}}
            <div class="relative shrink-0" x-data="{ open: false, selected: 'Semua Semester' }">
                <button @click="open = !open" @click.away="open = false" type="button"
                        class="flex items-center justify-between gap-2 px-4 py-2.5 bg-[#EBF1FF] border border-gray-200 rounded-xl text-sm font-medium text-[#1E5EFF] hover:bg-blue-100 focus:outline-none whitespace-nowrap">
                    <span x-text="selected"></span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="absolute left-0 mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-lg z-30 py-1" style="display: none;">
                    <a href="#" @click.prevent="selected = 'Semua Semester'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Semua Semester</a>
                    <a href="#" @click.prevent="selected = 'Ganjil'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Ganjil</a>
                    <a href="#" @click.prevent="selected = 'Genap'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Genap</a>
                </div>
            </div>

            {{-- Dropdown Mata Kuliah --}}
            <div class="relative shrink-0" x-data="{ open: false, selected: 'Semua Matakuliah' }">
                <button @click="open = !open" @click.away="open = false" type="button"
                        class="flex items-center justify-between gap-2 px-4 py-2.5 bg-[#EBF1FF] border border-gray-200 rounded-xl text-sm font-medium text-[#1E5EFF] hover:bg-blue-100 focus:outline-none whitespace-nowrap">
                    <span x-text="selected"></span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="absolute left-0 mt-2 w-52 bg-white border border-gray-200 rounded-xl shadow-lg z-30 py-1" style="display: none;">
                    <a href="#" @click.prevent="selected = 'Semua Matakuliah'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Semua Matakuliah</a>
                    <a href="#" @click.prevent="selected = 'Algoritma Struktur Data'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Algoritma Struktur Data</a>
                    <a href="#" @click.prevent="selected = 'Jaringan Komputer'; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5EFF]">Jaringan Komputer</a>
                </div>
            </div>

            {{-- Tombol Eksport PDF --}}
            <button id="btn-export-pdf"
                    class="px-4 py-2.5 bg-[#EBF1FF] text-[#1E5EFF] border border-blue-100 rounded-xl text-sm font-semibold hover:bg-blue-100 transition-colors flex items-center gap-2 whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Eksport PDF
            </button>

            {{-- Tombol Refresh --}}
            <button id="btn-refresh"
                    class="p-2.5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors text-gray-600 shadow-xs shrink-0" title="Refresh Data">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>

        </div>
    </div>

    {{-- DAFTAR DRAFT LOWONGAN (Card Style) --}}
    <div id="draft-list" class="space-y-4">

        {{-- Draft Card 1: Algoritma Struktur Data --}}
        <div class="draft-card bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md hover:border-blue-200 transition-all p-6 md:p-8">
            {{-- Header Card --}}
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight mb-2">Algoritma Struktur Data</h2>
                    <div class="flex flex-wrap items-center gap-3 text-xs">
                        <span class="bg-[#EBF1FF] text-[#1E5EFF] px-2.5 py-1 rounded font-bold text-[11px] tracking-wide">KODE MK</span>
                        <span class="text-gray-400 font-semibold">Kelas 2402</span>
                        <span class="text-gray-300">|</span>
                        <span class="text-gray-400 font-semibold">3 SKS</span>
                    </div>
                </div>
                {{-- Delete Button --}}
                <button class="btn-delete p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors shrink-0"
                        title="Hapus draft">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>

            {{-- Timestamp --}}
            <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-5">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Terakhir disimpan: Hari ini, 13.50 WIB oleh Dosen Pemangku</span>
            </div>

            {{-- Kuota Info Box --}}
            <div class="bg-[#F0F6FF] border border-blue-100 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 mb-1.5">Kuota Asisten</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-gray-900">6</span>
                    <span class="text-sm text-gray-500 font-medium">Mahasiswa</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                <button class="flex items-center gap-2 px-4 py-2.5 bg-[#2058B2] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-md shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Lanjut Edit
                </button>
                <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview
                </button>
                <button class="flex items-center gap-2 px-4 py-2.5 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold hover:bg-green-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Publikasikan
                </button>
                <span class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    Draft
                </span>
            </div>
        </div>

        {{-- Draft Card 2: Basis Data --}}
        <div class="draft-card bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md hover:border-blue-200 transition-all p-6 md:p-8">
            {{-- Header Card --}}
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight mb-2">Basis Data</h2>
                    <div class="flex flex-wrap items-center gap-3 text-xs">
                        <span class="bg-[#EBF1FF] text-[#1E5EFF] px-2.5 py-1 rounded font-bold text-[11px] tracking-wide">KODE MK</span>
                        <span class="text-gray-400 font-semibold">Kelas 2401</span>
                        <span class="text-gray-300">|</span>
                        <span class="text-gray-400 font-semibold">2 SKS</span>
                    </div>
                </div>
                <button class="btn-delete p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors shrink-0" title="Hapus draft">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>

            {{-- Timestamp --}}
            <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-5">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Terakhir disimpan: Kemarin, 09.20 WIB oleh Dosen Pemangku</span>
            </div>

            {{-- Kuota Info Box --}}
            <div class="bg-[#F0F6FF] border border-blue-100 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 mb-1.5">Kuota Asisten</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-gray-900">4</span>
                    <span class="text-sm text-gray-500 font-medium">Mahasiswa</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                <button class="flex items-center gap-2 px-4 py-2.5 bg-[#2058B2] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-md shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Lanjut Edit
                </button>
                <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview
                </button>
                <button class="flex items-center gap-2 px-4 py-2.5 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold hover:bg-green-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Publikasikan
                </button>
                <span class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    Draft
                </span>
            </div>
        </div>

    </div>

    {{-- Empty State (hidden by default) --}}
    <div id="empty-state" class="hidden bg-white rounded-2xl border border-gray-200/80 shadow-sm p-16 text-center">
        <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 rounded-2xl flex items-center justify-center">
            <svg class="w-8 h-8 text-[#1E5EFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Tidak ada draft lowongan</h3>
        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Buat pengumuman rekrutmen baru dan simpan sebagai draft untuk dilanjutkan nanti.</p>
        <a href="{{ route('dosen.lowongan.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-[#2058B2] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/25">
            + Buat Pengumuman Baru
        </a>
    </div>

</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" id="modal-backdrop"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 transform transition-all">
        <div class="w-12 h-12 mx-auto mb-4 bg-red-50 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Hapus Draft?</h3>
        <p class="text-sm text-gray-500 text-center mb-6">Draft lowongan ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
        <div class="flex gap-3">
            <button id="cancel-delete" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                Batal
            </button>
            <button id="confirm-delete" class="flex-1 px-4 py-2.5 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition-colors">
                Hapus
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal    = document.getElementById('delete-modal');
        const cancelDelete   = document.getElementById('cancel-delete');
        const confirmDelete  = document.getElementById('confirm-delete');
        const modalBackdrop  = document.getElementById('modal-backdrop');
        let   targetCard     = null;

        // Buka modal konfirmasi hapus
        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                targetCard = btn.closest('.draft-card');
                deleteModal.classList.remove('hidden');
            });
        });

        // Tutup modal
        function closeModal() {
            deleteModal.classList.add('hidden');
            targetCard = null;
        }

        cancelDelete.addEventListener('click', closeModal);
        modalBackdrop.addEventListener('click', closeModal);

        // Konfirmasi hapus — animasi fade-out lalu hilangkan elemen
        confirmDelete.addEventListener('click', function () {
            if (targetCard) {
                targetCard.style.transition = 'opacity 0.3s, transform 0.3s';
                targetCard.style.opacity    = '0';
                targetCard.style.transform  = 'scale(0.97)';
                setTimeout(function () {
                    targetCard.remove();
                    // Tampilkan empty state jika tidak ada card lagi
                    const remaining = document.querySelectorAll('.draft-card');
                    if (remaining.length === 0) {
                        document.getElementById('draft-list').classList.add('hidden');
                        document.getElementById('empty-state').classList.remove('hidden');
                    }
                }, 300);
            }
            closeModal();
        });

        // Live search filter
        document.getElementById('draft-search').addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.draft-card').forEach(function (card) {
                const title = card.querySelector('h2').textContent.toLowerCase();
                card.style.display = title.includes(query) ? '' : 'none';
            });
        });

        // Refresh spin animation
        document.getElementById('btn-refresh').addEventListener('click', function () {
            const svg = this.querySelector('svg');
            svg.style.transition = 'transform 0.5s';
            svg.style.transform  = 'rotate(360deg)';
            setTimeout(function () {
                svg.style.transition = 'none';
                svg.style.transform  = 'rotate(0deg)';
            }, 500);
        });
    });
</script>
@endpush
@endsection
