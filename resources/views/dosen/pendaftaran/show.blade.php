@extends('layouts.dosen')

@section('content')
@php
    $cvUrl = $pendaftaran->cv_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($pendaftaran->cv_path) : null;
    $transkripUrl = $pendaftaran->transkrip_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($pendaftaran->transkrip_path) : null;
    $defaultPdfUrl = $cvUrl ?: $transkripUrl;
    $defaultPdfTitle = $cvUrl ? 'Curriculum Vitae (CV) - ' . $pendaftaran->nama : ($transkripUrl ? 'Transkrip Nilai / KSM - ' . $pendaftaran->nama : '');
@endphp

<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" 
     x-data="{ 
         showPdfModal: false,
         activePdfTitle: @js($defaultPdfTitle),
         activePdfUrl: @js($defaultPdfUrl),
         openPreview(title, url) {
             this.activePdfTitle = title;
             this.activePdfUrl = url;
             this.showPdfModal = true;
         }
     }">

    <!-- KEMBALI & HEADER -->
    <div class="mb-6">
        <a href="{{ route('dosen.pendaftar.index') }}" class="text-xs font-semibold text-[#1E5EFF] hover:underline mb-2 inline-block">
            ← Kembali ke Daftar Pendaftar
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Peninjauan & Pratinjau Berkas Pelamar</h1>
                <p class="text-sm text-gray-500">Evaluasi nilai prasyarat, esai motivasi, dan periksa berkas KSM/transkrip mahasiswa secara langsung.</p>
            </div>
            
            @if($pendaftaran->status === 'menunggu')
                <div class="flex items-center gap-3">
                    <form action="{{ route('dosen.pendaftar.status', $pendaftaran) }}" method="POST" onsubmit="return confirm('Tolak pelamar ini?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_seleksi" value="ditolak">
                        <button type="submit" class="px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl text-xs font-semibold hover:bg-red-100 transition-colors cursor-pointer">
                            ✕ Tolak Pelamar
                        </button>
                    </form>

                    <form action="{{ route('dosen.pendaftar.status', $pendaftaran) }}" method="POST" onsubmit="return confirm('Terima pelamar ini sebagai asisten?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_seleksi" value="disetujui">
                        <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm cursor-pointer">
                            ✓ Terima Sebagai Asisten
                        </button>
                    </form>
                </div>
            @else
                <span class="px-4 py-2 rounded-xl text-xs font-bold {{ $pendaftaran->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                    Status: {{ ucfirst($pendaftaran->status) }}
                </span>
            @endif
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
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold text-xl border border-blue-100 shrink-0">
                        {{ strtoupper(substr($pendaftaran->nama, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $pendaftaran->nama }}</h3>
                        <p class="text-xs text-gray-500">NIM : <span class="font-mono font-semibold text-gray-800">{{ $pendaftaran->nim }}</span></p>
                        <p class="text-xs text-blue-600 font-medium mt-1">Mata Kuliah Dilamar: {{ $pendaftaran->lowongan?->mata_kuliah ?? 'Lowongan' }} ({{ $pendaftaran->lowongan?->kode ?? '-' }})</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-gray-500 mb-1">IPK Kumulatif</p>
                        <p class="text-lg font-bold text-gray-900">{{ $pendaftaran->ipk ? number_format($pendaftaran->ipk, 2) : '—' }} <span class="text-xs font-normal text-gray-500">/ 4.00</span></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-gray-500 mb-1">Nilai Mata Kuliah</p>
                        <p class="text-lg font-bold text-emerald-600">Nilai {{ $pendaftaran->nilai_matkul ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-gray-500 mb-1">Status Seleksi</p>
                        <span class="px-2.5 py-1 rounded-full font-semibold text-[10px] {{ $pendaftaran->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700' : ($pendaftaran->status === 'ditolak' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                            {{ ucfirst($pendaftaran->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- ESAI MOTIVASI -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span>✍️</span> Esai Motivasi & Portofolio
                </h2>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 text-xs text-gray-700 leading-relaxed whitespace-pre-line">
{{ $pendaftaran->motivasi }}
                </div>

                @if($pendaftaran->portfolio)
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-2 text-xs">
                        <span class="text-gray-500">Tautan Portofolio:</span>
                        <a href="{{ $pendaftaran->portfolio }}" target="_blank" rel="noopener noreferrer" class="font-bold text-[#1E5EFF] hover:underline">
                            {{ $pendaftaran->portfolio }} ↗
                        </a>
                    </div>
                @endif
            </div>

        </div>

        <!-- KOLOM KANAN: DOKUMEN & PREVIEW BERKAS PDF -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span>📄</span> Dokumen Persyaratan & KSM
                </h2>
                <p class="text-xs text-gray-500">Periksa lembar CV, KSM, dan transkrip nilai langsung dari peramban.</p>

                @if($cvUrl)
                    <div class="p-3 bg-blue-50/50 rounded-xl border border-blue-100 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 text-xs min-w-0">
                            <span class="text-xl shrink-0">📑</span>
                            <div class="truncate">
                                <p class="font-bold text-gray-800 truncate">Curriculum Vitae (CV)</p>
                                <p class="text-[10px] text-gray-400">Berkas PDF</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" @click="openPreview('Curriculum Vitae (CV) - {{ $pendaftaran->nama }}', '{{ $cvUrl }}')" class="px-2.5 py-1.5 bg-[#1E5EFF] text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors cursor-pointer">
                                Pratinjau
                            </button>
                            <a href="{{ $cvUrl }}" target="_blank" download class="p-1.5 text-gray-600 hover:text-gray-900 border border-gray-200 rounded-lg hover:bg-gray-50" title="Unduh Berkas">
                                ⬇
                            </a>
                        </div>
                    </div>
                @endif

                @if($transkripUrl)
                    <div class="p-3 bg-blue-50/50 rounded-xl border border-blue-100 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 text-xs min-w-0">
                            <span class="text-xl shrink-0">📑</span>
                            <div class="truncate">
                                <p class="font-bold text-gray-800 truncate">Transkrip Nilai / KSM</p>
                                <p class="text-[10px] text-gray-400">Berkas PDF</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" @click="openPreview('Transkrip Nilai / KSM - {{ $pendaftaran->nama }}', '{{ $transkripUrl }}')" class="px-2.5 py-1.5 bg-[#1E5EFF] text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors cursor-pointer">
                                Pratinjau
                            </button>
                            <a href="{{ $transkripUrl }}" target="_blank" download class="p-1.5 text-gray-600 hover:text-gray-900 border border-gray-200 rounded-lg hover:bg-gray-50" title="Unduh Berkas">
                                ⬇
                            </a>
                        </div>
                    </div>
                @endif

                @if(! $cvUrl && ! $transkripUrl)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 text-center text-xs text-gray-500">
                        Tidak ada berkas PDF yang terlampir.
                    </div>
                @else
                    <!-- Inline Preview Box -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 flex flex-col mt-4">
                        <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 flex items-center justify-between text-xs font-semibold text-gray-700">
                            <span>Pratinjau Berkas Aktif</span>
                            <button type="button" @click="openPreview(activePdfTitle, activePdfUrl)" class="text-[#1E5EFF] hover:underline flex items-center gap-1 text-[11px] font-bold cursor-pointer">
                                Perbesar Modal ↗
                            </button>
                        </div>
                        <div class="h-64 w-full bg-slate-100 relative">
                            <template x-if="activePdfUrl">
                                <iframe :src="activePdfUrl" class="w-full h-full border-0"></iframe>
                            </template>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- MODAL POPUP PREVIEW PDF DOKUMEN / KSM -->
    <div x-show="showPdfModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4" 
         x-cloak>
        <div @click.away="showPdfModal = false" class="bg-white rounded-3xl max-w-4xl w-full h-[85vh] flex flex-col shadow-2xl overflow-hidden border border-gray-100">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📑</span>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm" x-text="activePdfTitle || 'Pratinjau Dokumen Berkas'"></h3>
                        <p class="text-[11px] text-gray-500">Pratinjau dokumen PDF langsung dari peramban dosen</p>
                    </div>
                </div>
                <button type="button" @click="showPdfModal = false" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold hover:bg-gray-300 transition-colors cursor-pointer">✕</button>
            </div>
            
            <!-- Modal Body (Iframe PDF Viewer) -->
            <div class="flex-1 bg-gray-100 p-2 sm:p-4">
                <template x-if="activePdfUrl">
                    <iframe :src="activePdfUrl" class="w-full h-full rounded-xl border border-gray-200 bg-white shadow-inner"></iframe>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-gray-100 flex items-center justify-between bg-gray-50">
                <span class="text-xs text-gray-500 hidden sm:inline">Periksa kesesuaian berkas dengan kriteria penerimaan</span>
                <div class="flex items-center gap-2 ml-auto">
                    <button type="button" @click="showPdfModal = false" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors cursor-pointer">Tutup</button>
                    <a :href="activePdfUrl" target="_blank" download class="px-4 py-2 bg-[#1E5EFF] text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition-colors flex items-center gap-1.5 shadow-sm">
                        <span>⬇</span> Unduh PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
