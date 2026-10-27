@extends('layouts.dosen')

@section('content')
<div class="w-full pb-16">
    
    <!-- HEADER KEMBALI & JUDUL -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-sm mb-6">
        <a href="/dosen/timesheet" class="inline-flex items-center gap-2 text-sm font-semibold text-[#1E5EFF] hover:underline mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <h1 class="text-xl sm:text-2xl font-bold text-[#2058B2] tracking-tight">Detail Timesheet Asisten Praktikum Dosen</h1>
        <p class="text-sm text-gray-500 mt-1">Periksa detail nama, tanggal, jumlah jam, Mata kuliah, dan aktivitas sebelum mengambil keputusan.</p>
    </div>

    <!-- KARTU INFORMASI DETAIL TIMESHEET -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden p-6 md:p-8">
        
        <!-- Info Mahasiswa & Status -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 mb-6 border-b border-gray-100 gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-[#1E5EFF] border border-blue-100 flex items-center justify-center text-lg font-semibold">K</div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Khairul Hilmi</h3>
                    <p class="text-xs text-gray-400">NIM : 128973407</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                <span class="w-2 h-2 rounded-lg bg-amber-500"></span>
                Menunggu
            </span>
        </div>

        <!-- Detail Baris Informasi -->
        <div class="divide-y divide-gray-100 text-sm">
            <div class="py-4 flex justify-between items-center">
                <span class="text-gray-500 font-medium">Mata Kuliah</span>
                <span class="font text-gray-900">Algoritma Struktur Data</span>
            </div>
            <div class="py-4 flex justify-between items-center">
                <span class="text-gray-500 font-medium">Kode Mata Kuliah</span>
                <span class="font text-gray-900">Kode MK</span>
            </div>
            <div class="py-4 flex justify-between items-center">
                <span class="text-gray-500 font-medium">Kelas</span>
                <span class="font text-gray-900">2402</span>
            </div>
            <div class="py-4 flex justify-between items-center">
                <span class="text-gray-500 font-medium">Total SKS</span>
                <span class="font text-gray-900">3 SKS</span>
            </div>
            <div class="py-4 flex justify-between items-center">
                <span class="text-gray-500 font-medium">Tanggal Aktivitas</span>
                <span class="font text-gray-900">26 Okt, 2026</span>
            </div>
            <div class="py-4 flex justify-between items-center">
                <span class="text-gray-500 font-medium">Jumlah Jam</span>
                <span class="font text-gray-900">3.5 Jam</span>
            </div>
        </div>

        <!-- Aktivitas Pekerjaan -->
        <div class="mt-8 pt-6 border-t border-gray-100">
            <h4 class="text-base font-bold text-gray-900 mb-4">Aktivitas Pekerjaan</h4>
            <div class="border border-[#1E5EFF] bg-[#F8FAFC] rounded-2xl p-5 shadow-xs">
                <h5 class="text-sm font-semibold text-gray-900 mb-1">Mendampingi Praktikum</h5>
                <p class="text-sm text-gray-500">Mendampingi sesi lab dan membantu mahasiswa memperbaiki error pada tugas.</p>
            </div>
        </div>

        <!-- Tombol Aksi Persetujuan -->
        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
            <button type="button" class="px-6 py-3 border border-red-200 text-red-600 bg-red-50/50 hover:bg-red-100 rounded-lg text-sm font-bold transition-colors">
                Tolak Pengajuan
            </button>
            <button type="button" class="px-6 py-3 bg-[#1E5EFF] hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-colors shadow-lg shadow-blue-500/25">
                Setujui Timesheet
            </button>
        </div>

    </div>
</div>
@endsection