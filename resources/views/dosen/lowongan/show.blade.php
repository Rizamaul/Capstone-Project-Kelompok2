@extends('layouts.dosen') 

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <!-- HEADER STATUS & JUDUL -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 mb-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full text-xs font-semibold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    STATUS: AKTIF & TERBUKA
                </span>
                <span class="text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    Terverifikasi Departemen
                </span>
            </div>
            <!-- Tenggat Waktu Badge -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-2 flex items-center gap-3">
                <svg class="w-5 h-5 text-[#1E5EFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="text-xs text-gray-500">Tenggat Waktu</p>
                    <p class="text-sm font-bold text-[#1E5EFF]">12 Hari Tersisa <span class="text-xs font-normal text-gray-500">(22 Oktober 2026 • 23:59 WIB)</span></p>
                </div>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Lowongan Rekrutmen Asisten Dosen Resmi Tayang!</h1>
        <p class="text-sm text-gray-600 mb-4">
            Mata Kuliah: <span class="font-semibold text-gray-800">IK2402 - Algoritma & Struktur Data (Semester Genap 2024/2025)</span> • Dosen Pembina: <span class="font-semibold text-gray-800">Nama Dosen Pemangku</span>
        </p>

        <!-- Link Aksi Cepat Bagikan -->
        <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-600">
                <span class="text-gray-400">🔗</span> https://asdos.universitaspertamina.ac.id/lowongan/ik2402
            </div>
            <button class="px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors flex items-center gap-1.5 shadow-xs">
                📋 Salin Tautan
            </button>
            <button class="px-3.5 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold hover:bg-emerald-100 transition-colors flex items-center gap-1.5">
                💬 Grup WhatsApp
            </button>
            <button class="px-3.5 py-2 bg-purple-50 text-purple-700 border border-purple-200 rounded-xl text-xs font-semibold hover:bg-purple-100 transition-colors flex items-center gap-1.5">
                🔲 QR Code
            </button>
        </div>
    </div>

    <!-- KONTEN UTAMA & SIDEBAR -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI (Detail Parameter & Syarat) -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span>📄</span> Ringkasan Parameter Pengumuman
                    </h2>
                    <a href="{{ route('dosen.lowongan.edit', 1) }}" class="px-3 py-1.5 bg-blue-50 text-[#1E5EFF] rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors">
                        Edit Lowongan
                    </a>
                </div>

                <!-- A. Kuota & Alokasi -->
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">A. Kuota & Alokasi Kelas Paralel</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Kelas Algoritma & Struktur Data</h4>
                            <p class="text-xs text-gray-500 mt-1">🕒 Senin, 08:00 - 11:30 • 📍 Lab Komputer 2402</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-[#1E5EFF] rounded-lg text-xs font-bold">2 Asisten</span>
                    </div>
                </div>

                <!-- B. Kriteria Ambang Batas -->
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">B. Kriteria Ambang Batas Akademik (Auto-Filter)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <p class="text-xs text-gray-500 mb-1">Nilai Prasyarat Minimal</p>
                            <p class="text-base font-bold text-gray-800">Minimal A <span class="text-xs font-normal text-gray-500">(Bobot 4.00)</span></p>
                        </div>
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <p class="text-xs text-gray-500 mb-1">IPK Kumulatif Minimal</p>
                            <p class="text-base font-bold text-gray-800">≥ 3.25 <span class="text-xs font-normal text-gray-500">Skala 4.00</span></p>
                        </div>
                    </div>
                    <div class="text-xs text-gray-600 space-y-2 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <p class="font-semibold text-gray-700">Kualifikasi Teknis Tambahan:</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                            <li>Telah tuntas menempuh mata kuliah Struktur Data dan Praktikum Pemrograman Dasar.</li>
                            <li>Menguasai bahasa pemrograman C++ (Pointer, Linked List, Tree) & Python dasar untuk analisis kompleksitas Big-O.</li>
                            <li>Memiliki kemampuan komunikasi interpersonal yang baik dan kesabaran membimbing praktikan.</li>
                        </ul>
                    </div>
                </div>

                <!-- C. Berkas & Dokumen Wajib -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">C. Berkas & Dokumen Wajib Pelamar</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div class="p-3 border border-gray-200 rounded-xl bg-gray-50">
                            <p class="font-semibold text-gray-800">Transkrip Nilai (PDF)</p>
                            <p class="text-gray-500 mt-0.5">Terverifikasi BAAK</p>
                        </div>
                        <div class="p-3 border border-gray-200 rounded-xl bg-gray-50">
                            <p class="font-semibold text-gray-800">Curriculum Vitae (CV)</p>
                            <p class="text-gray-500 mt-0.5">Format ATS / Kreatif</p>
                        </div>
                        <div class="p-3 border border-gray-200 rounded-xl bg-gray-50">
                            <p class="font-semibold text-gray-800">Pas Foto Formal</p>
                            <p class="text-gray-500 mt-0.5">Ukuran 3x4 (JPG/PNG)</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN (Aksi Cepat & Info Log) -->
        <div class="space-y-6">
            
            <!-- Tindakan Cepat -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span>⚡</span> Tindakan Cepat
                </h3>
                <div class="space-y-3">
                    <a href="#" class="w-full py-2.5 px-4 bg-[#1E5EFF] text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
                        👥 Pantau Pelamar & Statistik
                    </a>
                    <a href="#" class="w-full py-2.5 px-4 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                        👁️ Pratinjau Tampilan Mahasiswa
                    </a>
                    <a href="{{ route('dosen.dashboard') }}" class="w-full py-2.5 px-4 bg-gray-50 border border-gray-200 text-gray-600 rounded-xl text-xs font-semibold hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                        ← Kembali ke Dashboard Kelola
                    </a>
                </div>
            </div>

            <!-- Log Publikasi -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm text-xs space-y-3">
                <h3 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <span>🔒</span> Log Publikasi
                </h3>
                <div class="flex justify-between py-1 border-b border-gray-100">
                    <span class="text-gray-500">ID Publikasi</span>
                    <span class="font-mono font-semibold text-gray-800">#REC-2025-IF2110-09</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-100">
                    <span class="text-gray-500">Waktu Terbit</span>
                    <span class="font-semibold text-gray-800">Hari ini, 14:35 WIB</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Notifikasi Blast</span>
                    <span class="font-semibold text-blue-600">142 Mahasiswa Berhak</span>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection