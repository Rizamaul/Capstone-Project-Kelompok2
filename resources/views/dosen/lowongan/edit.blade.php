@extends('layouts.dosen')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    
    <!-- HEADER INFORMASI -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Edit Lowongan Rekrutmen Asisten Praktikum</h1>
            <p class="text-sm text-gray-500">
                Perbarui parameter lowongan, jadwal praktikum, kriteria seleksi otomatis, berkas pelamar, atau tenggat pendaftaran untuk mata kuliah Algoritma & Struktur Data.
            </p>
        </div>
        <button type="submit" form="form-edit-lowongan" class="px-5 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm whitespace-nowrap">
            💾 Simpan Perubahan
        </button>
    </div>

    <!-- FORM UTAMA -->
    <form id="form-edit-lowongan" action="#" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- SECTION 1: INFORMASI MATA KULIAH -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                <span class="text-lg">📖</span>
                <h2 class="text-sm font-bold text-gray-900">Informasi Mata Kuliah</h2>
            </div>
            <p class="text-xs text-gray-500 mb-4">Tentukan mata kuliah, kuota pelamar, dan Tenggat waktu pendaftaran yang di butuhkan.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kelas & Jadwal Pelaksanaan</label>
                    <select name="jadwal_kelas" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                        <option value="lab-2402" selected>Lab Komputer 2402 - Senin, 08:00 - 11:30</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Semester</label>
                    <select name="semester" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                        <option value="ganjil-2026" selected>Semester Ganjil 2026</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kuota Asisten Dosen yang Dibutuhkan</label>
                    <div class="flex items-center">
                        <button type="button" class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-gray-600 hover:bg-gray-200">-</button>
                        <input type="number" name="kuota" value="4" class="w-full text-center border-y border-gray-200 bg-gray-50 py-2 text-xs text-gray-800 focus:outline-none">
                        <button type="button" class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-200 rounded-r-xl text-gray-600 hover:bg-gray-200">+</button>
                        <span class="ml-2 text-xs text-gray-500 whitespace-nowrap">Mahasiswa</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tenggat Waktu</label>
                    <input type="text" name="tenggat" value="22 Oktober 2026, 08:00 WIB" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                </div>
            </div>
        </div>

        <!-- SECTION 2: KRITERIA & SYARAT SELEKSI -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                <span class="text-lg">🎯</span>
                <h2 class="text-sm font-bold text-gray-900">Kriteria & Syarat Seleksi</h2>
            </div>
            <p class="text-xs text-gray-500 mb-4">Tentukan standar minimum akademik dan kompetensi khusus kandidat.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nilai Minimum Mata Kuliah</label>
                    <select name="nilai_min" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                        <option value="A" selected>Min A</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Minimal IPK Kumulatif</label>
                    <input type="text" name="ipk_min" value="3.25" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                </div>
            </div>
        </div>

        <!-- TOMBOL AKSI BAWAH -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('dosen.lowongan.show', 1) }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors">
                Batalkan
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection