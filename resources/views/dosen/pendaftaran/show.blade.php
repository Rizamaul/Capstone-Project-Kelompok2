@extends('layouts.dosen')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" x-data="{ showPdfModal: false }">
    
    <!-- KEMBALI & HEADER -->
    <div class="mb-6">
        <a href="{{ route('dosen.dashboard') }}" class="text-xs font-semibold text-[#1E5EFF] hover:underline mb-2 inline-block">
            ← Kembali ke Dashboard
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Peninjauan & Pratinjau Berkas Pelamar</h1>
                <p class="text-sm text-gray-500">Evaluasi nilai prasyarat, esai motivasi, dan periksa berkas PDF KSM mahasiswa secara langsung.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl text-xs font-semibold hover:bg-red-100 transition-colors">
                    ✕ Tolak Pelamar
                </button>
                <button class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
                    ✓ Terima Sebagai Asisten
                </button>
            </div>
        </div>
    </div>

    <!-- KONTEN UTAMA (GRID 2 KOLOM) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: PROFIL & DATA AKADEMIK (2 SPAN) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- PROFIL PELAMAR -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span>👤</span> Profil & Identitas Mahasiswa
                </h2>
                
                <div class="flex items-center gap-4 pb-6 border-b border-gray-100">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold text-xl border border-blue-100">
                        KH
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Khairul Hilmi</h3>
                        <p class="text-xs text-gray-500">NIM : <span class="font-mono font-semibold text-gray-800">128973407</span> • Program Studi : Ilmu Komputer</p>
                        <p class="text-xs text-blue-600 font-medium mt-1">Mata Kuliah Dilamar: Algoritma & Struktur Data (IK2402)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-gray-500 mb-1">IPK Kumulatif</p>
                        <p class="text-lg font-bold text-gray-900">3.94 <span class="text-xs font-normal text-gray-500">/ 4.00</span></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-gray-500 mb-1">Nilai Mata Kuliah Prasyarat</p>
                        <p class="text-lg font-bold text-emerald-600">Nilai A <span class="text-xs font-normal text-gray-500">(Bobot 4.00)</span></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-gray-500 mb-1">Status Auto-Filter</p>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full font-semibold text-[10px]">Lolos Sistem</span>
                    </div>
                </div>
            </div>

            <!-- ESAI MOTIVASI -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span>✍️</span> Esai Motivasi & Komitmen Mahasiswa
                </h2>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 text-xs text-gray-700 leading-relaxed space-y-3">
                    <p>
                        "Saya memiliki ketertarikan mendalam pada pengembangan algoritma efisien dan struktur data lanjutan. Selama menempuh mata kuliah terkait, saya aktif membantu rekan seangkatan dalam memahami konsep kompleksitas waktu (Big-O), pointer, dan implementasi tree."
                    </p>
                    <p>
                        "Menjadi asisten praktikum adalah kesempatan emas bagi saya untuk mengabdikan diri mendukung proses pembelajaran di laboratorium, sekaligus mengasah kemampuan komunikasi teknis dan kepemimpinan."
                    </p>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: PREVIEW BERKAS PDF KSM -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span>📄</span> Dokumen KSM & Transkrip
                </h2>
                <p class="text-xs text-gray-500">Periksa lembar studi mahasiswa dan transkrip nilai langsung dari peramban.</p>

                <!-- Tombol Trigger Modal / Iframe -->
                <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">📑</span>
                        <div>
                            <p class="text-xs font-bold text-gray-800">KSM_Khairul_Hilmi.pdf</p>
                            <p class="text-[10px] text-gray-400">Terverifikasi BAAK (2.4 MB)</p>
                        </div>
                    </div>
                    <button @click="showPdfModal = true" class="px-3 py-1.5 bg-[#1E5EFF] text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors">
                        Pratinjau
                    </button>
                </div>

                <!-- Inline Preview / Iframe kecil di sidebar -->
                <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-100 h-64 flex items-center justify-center relative">
                    <!-- Placeholder atau Iframe PDF nyata -->
                    <div class="text-center p-4">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-xs font-semibold text-gray-700 mb-1">Pratinjau Berkas Aktif</p>
                        <button @click="showPdfModal = true" class="text-xs text-[#1E5EFF] font-bold hover:underline">Perbesar di Jendela Modal →</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL POPUP PREVIEW PDF KSM -->
    <div x-show="showPdfModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-4xl w-full h-[85vh] flex flex-col shadow-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📑</span>
                    <h3 class="font-bold text-gray-900 text-sm">Pratinjau Dokumen KSM - Khairul Hilmi</h3>
                </div>
                <button @click="showPdfModal = false" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold hover:bg-gray-300">✕</button>
            </div>
            
            <!-- Modal Body (Iframe PDF Viewer) -->
            <div class="flex-1 bg-gray-100 p-4">
                <iframe src="https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf" class="w-full h-full rounded-xl border border-gray-200 bg-white"></iframe>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-gray-100 flex items-center justify-end bg-gray-50 gap-2">
                <button @click="showPdfModal = false" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold">Tutup</button>
                <a href="#" class="px-4 py-2 bg-[#1E5EFF] text-white rounded-xl text-xs font-semibold hover:bg-blue-700">Unduh PDF</a>
            </div>
        </div>
    </div>

</div>
@endsection