@extends('layouts.mahasiswa')
@section('title', 'Detail Matakuliah')

@section('content')
<div class="space-y-6">

    <!-- HEADER CARD -->
    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E5EFF] tracking-tight">
                {{ $lowongan['nama'] }}
            </h1>
            <p class="text-xs text-gray-500 mt-1">
                Rekrutmen asisten praktikum untuk membantu sesi belajar yang terstruktur dan suportif.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('mahasiswa.lowongan.index') }}" 
               class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                ← Kembali
            </a>
            @if($lowongan['status'] === 'Dibuka')
                <a href="{{ route('mahasiswa.pendaftaran.create', $lowongan['id']) }}" 
                   class="px-5 py-2.5 bg-[#1E5EFF] rounded-xl text-xs font-bold text-white hover:bg-blue-700 transition-colors shadow-xs">
                    Daftar Sekarang
                </a>
            @else
                <button disabled class="px-5 py-2.5 bg-gray-200 rounded-xl text-xs font-bold text-gray-400 cursor-not-allowed">
                    Pendaftaran Ditutup
                </button>
            @endif
        </div>
    </div>

    <!-- GRID 2 KOLOM -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI (2 SPAN) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- TENTANG MATA KULIAH & KESEMPATAN -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs space-y-5">
                <div>
                    <h2 class="text-base font-bold text-[#1E5EFF]">Tentang Mata Kuliah dan Kesempatan ini</h2>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        {{ $lowongan['deskripsi'] ?: 'Mata kuliah ' . $lowongan['nama'] . ' membekali mahasiswa dengan fondasi analisis praktikum mendalam, implementasi kode terstruktur, serta bimbingan aktif di laboratorium bersama dosen pengampu.' }}
                    </p>
                </div>

                <!-- Syarat Pendaftaran -->
                <div>
                    <h3 class="text-xs font-bold text-gray-900 mb-2">Syarat Pendaftaran</h3>
                    <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                        <li>Minimal nilai {{ $lowongan['syarat_nilai'] ?: 'B' }} pada {{ $lowongan['nama'] }}</li>
                        <li>IPK Kumulatif Minimal {{ $lowongan['syarat_ipk'] ? number_format((float)$lowongan['syarat_ipk'], 2, ',', '.') : '3.25' }}</li>
                        <li>Bersedia mendampingi praktikum setiap minggu</li>
                        <li>Mampu bekerja kolaboratif dan komunikatif</li>
                        @if($lowongan['persyaratan'])
                            <li>{{ $lowongan['persyaratan'] }}</li>
                        @endif
                    </ul>
                </div>

                <!-- Tanggung Jawab Asisten Praktikum -->
                <div>
                    <h3 class="text-xs font-bold text-gray-900 mb-2">Tanggung Jawab Asisten Praktikum</h3>
                    <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                        <li>Panduan Praktikum Terjadwal C/C++</li>
                        <li>Penilaian & Review Kode Mandiri</li>
                        <li>Bimbingan Tugas Besar & Pra-Ujian</li>
                        <li>Koordinasi Dosen Pengampu</li>
                    </ul>
                </div>
            </div>

            <!-- KRITERIA SELEKSI -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs">
                <h2 class="text-base font-bold text-gray-900">Kriteria Seleksi</h2>
                <p class="text-[11px] text-gray-400 mt-0.5">Kriteria ditampilkan untuk transparansi, bukan dihitung sebagai skor otomatis.</p>
                <ul class="text-xs text-gray-600 space-y-2 mt-3 list-disc list-inside">
                    <li>Studi kasus pointer, manipulasi struktur data dinamis, dan optimasi Big-O.</li>
                    <li>Kejelasan penyampaian materi konsep dan simulasi bimbingan peserta praktikum.</li>
                    <li>Kualitas kode C/C++, struktur ADT modular, dokumentasi, dan kelayakan demo.</li>
                    <li>Tidak bentrok dengan jadwal KRS semester berjalan dan rekam jejak akademik.</li>
                </ul>
            </div>

            <!-- SILABUS & MODUL PRAKTIKUM -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs">
                <h2 class="text-base font-bold text-gray-900 mb-4">Silabus & Modul Praktikum Semester Berjalan</h2>
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="text-gray-700 font-medium">1. Pointer, Dynamic Memory Allocation, Struct, & Abstract Data Type (ADT)</span>
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-[#1E5EFF] text-[10px] font-semibold">Minggu 1 - 3</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="text-gray-700 font-medium">2. Linear Structures: Singly & Doubly Linked List, Stack, dan Queue</span>
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-[#1E5EFF] text-[10px] font-semibold">Minggu 4 - 7</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="text-gray-700 font-medium">3. Non-Linear Structures: Binary Search Tree (BST), AVL Tree, dan Binary Heap</span>
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-[#1E5EFF] text-[10px] font-semibold">Minggu 10 - 11</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="text-gray-700 font-medium">4. Graph Representation (Adjacency List/Matrix), Traversal (BFS/DFS), & Shortest Path (Dijkstra)</span>
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-[#1E5EFF] text-[10px] font-semibold">Minggu 12 - 14</span>
                    </div>
                </div>
            </div>

            <!-- MANFAAT MENJADI ASISTEN -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs">
                <h2 class="text-xs font-bold text-gray-900 mb-1">Manfaat menjadi asisten</h2>
                <p class="text-xs text-gray-500">
                    Pengalaman mengajar dan mentoring | Sertifikat asisten praktikum | Honor berdasarkan jam yang disetujui
                </p>
            </div>

        </div>

        <!-- KOLOM KANAN (SIDEBAR) -->
        <div class="space-y-6">

            <!-- RINGKASAN LOWONGAN -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900">Ringkasan</h2>
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold {{ $lowongan['status'] === 'Dibuka' ? 'bg-blue-50 text-[#1E5EFF]' : 'bg-gray-100 text-gray-500' }}">
                        ● Pendaftaran {{ $lowongan['status'] }}
                    </span>
                </div>

                <div class="space-y-3 pt-2 text-xs">
                    <div>
                        <p class="text-gray-400">Tenggat pendaftaran</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $lowongan['tenggat'] ?: '22 Okt, 2026' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Kuota Formasi</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $lowongan['kuota'] }} asisten</p>
                    </div>

                    <div class="border-t border-gray-100 pt-3 space-y-3">
                        <div>
                            <p class="font-bold text-gray-800">Dosen Pengampu:</p>
                            <p class="text-gray-600 mt-0.5">{{ $lowongan['dosen'] }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Lokasi Praktikum:</p>
                            <p class="text-gray-600 mt-0.5">Lab Komputer (lab ruangan 2403, Gedung Griya Legita lt. 3)</p>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Jadwal Tugas:</p>
                            <p class="text-gray-600 mt-0.5">{{ $lowongan['jadwal'] ?: 'Senin & Kamis (Total 6 jam/Minggu)' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DOKUMEN YANG DIMINTA -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs space-y-3">
                <h2 class="text-sm font-bold text-gray-900">Dokumen yang diminta</h2>
                
                <div class="space-y-2">
                    @forelse($lowongan['berkas_diminta'] as $doc)
                        <div class="p-3 bg-blue-50/70 rounded-xl text-xs font-semibold text-gray-800">
                            {{ $doc === 'CV' ? 'CV / Resume' : $doc }}
                        </div>
                    @empty
                        <div class="p-3 bg-blue-50/70 rounded-xl text-xs font-semibold text-gray-800">
                            CV / Resume
                        </div>
                        <div class="p-3 bg-blue-50/70 rounded-xl text-xs font-semibold text-gray-800">
                            Transkrip nilai
                        </div>
                    @endforelse
                    <div class="p-3 bg-blue-50/70 rounded-xl text-xs font-semibold text-gray-800">
                        Portofolio (opsional)
                    </div>
                </div>

                <p class="text-[10px] text-gray-400 leading-relaxed pt-1">
                    Demo saat ini menggunakan data dokumen contoh profil Anda. File transkrip diverifikasi langsung melalui server akademik.
                </p>
            </div>

            <!-- CARD PENDAFTARAN SAYA -->
            <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs space-y-3">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Dipanggil interview</h2>
                    <p class="text-[10px] text-gray-400 uppercase font-semibold mt-0.5 tracking-wider">PENDAFTARAN SAYA</p>
                </div>
                <a href="{{ route('mahasiswa.status') }}" 
                   class="block w-full py-2.5 px-4 bg-[#1E5EFF] text-white text-xs font-bold rounded-xl text-center hover:bg-blue-700 transition-colors shadow-xs">
                    Buka detail Status →
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
