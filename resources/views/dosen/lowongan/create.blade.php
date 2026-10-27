@extends('layouts.dosen')

@section('content')
<div class="max-w-7xl mx-auto pb-16">
    
    <!-- HEADER HALAMAN & TOMBOL SIMPAN DRAFT -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-[#2058B2] tracking-tight">Buat Lowongan Baru untuk Asisten Praktikum Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Silahkan isi formulir berikut untuk membuka pendaftaran asisten praktikum baru.</p>
        </div>
       <!-- Tombol Simpan Draft  -->
        <button type="button" class="bg-[#2058B2] text-white px-4 py-5 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/25 flex items-center gap-2.5 whitespace-nowrap">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3.5V7h3.5"></path>
            </svg>
            Simpan Draft
        </button>
    </div>

    <form action="#" method="POST" class="space-y-6">
        
        <!-- 1. INFORMASI MATA KULIAH -->
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mata Kuliah Diampu</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF] bg-white">
                        <option>Pilih Mata Kuliah</option>
                        <option>Algoritma Struktur Data</option>
                        <option>Jaringan Komputer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Mata Kuliah</label>
                    <input type="text" placeholder="Masukan Kode Mata Kuliah" class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat (Kelas)</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF] bg-white">
                        <option>Pilih Kelas</option>
                        <option>Kelas 2402</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF] bg-white">
                        <option>Pilih Semester</option>
                        <option>Ganjil 2026</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kuota Asisten Dosen yang Dibutuhkan</label>
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tenggat Waktu</label>
                    <input type="datetime-local" class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF]">
                </div>
            </div>
        </div>

        <!-- 2. KRITERIA & SYARAT SELEKSI -->
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai Minimum Mata Kuliah</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 bg-white">
                        <option>Pilih Nilai Minimum Mata Kuliah</option>
                        <option>A</option>
                        <option>AB</option>
                        <option>B</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Minimal IPK Kumulatif</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/20 bg-white">
                        <option>Pilih Minimal IPK Kumulatif</option>
                        <option>3.00</option>
                        <option>3.25</option>
                        <option>3.50</option>
                    </select>
                </div>
            </div>
            
          <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden p-6 md:p-8 mt-8">
            <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">Syarat Tambahan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Tambahkan ketentuan khusus atau syarat tambahan bagi pelamar.</p>
                </div>
                <button type="button" class="text-sm font-semibold text-[#1E5EFF] hover:underline whitespace-nowrap">+ Tambah Syarat</button>
            </div>

            <div class="space-y-3">
                <!-- Item Syarat Tambahan 1 -->
                <div class="flex items-center justify-between gap-4 border border-[#1E5EFF] bg-[#F8FAFC] rounded-2xl p-4 sm:p-5 transition-all">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-sm font text-gray-900">Syarat Tambahan 1 (Contoh: Wajib melampirkan portofolio)</p>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-red-500 transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        </button>
                </div>

                <!-- Item Syarat Tambahan 2 -->
                <div class="flex items-center justify-between gap-4 border border-[#1E5EFF] bg-[#F8FAFC] rounded-2xl p-4 sm:p-5 transition-all">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-sm font text-gray-900">Syarat Tambahan 2 (Contoh: Pernah menjadi asisten mata kuliah terkait)</p>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-red-500 transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>  
    </div>

            <!-- 3. MANFAAT & BENEFIT ASISTEN -->
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden p-6 md:p-8">
                <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-100">
                    <!-- Kotak Ikon Checklist Clipboard persis Figma -->
                    <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-[#1E5EFF] shadow-xs shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-900">Manfaat & Benefit Asisten</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Masukan manfaat & benefit untuk asisten praktikum yang di inginkan.</p>
                    </div>
                    <button type="button" class="text-sm font-semibold text-[#1E5EFF] hover:underline whitespace-nowrap">+ Tambah Manfaat & Benefit</button>
                </div>

                    <!-- Contoh Item Manfaat / Benefit / Syarat dengan Border Biru -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-4 border border-[#1E5EFF] bg-[#F8FAFC] rounded-2xl p-4 sm:p-5 transition-all">
                            <div class="flex items-center gap-3 sm:gap-4">
                        <!-- Ikon Centang Hijau Bulat -->
                            <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Konversi SKS</p>
                                <p class="text-xs text-gray-500 mt-0.5">Dapat ditukarkan menjadi 2 SKS yang anda inginkan</p>
                            </div>
                        </div>
                            <!-- Tombol Silang (X) -->
                        <button type="button" class="text-gray-400 hover:text-red-500 transition-colors p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. DAFTAR DOKUMEN YANG DIMINTA -->
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

              <!-- Card Transkrip Nilai yang disamakan dengan Konversi SKS (Border Biru, BG Lembut, & Shadow) -->
            <div class="bg-[#F8FAFC] rounded-2xl border border-[#1E5EFF] p-4 sm:p-5 shadow-xs flex items-center justify-between gap-4 mb-4 transition-all">
                <div class="flex items-center gap-4">
                    <!-- Ikon Dokumen dengan kotak putih bersih -->
                    <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-[#1E5EFF] shadow-xs shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Transkrip Nilai Akademik Terakhir</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">memuat nilai mata kuliah prasyarat secara sah & terverifikasi</p>
                    </div>
                </div>

                 <div class="flex items-center gap-4 shrink-0">
                    <!-- Badge Format PDF -->
                    <span class="inline-flex items-center px-6 py-2 rounded-lg text-xs sm:text-sm font bg-[#EBF1FF] text-[#1E5EFF] border border-blue-200">
                        Format : PDF
                    </span>
                    <!-- Radio Button Bulat -->
                        <div class="w-6 h-6 rounded-full border border-[#1E5EFF] flex items-center justify-center"></div>
                </div>
             </div>
        </div>
        

        <!-- FOOTER SUBMIT BUTTONS -->
        <div class="flex justify-end items-center gap-4 pt-2">
            <button type="button" class="px-6 py-3.5 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-xs">
                Batalkan
            </button>
            <button type="submit" class="px-8 py-3.5 bg-[#1E5EFF] text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Publikasikan
            </button>
        </div>

    </form>
</div>
@endsection