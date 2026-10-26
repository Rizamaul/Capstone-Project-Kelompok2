@extends('layouts.dosen')

@section('content')
<!-- Lebar container disamakan menggunakan max-w-7xl mx-auto agar selaras dengan dashboard -->
<div class="max-w-7xl mx-auto pb-16">
    
    <!-- ========================================== -->
    <!-- HEADER HALAMAN & TOMBOL SIMPAN DRAFT -->
    <!-- ========================================== -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-[#1E293B] tracking-tight">Buat Lowongan Baru untuk Asisten Praktikum Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Silahkan isi formulir berikut untuk membuka pendaftaran asisten praktikum baru.</p>
        </div>
        <!-- Tombol Simpan Draft -->
        <button type="button" class="bg-[#1E5EFF] text-white px-6 py-3.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/25 flex items-center gap-2.5 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            Simpan Draft
        </button>
    </div>

    <form action="#" method="POST" class="space-y-6">
        
        <!-- ========================================== -->
        <!-- 1. INFORMASI MATA KULIAH -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden p-6 md:p-8">
            <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-[#1E5EFF] shadow-xs shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Informasi Mata Kuliah</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Tentukan mata kuliah, Kuota pelamar dan Tenggat waktu pendaftaran yang di butuhkan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mata Kuliah Diampu</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF] bg-white">
                        <option>Pilih Mata Kuliah</option>
                        <option>Algoritma Struktur Data</option>
                        <option>Jaringan Komputer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kode Mata Kuliah</label>
                    <input type="text" placeholder="Masukan Kode Mata Kuliah" class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF]">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tempat (Kelas)</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF] bg-white">
                        <option>Pilih Kelas</option>
                        <option>Kelas 2402</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Semester</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF] bg-white">
                        <option>Pilih Semester</option>
                        <option>Ganjil 2026</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kuota Asisten Dosen yang Dibutuhkan</label>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden w-36 bg-white">
                            <button type="button" class="w-11 h-12 bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 border-r border-gray-200 font-bold">-</button>
                            <input type="text" value="0" class="w-full h-12 text-center font-bold text-gray-800 border-none focus:ring-0 p-0 text-sm">
                            <button type="button" class="w-11 h-12 bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 border-l border-gray-200 font-bold">+</button>
                        </div>
                        <span class="text-sm text-gray-500 font-semibold">Mahasiswa</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tenggat Waktu</label>
                    <input type="datetime-local" class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF]">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. KRITERIA & SYARAT SELEKSI -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden p-6 md:p-8">
            <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-[#1E5EFF] shadow-xs shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Kriteria & Syarat Seleksi</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Tentukan standar minimum akademik dan kompetensi khusus kandidat.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nilai Minimum Mata Kuliah</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 bg-white">
                        <option>Pilih Nilai Minimum Mata Kuliah</option>
                        <option>A</option>
                        <option>AB</option>
                        <option>B</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Minimal IPK Kumulatif</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 bg-white">
                        <option>Pilih Minimal IPK Kumulatif</option>
                        <option>3.00</option>
                        <option>3.25</option>
                        <option>3.50</option>
                    </select>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-bold text-gray-700">Syarat Tambahan</label>
                    <button type="button" class="text-sm font-bold text-[#1E5EFF] hover:underline">+ Tambah Syarat</button>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 border border-gray-200 bg-[#F8FAFC] rounded-xl p-3.5">
                        <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <input type="text" value="Syarat Tambahan 1" class="flex-1 bg-transparent border-none text-sm font-bold text-gray-800 focus:ring-0 p-0">
                        <button type="button" class="text-gray-400 hover:text-red-500 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 3. MANFAAT & BENEFIT ASISTEN -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden p-6 md:p-8">
            <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-[#1E5EFF] shadow-xs shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900">Manfaat & Benefit Asisten</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Masukan manfaat & benefit untuk asisten praktikum yang di inginkan.</p>
                </div>
                <button type="button" class="text-sm font-bold text-[#1E5EFF] hover:underline whitespace-nowrap">+ Tambah Manfaat & Benefit</button>
            </div>

            <div class="space-y-3">
                <div class="flex items-center gap-3 border border-gray-200 bg-[#F8FAFC] rounded-xl p-3.5">
                    <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-800">Konversi SKS</p>
                        <p class="text-xs text-gray-500">Dapat ditukarkan menjadi 2 SKS yang anda inginkan</p>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-red-500 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 4. DAFTAR DOKUMEN YANG DIMINTA -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden p-6 md:p-8">
            <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-[#1E5EFF] shadow-xs shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar Dokumen yang Diminta</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Format berkas dokumen yang wajib diunggah mahasiswa pada formulir lamaran.</p>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between border border-gray-200 bg-[#F8FAFC] rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg border border-gray-200 text-[#1E5EFF]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">Transkrip Nilai Akademik Terakhir</p>
                            <p class="text-xs text-gray-500">memuat nilai mata kuliah prasyarat secara sah & terverifikasi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 shadow-xs">Format : PDF</span>
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FOOTER SUBMIT BUTTONS -->
        <!-- ========================================== -->
        <div class="flex justify-end items-center gap-4 pt-2">
            <button type="button" class="px-6 py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-xs">
                Batalkan
            </button>
            <button type="submit" class="px-8 py-3.5 bg-[#1E5EFF] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Publikasikan
            </button>
        </div>

    </form>
</div>
@endsection