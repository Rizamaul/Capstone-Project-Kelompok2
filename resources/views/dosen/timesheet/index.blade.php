@extends('layouts.dosen')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
    
    <!-- HEADER -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Timesheet Asisten Praktikum Dosen</h1>
            <p class="text-sm text-gray-500">Pantau aktivitas asisten praktikum dosen secara real time dan terpusat</p>
        </div>
        <button class="px-5 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm whitespace-nowrap">
            ✓ Setujui Semua
        </button>
    </div>

    <!-- STATISTIK KARTU -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs font-semibold text-gray-500">Disetujui</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">✓</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">0</h3>
            <p class="text-xs text-emerald-600 font-medium">Daftar disetujui</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs font-semibold text-gray-500">Menunggu</span>
                <span class="p-2 bg-amber-50 text-amber-600 rounded-xl">⏳</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">2</h3>
            <p class="text-xs text-amber-600 font-medium">Perlu tindakan dari dosen</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs font-semibold text-gray-500">Total Jam</span>
                <span class="p-2 bg-blue-50 text-[#1E5EFF] rounded-xl">🕒</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">7 <span class="text-sm font-normal text-gray-500">Jam</span></h3>
            <p class="text-xs text-gray-500">Perminggu : 10j | Bulan : 40j</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs font-semibold text-gray-500">Estimasi Honor</span>
                <span class="p-2 bg-purple-50 text-purple-600 rounded-xl">💰</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Rp. 245.000</h3>
            <p class="text-xs text-gray-500">SK Dekanat 2026 : <span class="text-gray-700 font-medium">Rp. 35.000 / Jam</span></p>
        </div>
    </div>

    <!-- TABEL DAFTAR TIMESHEET -->
    <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-sm">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">Daftar Timesheet Asisten</h3>
            <span class="px-3 py-1 bg-blue-50 text-[#1E5EFF] rounded-lg text-xs font-semibold">2 Ditampilkan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/75 text-gray-400 font-semibold border-b border-gray-100">
                        <th class="py-3 px-4">Nama Asisten</th>
                        <th class="py-3 px-4">Kode & Mata Kuliah</th>
                        <th class="py-3 px-4">Tanggal & Waktu</th>
                        <th class="py-3 px-4">Aktivitas</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <tr>
                        <td class="py-3 px-4 font-semibold">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold">F</span>
                                <div>
                                    <p class="text-gray-900 font-bold">Febian Eldriano</p>
                                    <p class="text-[10px] text-gray-400">NIM : 123456789</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-bold text-gray-800">Algoritma & Struktur Data</p>
                            <span class="px-2 py-0.5 bg-blue-50 text-[#1E5EFF] rounded text-[10px]">IK2402</span>
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-semibold text-gray-800">26 Okt, 2026</p>
                            <p class="text-[10px] text-gray-400">08.00 - 11.30 WIB (3.5 Jam)</p>
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-semibold">Praktikum Modul 1</p>
                            <p class="text-[10px] text-gray-400 truncate max-w-xs">Memaparkan semua materi pada modul 1</p>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-full font-medium text-[10px]">Menunggu</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('dosen.timesheet.show', 1) }}" class="w-7 h-7 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100">✕</a>
                                <a href="{{ route('dosen.timesheet.show', 1) }}" class="w-7 h-7 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-100">✓</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection