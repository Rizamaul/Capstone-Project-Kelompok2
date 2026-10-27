@extends('layouts.dosen')

@section('content')
<div class="max-w-7xl mx-auto pb-16">
    
    <!-- HERO BANNER (Kelola Pengumuman) -->
   <div class="bg-white rounded-2xl border border-gray-200/80 p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div>
            <!-- Judul dibuat warna biru  -->
            <h1 class="text-2xl font-bold text-[#2058B2] tracking-tight">Kelola Pengumuman Rekrutmen Asisten Dosen</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status pendaftaran pelamar masuk dan kelola status buka atau tutup pada lowongan secara terpusat</p>
        </div>
        <!-- Tombol disesuaikan ukuran padding, rounded-xl, dan warna birunya -->
        <a href="/dosen/lowongan/create" class="bg-[#2058B2] text-white px-6 py-4 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/25 whitespace-nowrap flex items-center justify-center">
            + Buat Pengumuman Baru
        </a>
    </div>

    <!-- 4 KARTU STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Pengumuman Aktif -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between shadow-sm hover:border-blue-200 transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-800">Pengumuman Aktif</span>
                <div class="p-2 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-4xl font-bold text-gray-900 tracking-tight">2</span>
                <span class="text-xs font-semibold text-gray-400">Mata Kuliah</span>
            </div>
            <a href="#" class="text-xs font-semibold text-[#1E5EFF] hover:underline">2 Matakuliah Masih terbuka</a>
        </div>

        <!-- Card 2: Total Pendaftar -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between shadow-sm hover:border-blue-200 transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-800">Total Pendaftar</span>
                <div class="p-2 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-4xl font-bold text-gray-900 tracking-tight">76</span>
                <span class="text-xs font-semibold text-gray-400">Mahasiswa</span>
            </div>
            <a href="#" class="text-xs font-semibold text-[#1E5EFF] hover:underline">Perlu verifikasi berkas</a>
        </div>

        <!-- Card 3: Kuota Terpenuhi -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between shadow-sm hover:border-blue-200 transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-800">Kuota Terpenuhi</span>
                <div class="p-2 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-1 mb-4">
                <span class="text-4xl font-bold text-gray-900 tracking-tight">4<span class="text-2xl text-gray-400 font-bold">/8</span></span>
                <span class="text-xs font-semibold text-gray-400 ml-1">Asisten</span>
            </div>
            <a href="#" class="text-xs font-semibold text-[#1E5EFF] hover:underline">Target kuota terpenuhi sedikit lagi</a>
        </div>

        <!-- Card 4: Review Berkas -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between shadow-sm hover:border-blue-200 transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-800">Review Berkas</span>
                <div class="p-2 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-4xl font-bold text-gray-900 tracking-tight">26</span>
                <span class="text-xs font-semibold text-gray-400">Berkas Masuk</span>
            </div>
            <a href="#" class="text-xs font-semibold text-[#1E5EFF] hover:underline">Batas Verifikasi : 3 hari lagi</a>
        </div>

    </div>

    <!-- FILTER BAR -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-4 mb-8 shadow-sm flex flex-wrap items-center gap-3 w-full">        
        <!-- Search Input -->
       <div class="relative flex-1 min-w-[200px]">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white placeholder-gray-400 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF]" placeholder="Cari pendaftar, matakuliah...">
        </div>

        <!-- Dropdowns -->
      <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
        <button class="px-3.5 py-2.5 border border-gray-200 rounded-xl bg-white text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 whitespace-nowrap">
            Semua Status
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>
        
        <button class="px-3.5 py-2.5 border border-gray-200 rounded-xl bg-white text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 whitespace-nowrap">
            Semua Semester
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <button class="px-3.5 py-2.5 border border-gray-200 rounded-xl bg-white text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 whitespace-nowrap">
            Semua Matakuliah
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <!-- Eksport PDF -->
        <button class="px-4 py-2.5 bg-[#EBF1FF] text-[#1E5EFF] border border-blue-100 rounded-xl text-xs sm:text-sm font-semibold hover:bg-blue-100 transition-colors flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
                Eksport PDF
        </button>


        <!-- Refresh -->
        <button class="p-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        </button>
        </div>
    </div>    

    <!-- TABEL REKRUTMEN ASISTEN PRAKTIKUM -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        
        <!-- Table Title Header -->
        <div class="p-6 md:p-8 pb-6 flex items-center gap-4 border-b border-gray-100">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">Daftar Rekrutmen Asisten Praktikum</h2>
            <span class="px-3 py-1 bg-[#EBF1FF] text-[#1E5EFF] rounded-lg text-xs font-semibold">2 Mata Kuliah</span>
        </div>

        <!-- Table Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F0F6FF] border-b border-gray-200 text-[#2058B2] text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 md:px-8">Nama Mata Kuliah & Kode</th>
                        <th class="py-4 px-6">Kuota</th>
                        <th class="py-4 px-6">Pelamar Terdaftar</th>
                        <th class="py-4 px-6">Tenggat Pendaftaran</th>
                        <th class="py-4 px-6">Status Lowongan</th>
                        <th class="py-4 px-6">Buka atau Tutup</th>
                        <th class="py-4 px-6 md:px-8">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    
                    <!-- Row 1 -->
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="py-5 px-6 md:px-8">
                            <p class="font-bold text-gray-900 text-base mb-1">Algoritma Struktur Data</p>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="bg-[#EBF1FF] text-[#1E5EFF] px-2 py-0.5 rounded font-bold text-[10px]">KODE MK</span>
                                <span class="text-gray-400 font-semibold">Kelas 2402 <span class="mx-1">•</span> 3 SKS</span>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <p class="font-bold text-gray-900 text-base">3<span class="text-gray-400 font-semibold text-sm">/4 Asisten</span></p>
                        </td>
                        <td class="py-5 px-6">
                            <p class="font-bold text-gray-900 text-sm">38 Pelamar</p>
                            <p class="text-[11px] text-gray-400 font-semibold mt-0.5">19 Belum Terverifikasi</p>
                        </td>
                        <td class="py-5 px-6">
                            <p class="font-bold text-gray-900 text-sm">22 Okt, 2026</p>
                            <p class="text-[11px] text-[#1E5EFF] font-semibold mt-0.5">Sisa Beberapa Jam Lagi</p>
                        </td>
                        <td class="py-5 px-6">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-[#EBF1FF] text-[#1E5EFF] text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#1E5EFF]"></span>
                                Pendaftaran Dibuka
                            </span>
                        </td>
                        <td class="py-5 px-6">
                            <div class="w-11 h-6 bg-[#1E5EFF] rounded-full relative cursor-pointer shadow-inner flex items-center px-0.5 justify-end">
                                <div class="w-5 h-5 bg-white rounded-full shadow-md"></div>
                            </div>
                        </td>
                        <td class="py-5 px-6 md:px-8">
                            <div class="flex items-center gap-3">
                                <button class="p-2 text-[#1E5EFF] bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button class="p-2 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="py-5 px-6 md:px-8">
                            <p class="font-bold text-gray-900 text-base mb-1">Jaringan Komputer</p>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="bg-[#EBF1FF] text-[#1E5EFF] px-2 py-0.5 rounded font-bold text-[10px]">KODE MK</span>
                                <span class="text-gray-400 font-semibold">Kelas 2402 <span class="mx-1">•</span> 3 SKS</span>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <p class="font-bold text-gray-900 text-base">1<span class="text-gray-400 font-semibold text-sm">/4 Asisten</span></p>
                        </td>
                        <td class="py-5 px-6">
                            <p class="font-bold text-gray-900 text-sm">38 Pelamar</p>
                            <p class="text-[11px] text-gray-400 font-semibold mt-0.5">19 Belum Terverifikasi</p>
                        </td>
                        <td class="py-5 px-6">
                            <p class="font-bold text-gray-900 text-sm">22 Okt, 2026</p>
                            <p class="text-[11px] text-[#1E5EFF] font-semibold mt-0.5">Sisa Beberapa Jam Lagi</p>
                        </td>
                        <td class="py-5 px-6">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-[#EBF1FF] text-[#1E5EFF] text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#1E5EFF]"></span>
                                Pendaftaran Dibuka
                            </span>
                        </td>
                        <td class="py-5 px-6">
                            <div class="w-11 h-6 bg-[#1E5EFF] rounded-full relative cursor-pointer shadow-inner flex items-center px-0.5 justify-end">
                                <div class="w-5 h-5 bg-white rounded-full shadow-md"></div>
                            </div>
                        </td>
                        <td class="py-5 px-6 md:px-8">
                            <div class="flex items-center gap-3">
                                <button class="p-2 text-[#1E5EFF] bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button class="p-2 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="bg-[#F8FAFC] border-t border-gray-200 p-4 px-6 md:px-8 flex items-center justify-between">
            <span class="text-[#1E5EFF] text-sm font-semibold">Menampilkan 2 dari 22 daftar</span>
            
            <div class="flex items-center gap-2">
                <button class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-900 font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                    1
                </button>
                <button class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-[#1E5EFF] hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
        
    </div>
</div>
@endsection