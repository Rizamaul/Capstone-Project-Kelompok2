@extends('layouts.mahasiswa')
@section('title', 'Detail Status Pendaftaran')

@section('content')
<div class="space-y-6">

    <!-- HEADER & NAVIGASI KEMBALI -->
    <div>
        <a href="{{ route('mahasiswa.status') }}" class="text-xs font-semibold text-[#1E5EFF] hover:underline inline-flex items-center gap-1 mb-2">
            <span>← Kembali</span>
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Detail Pendaftaran {{ $pendaftaran['matkul'] }}</h1>
                <p class="text-xs text-gray-500 mt-1">Setiap perubahan status ditampilkan agar kamu tahu langkah berikutnya.</p>
            </div>
            <div>
                <a href="{{ route('mahasiswa.lowongan.index') }}" class="text-xs font-semibold text-gray-500 hover:text-[#1E5EFF] transition-colors">
                    ← Lihat Pengumuman
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800 shadow-xs flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- GRID 2 KOLOM -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI (2 SPAN): JEJAK STATUS -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs">
                
                <!-- Header Card Status -->
                <div class="flex items-center justify-between gap-2 pb-5 border-b border-gray-100">
                    <div>
                        <span class="px-2.5 py-1 rounded-lg bg-[#EDF5FF] text-[#1E5EFF] font-bold text-[11px]">
                            {{ $pendaftaran['kode'] }} · {{ $pendaftaran['sks'] }} SKS
                        </span>
                        <h2 class="text-lg font-bold text-gray-900 mt-2.5">{{ $pendaftaran['matkul'] }}</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Didaftarkan {{ $pendaftaran['didaftarkan_pada'] }}</p>
                    </div>

                    @php
                        $badgeColor = match($pendaftaran['status']) {
                            'disetujui' => 'bg-emerald-50 text-emerald-700',
                            'ditolak' => 'bg-red-50 text-red-700',
                            default => 'bg-[#FEF9E7] text-amber-700',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 {{ $badgeColor }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $pendaftaran['status'] === 'disetujui' ? 'bg-emerald-500' : ($pendaftaran['status'] === 'ditolak' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                        <span>{{ ucfirst($pendaftaran['status']) }}</span>
                    </span>
                </div>

                <!-- Section Jejak Status -->
                <div class="pt-5">
                    <h3 class="text-sm font-bold text-gray-900">Jejak Status</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5 mb-6">Riwayat perubahan status pendaftaran.</p>

                    <div class="space-y-6">
                        @foreach($langkah as $step)
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-xs font-bold {{ $step['selesai'] ? 'border-2 border-emerald-500 text-emerald-600 bg-emerald-50/50' : 'border-2 border-amber-400 text-amber-500 bg-amber-50/30' }}">
                                        ✓
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900">{{ $step['judul'] }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $step['deskripsi'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs {{ $step['selesai'] ? 'text-gray-400' : 'text-gray-400' }}">{{ $step['waktu'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- KOLOM KANAN: RINGKASAN & ALERT -->
        <div class="space-y-6">

            <!-- CARD RINGKASAN PENDAFTARAN -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900">Ringkasan Pendaftaran</h3>
                    <a href="{{ route('mahasiswa.pendaftaran.edit', $pendaftaran['id']) }}" class="px-4 py-1.5 bg-[#1E5EFF] text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-xs">
                        Edit
                    </a>
                </div>

                <div class="pt-4 space-y-4 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Nama</span>
                        <span class="font-bold text-gray-900">{{ $pendaftaran['nama'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">NIM</span>
                        <span class="font-bold text-gray-900">{{ $pendaftaran['nim'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Nilai Mata Kuliah</span>
                        <span class="font-bold text-gray-900">{{ $pendaftaran['nilai_matkul'] ?: 'A-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Dokumen CV</span>
                        <span class="font-bold text-gray-900">{{ $pendaftaran['cv'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Transkip</span>
                        <span class="font-bold text-gray-900">{{ $pendaftaran['transkrip'] }}</span>
                    </div>
                </div>
            </div>

            <!-- ALERT BOX STATUS TAHAP -->
            <div class="rounded-2xl border border-amber-200/70 bg-[#FEF9E7] p-5">
                <h4 class="text-xs font-bold text-amber-900">
                    {{ $pendaftaran['status'] === 'disetujui' ? 'Pendaftaran telah disetujui' : ($pendaftaran['status'] === 'ditolak' ? 'Pendaftaran ditolak' : 'Pendaftaran masi tahap menunggu') }}
                </h4>
                <p class="text-xs text-amber-800/90 mt-2 leading-relaxed">
                    {{ $pendaftaran['catatan_dosen'] ?: 'Dosen belum mengonfirmasi hasil seleksi. lihat catatan diatas untuk informasi lebih lanjut.' }}
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
