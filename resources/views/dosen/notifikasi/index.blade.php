@extends('layouts.dosen')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
    
    <!-- HEADER & AKSI -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Pusat Notifikasi & Pemberitahuan</h1>
            <p class="text-sm text-gray-500">Pantau semua pembaruan seleksi asisten praktikum dosen, timesheet baru, dan pengumuman resmi dekanat.</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="px-4 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-1.5 whitespace-nowrap">
                ✓ Tandai Semua Dibaca
            </button>
            <button class="p-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-colors">
                ⚙️
            </button>
        </div>
    </div>

    <!-- FILTER & PENCARIAN -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-4 shadow-sm space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-1">
                <input type="text" placeholder="Cari semua pengumuman..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
            </div>
            <div>
                <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                    <option>Urutan : Paling Baru</option>
                </select>
            </div>
            <div>
                <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                    <option>📅 Tanggal & Waktu</option>
                </select>
            </div>
            <div>
                <select class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                    <option>Semua Matakuliah</option>
                </select>
            </div>
        </div>

        <!-- TAB KATEGORI -->
        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100 text-xs">
            <button class="px-4 py-2 bg-[#1E5EFF] text-white rounded-xl font-semibold shadow-xs">Semua (2)</button>
            <button class="px-4 py-2 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl font-semibold hover:bg-gray-100">Belum Dibaca (2)</button>
            <button class="px-4 py-2 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl font-semibold hover:bg-gray-100">Tindakan & Persetujuan (1)</button>
            <button class="px-4 py-2 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl font-semibold hover:bg-gray-100">Rekrutmen Asisten Praktikum (1)</button>
        </div>
    </div>

    <!-- LIST NOTIFIKASI -->
    <div class="space-y-4">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">HARI INI • Selasa, 26 Oktober 2026</p>

        <!-- Notifikasi 1 -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-blue-50 text-[#1E5EFF] rounded-lg text-xs font-semibold flex items-center gap-1.5">
                        👥 Rekrutmen Asisten Praktikum
                    </span>
                    <span class="px-3 py-1 bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-xs font-medium">
                        KODE MK - Algoritma Struktur Data
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">1 Menit Lalu</span>
                    <span class="w-2 h-2 rounded-full bg-[#1E5EFF]"></span>
                </div>
            </div>

            <div>
                <h3 class="text-base font-bold text-gray-900 mb-1">Pelamar Baru Mengunggah Berkas Pendaftaran</h3>
                <p class="text-xs text-gray-600">
                    <span class="font-bold text-gray-800">Khairul Hilmi (128973407)</span> telah menyelesaikan pendaftaran calon asisten praktikum pada matakuliah Algoritma Struktur Data dengan <span class="font-bold text-blue-600">IPK 3.94</span> dan nilai matakuliah prasyarat <span class="font-bold text-emerald-600">Nilai A</span>.
                </p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center font-bold text-gray-600">
                        👤
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Rizal Hidayat (128973357)</p>
                        <p class="text-[10px] text-gray-400">Nama_File_Persyaratan.pdf | Nama_File_Persyaratan.pdf</p>
                    </div>
                </div>
                <a href="#" class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold hover:bg-blue-100">→</a>
            </div>
        </div>

        <!-- Notifikasi 2 -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-semibold flex items-center gap-1.5">
                        ⏳ Tindakan & persetujuan
                    </span>
                    <span class="px-3 py-1 bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-xs font-medium">
                        KODE MK - Algoritma Struktur Data
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">5 Menit Lalu</span>
                    <span class="w-2 h-2 rounded-full bg-[#1E5EFF]"></span>
                </div>
            </div>

            <div>
                <h3 class="text-base font-bold text-gray-900 mb-1">Pengajuan Timesheet Baru Perlu Verifikasi</h3>
                <p class="text-xs text-gray-600">
                    <span class="font-bold text-gray-800">Khairul Hilmi (128973407)</span> mengajukan presensi asisten praktikum melalui pengiriman aktivitas pada Praktikum Modul 1 dengan total durasi asistensi <span class="font-bold text-blue-600">3.5 Jam</span>.
                </p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="space-y-1 text-gray-600">
                    <p class="flex items-center gap-1.5 font-medium text-gray-800">📅 Sesi : 26 Oktober 2026 (08.00 - 11.30)</p>
                    <p class="flex items-center gap-1.5 text-gray-500">📍 Kelas : Lab Komputer 2402</p>
                </div>
                <a href="{{ route('dosen.timesheet.show', 1) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors text-center shadow-xs">
                    👁️ Lihat Detail Sesi
                </a>
            </div>
        </div>

    </div>
</div>
@endsection