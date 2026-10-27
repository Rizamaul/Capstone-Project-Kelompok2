@extends('layouts.dosen')

@section('content')
<div class="w-full pb-16">
    
    <!-- HERO BANNER TIMESHEET -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#2058B2] tracking-tight">Timesheet Asisten Praktikum Dosen</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau aktivitas asisten praktikum dosen secara real time dan terpusat</p>
        </div>
        <button type="button" class="bg-[#2058B2] text-white px-6 py-3.5 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/25 flex items-center gap-2 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Setujui Semua
        </button>
    </div>

    <!-- 4 KARTU STATISTIK TIMESHEET -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Disetujui -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-[#2058B2]">Disetujui</span>
                <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-lg border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">0</h3>
            <p class="text-xs text-gray-400 mt-1">Pengajuan</p>
            <p class="text-xs font-semibold text-emerald-600 mt-3 pt-3 border-t border-gray-100">Daftar disetujui</p>
        </div>
        <!-- Menunggu -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-[#2058B2]">Menunggu</span>
                <div class="p-2.5 bg-amber-50 text-amber-600 rounded-lg border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">2</h3>
            <p class="text-xs text-gray-400 mt-1">Pengajuan</p>
            <p class="text-xs font-semibold text-amber-600 mt-3 pt-3 border-t border-gray-100">Perlu tindakan dari dosen</p>
        </div>
        <!-- Total Jam -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-[#2058B2]">Total Jam</span>
                <div class="p-2.5 bg-blue-50 text-[#1E5EFF] rounded-lg border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">7</h3>
            <p class="text-xs text-gray-400 mt-1">Jam</p>
            <p class="text-xs font-semibold text-blue-600 mt-3 pt-3 border-t border-gray-100">Target 1 semester : 135 jam</p>
        </div>
        <!-- Total Daftar -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-[#2058B2]">Total Daftar</span>
                <div class="p-2.5 bg-purple-50 text-purple-600 rounded-lg border border-purple-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">2</h3>
            <p class="text-xs text-gray-400 mt-1">Daftar Masuk</p>
            <p class="text-xs font-semibold text-purple-600 mt-3 pt-3 border-t border-gray-100">Riwayat daftar tersimpan</p>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-4 mb-6 shadow-sm flex flex-wrap items-center gap-3 w-full">
        <div class="relative flex-1 min-w-[200px]">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white placeholder-gray-400 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF]" placeholder="Cari pendaftar, matakuliah, atau berkas lainnya....">
        </div>
        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
            <button class="px-3.5 py-2.5 border border-gray-200 rounded-xl bg-white text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 whitespace-nowrap">
                Semua Status <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <button class="px-3.5 py-2.5 border border-gray-200 rounded-xl bg-white text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 whitespace-nowrap">
                Semua Semester <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <button class="px-3.5 py-2.5 border border-gray-200 rounded-xl bg-white text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 whitespace-nowrap">
                Semua Matakuliah <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <button class="px-4 py-2.5 bg-[#EBF1FF] text-[#1E5EFF] border border-blue-100 rounded-xl text-xs sm:text-sm font-semibold hover:bg-blue-100 transition-colors flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Eksport PDF
            </button>
            <button class="p-2.5 border border-gray-200 rounded-xl bg-white text-gray-600 hover:bg-gray-50 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <!-- TABEL DAFTAR TIMESHEET ASISTEN -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 pb-6 flex items-center gap-4 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Daftar Timesheet Asisten</h2>
            <span class="px-3 py-1 bg-[#EBF1FF] text-[#1E5EFF] rounded-lg text-xs font-semibold">2 Ditampilkan</span>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Nama Asisten</th>
                        <th class="py-4 px-6">Kode & Mata Kuliah</th>
                        <th class="py-4 px-6">Tanggal & Waktu</th>
                        <th class="py-4 px-6">Aktivitas</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <!-- Baris 1 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#1E5EFF] border border-blue-100 flex items-center justify-center font-semibold">F</div>
                                <div>
                                    <p class="font-bold text-gray-900">Febian Eldriano</p>
                                    <p class="text-xs text-gray-400">NIM : 123456789</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-gray-900">Algoritma Struktur Data</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-[#EBF1FF] text-[#1E5EFF] rounded text-[10px] font-semibold">KODE MK</span>
                                <span class="text-xs text-gray-500">Kelas 2402 • 3 SKS</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-gray-900">26 Okt, 2026</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="text-xs text-gray-500">08.00 - 11.30 WIB</span>
                                <span class="px-2 py-0.5 bg-blue-50 text-[#1E5EFF] rounded text-[10px] font-semibold flex items-center gap-1">3.5 Jam</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-gray-900">Praktikum Modul 1</p>
                            <p class="text-xs text-gray-500 mt-0.5">Memaparkan semua materi pada modul 1</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Menunggu
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="/dosen/timesheet/detail" class="p-2 bg-blue-50 text-[#1E5EFF] rounded-xl hover:bg-blue-100 transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <button class="p-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-100 transition-colors" title="Setujui">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Baris 2 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#1E5EFF] border border-blue-100 flex items-center justify-center font-semibold">K</div>
                                <div>
                                    <p class="font-bold text-gray-900">Khairul Hilmi</p>
                                    <p class="text-xs text-gray-400">NIM : 128973407</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-gray-900">Algoritma Struktur Data</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-[#EBF1FF] text-[#1E5EFF] rounded text-[10px] font-semibold">KODE MK</span>
                                <span class="text-xs text-gray-500">Kelas 2402 • 3 SKS</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-gray-900">26 Okt, 2026</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="text-xs text-gray-500">08.00 - 11.30 WIB</span>
                                <span class="px-2 py-0.5 bg-blue-50 text-[#1E5EFF] rounded text-[10px] font-semibold flex items-center gap-1">3.5 Jam</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-gray-900">Mendampingi Prak</p>
                            <p class="text-xs text-gray-500 mt-0.5">Mendampingi sesi lab dan membantu memperbaiki error pada tugas.</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Menunggu
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="/dosen/timesheet/detail" class="p-2 bg-blue-50 text-[#1E5EFF] rounded-xl hover:bg-blue-100 transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <button class="p-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-100 transition-colors" title="Setujui">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Footer Tabel / Pagination -->
        <div class="p-6 border-t border-gray-100 flex items-center justify-between text-sm  font-semibold text-[#1E5EFF]">
            <span>Menampilkan 2 dari 22 daftar</span>
            <div class="flex items-center gap-2">
                <button class="p-2 border border-gray-200 rounded-xl hover:bg-gray-50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <button class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-900 font-bold hover:bg-gray-50 transition-colors shadow-sm">
                    1
                </button>                
                <button class="p-2 border border-gray-200 rounded-xl hover:bg-gray-50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
            </div>
        </div>
    </div>
</div>
@endsection