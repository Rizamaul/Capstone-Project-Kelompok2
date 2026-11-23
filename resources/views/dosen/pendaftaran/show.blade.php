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
         showRejectModal: false,
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
                    Status Seleksi: {{ ucfirst($pendaftaran->status) }}
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800 shadow-xs flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- KONTEN UTAMA (GRID 2 KOLOM) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: PROFIL, ESAI & PENGATURAN WAWANCARA (2 SPAN) -->
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

            <!-- ATUR JADWAL DAN LOKASI WAWANCARA (CALENDAR PICKER) -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span>🗓</span> Atur Jadwal & Lokasi Wawancara
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Pilih tanggal dan jam interview menggunakan kalender, serta tentukan lokasi seleksi.</p>
                    </div>
                    @if($pendaftaran->jadwal_wawancara)
                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-[#1E5EFF] font-bold text-xs border border-blue-100">
                            Terjadwal
                        </span>
                    @endif
                </div>

                @if($pendaftaran->jadwal_wawancara)
                    <div class="p-4 bg-blue-50/60 rounded-xl border border-blue-100 text-xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Waktu Wawancara:</span>
                            <span class="font-bold text-gray-900">{{ $pendaftaran->jadwal_wawancara }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Lokasi / Ruang:</span>
                            <span class="font-bold text-gray-900">{{ $pendaftaran->lokasi_wawancara }}</span>
                        </div>
                        @if($pendaftaran->catatan_wawancara)
                            <div class="pt-2 border-t border-blue-100">
                                <span class="text-gray-500 block mb-0.5">Catatan Tambahan:</span>
                                <span class="text-gray-800">{{ $pendaftaran->catatan_wawancara }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <form action="{{ route('dosen.pendaftar.wawancara', $pendaftaran) }}" method="POST" class="space-y-4 pt-1">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">
                                📅 Waktu Pelaksanaan Wawancara (Kalender)
                            </label>
                            <input type="datetime-local" 
                                   name="jadwal_wawancara" 
                                   required 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF] bg-white">
                            <p class="text-[10px] text-gray-400 mt-1">Pilih tanggal dan jam melalui ikon kalender.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">
                                📍 Lokasi / Ruang Wawancara
                            </label>
                            <input type="text" 
                                   name="lokasi_wawancara" 
                                   value="{{ old('lokasi_wawancara', $pendaftaran->lokasi_wawancara) }}" 
                                   required 
                                   placeholder="Contoh: Ruang Dosen Gd. A / Lab Komputer 2" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">
                            <p class="text-[10px] text-gray-400 mt-1">Tuliskan nama ruangan atau tautan online meeting.</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Catatan / Instruksi Wawancara (Opsional)</label>
                        <input type="text" 
                               name="catatan_wawancara" 
                               value="{{ old('catatan_wawancara', $pendaftaran->catatan_wawancara) }}" 
                               placeholder="Contoh: Harap hadir 10 menit sebelum jadwal dan membawa laptop." 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-[#1E5EFF] hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                            {{ $pendaftaran->jadwal_wawancara ? 'Perbarui Jadwal Wawancara' : 'Simpan Jadwal Wawancara' }}
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- KOLOM KANAN: DOKUMEN PERSYARATAN & 2 TOMBOL VERIFIKASI MANUAL -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span>📄</span> Dokumen Persyaratan & KSM
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Tinjau berkas lalu verifikasi kelengkapannya secara manual.</p>
                    </div>
                </div>

                <!-- Status Verifikasi Berkas Saat Ini -->
                <div class="p-3.5 rounded-xl border {{ $pendaftaran->status_berkas === 'diverifikasi' ? 'bg-emerald-50/70 border-emerald-200 text-emerald-800' : ($pendaftaran->status_berkas === 'ditolak' ? 'bg-red-50/70 border-red-200 text-red-800' : 'bg-amber-50/70 border-amber-200 text-amber-800') }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold flex items-center gap-1.5">
                            @if($pendaftaran->status_berkas === 'diverifikasi')
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Status: Berkas Diverifikasi
                            @elseif($pendaftaran->status_berkas === 'ditolak')
                                <span class="w-2 h-2 rounded-full bg-red-500"></span> Status: Berkas Ditolak
                            @else
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Status: Belum Diverifikasi
                            @endif
                        </span>
                        @if($pendaftaran->waktu_verifikasi_berkas)
                            <span class="text-[10px] text-gray-500">{{ $pendaftaran->waktu_verifikasi_berkas->translatedFormat('d M, H:i') }}</span>
                        @endif
                    </div>
                    @if($pendaftaran->status_berkas === 'ditolak' && $pendaftaran->catatan_berkas)
                        <div class="mt-2 pt-2 border-t border-red-200/60 text-xs text-red-700 leading-relaxed">
                            <span class="font-bold">Alasan Penolakan:</span> {{ $pendaftaran->catatan_berkas }}
                        </div>
                    @endif
                </div>

                <!-- 2 TOMBOL MANUAL VERIFIKASI (CEKLIST ✓ DAN SILANG ✕) -->
                <div>
                    <p class="text-xs font-bold text-gray-700 mb-2">Tindakan Verifikasi Berkas:</p>
                    <div class="flex items-center gap-3">
                        <!-- Tombol Ceklist (✓) -->
                        <form action="{{ route('dosen.pendaftar.verifikasi-berkas', $pendaftaran) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="diverifikasi">
                            <button type="submit" 
                                    class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition-colors cursor-pointer"
                                    title="Setujui dan verifikasi berkas">
                                <span class="text-sm font-extrabold">✓</span>
                                <span>Verifikasi Berkas</span>
                            </button>
                        </form>

                        <!-- Tombol Silang (✕) -> Membuka Modal Alasan -->
                        <div class="flex-1">
                            <button type="button" 
                                    @click="showRejectModal = true"
                                    class="w-full py-2.5 px-3 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold text-xs flex items-center justify-center gap-1.5 transition-colors cursor-pointer"
                                    title="Tolak berkas dengan alasan">
                                <span class="text-sm font-extrabold">✕</span>
                                <span>Tolak Berkas</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- DAFTAR DOKUMEN BERKAS -->
                <div class="space-y-3 pt-2">
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
                                <button type="button" 
                                        @click="openPreview('Curriculum Vitae (CV) - {{ $pendaftaran->nama }}', '{{ $cvUrl }}')" 
                                        class="px-3 py-1.5 bg-[#1E5EFF] text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors cursor-pointer shadow-xs">
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
                                <button type="button" 
                                        @click="openPreview('Transkrip Nilai / KSM - {{ $pendaftaran->nama }}', '{{ $transkripUrl }}')" 
                                        class="px-3 py-1.5 bg-[#1E5EFF] text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors cursor-pointer shadow-xs">
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
                    @endif
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL POPUP ALASAN PENOLAKAN BERKAS (KETIKA MENEKAN X) -->
    <div x-show="showRejectModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4" 
         x-cloak>
        <div @click.away="showRejectModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-gray-100 space-y-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-sm">✕</span>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-sm">Alasan Penolakan Berkas</h3>
                        <p class="text-[11px] text-gray-500">Tuliskan keterangan mengapa berkas pelamar belum memenuhi syarat</p>
                    </div>
                </div>
                <button type="button" @click="showRejectModal = false" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">✕</button>
            </div>

            <form action="{{ route('dosen.pendaftar.verifikasi-berkas', $pendaftaran) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" value="ditolak">

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Alasan / Catatan Kekurangan Berkas</label>
                    <textarea name="alasan" 
                              rows="4" 
                              required 
                              placeholder="Contoh: Dokumen transkrip nilai belum memuat semester terakhir, atau format CV tidak terbaca jelas..."
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500 leading-relaxed"></textarea>
                    <p class="text-[10px] text-gray-400 mt-1">Alasan ini akan langsung ditampilkan kepada mahasiswa pada portal mereka.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="showRejectModal = false" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors cursor-pointer">
                        Kirim Alasan & Tolak Berkas
                    </button>
                </div>
            </form>
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
                <span class="text-xs text-gray-500 hidden sm:inline">
                    Periksa kesesuaian berkas dengan kriteria penerimaan
                </span>
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
