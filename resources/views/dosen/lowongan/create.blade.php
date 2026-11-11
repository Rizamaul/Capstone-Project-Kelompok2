@extends('layouts.dosen')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto" x-data="{ showModal: false }">
    
    <!-- HEADER -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Buat Lowongan Asisten Praktikum Baru</h1>
            <p class="text-sm text-gray-500">Formulir publikasi lowongan asisten praktikum untuk mahasiswa.</p>
        </div>
        <button type="button" class="px-5 py-2.5 bg-blue-50 text-[#1E5EFF] rounded-xl text-xs font-semibold hover:bg-blue-100 transition-colors shadow-sm whitespace-nowrap">
            + Simpan Draft
        </button>
    </div>

    <!-- FORM UTAMA -->
    <form class="space-y-6">
        
        <!-- KARTU INFORMASI MATA KULIAH -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-gray-100">
                <span class="text-lg">📖</span>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Informasi Mata Kuliah</h2>
                    <p class="text-xs text-gray-500">Tentukan mata kuliah, kuota pelamar, dan tenggat waktu pendaftaran yang dibutuhkan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mata Kuliah Diampu -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Mata Kuliah Diampu</label>
                    <select name="mata_kuliah" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                        <option value="algoritma">Algoritma & Struktur Data</option>
                        <option value="pemrograman">Pemrograman Berorientasi Objek</option>
                        <option value="basisdata">Basis Data</option>
                    </select>
                </div>

                <!-- Kode Mata Kuliah -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kode Mata Kuliah</label>
                    <input type="text" value="IK2402" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                </div>

                <!-- Kelas & Jadwal Pelaksanaan (Sesuai Permintaan) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kelas & Jadwal Pelaksanaan</label>
                    <select name="kelas" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                        <option value="" disabled selected>Pilih Kelas</option>
                        <option value="cs-2023">CS 2023 - Senin, 08:00 - 11:30 WIB</option>
                        <option value="cs-2024">CS 2024 - Rabu, 13:00 - 16:30 WIB</option>
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Semester</label>
                    <select name="semester" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                        <option>Semester Ganjil 2026</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                <!-- Kuota -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kuota Asisten Dosen yang Dibutuhkan</label>
                    <div class="flex items-center">
                        <button type="button" class="px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-l-xl text-gray-600 font-bold hover:bg-gray-200">-</button>
                        <input type="text" value="2" class="w-full text-center border-y border-gray-200 bg-gray-50 py-2.5 text-xs text-gray-800 focus:outline-none">
                        <button type="button" class="px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-r-xl text-gray-600 font-bold hover:bg-gray-200">+</button>
                        <span class="ml-3 text-xs text-gray-500 whitespace-nowrap">Mahasiswa</span>
                    </div>
                </div>

                <!-- Tenggat Waktu -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tenggat Waktu Pendaftaran</label>
                    <input type="text" value="26 Oktober 2026, 08:00 WIB" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-[#1E5EFF]">
                </div>
            </div>
        </div>

        <!-- TOMBOL AKSI BAWAH -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('dosen.dashboard') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                Batalkan
            </a>
            <button type="button" @click="showModal = true" class="px-6 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                Publikasikan
            </button>
        </div>
    </form>

    <!-- MODAL KONFIRMASI PUBLIKASI -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-8 text-center shadow-2xl transform transition-all" @click.away="showModal = false">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-[#1E5EFF]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-6">Apakah Anda yakin ingin Publikasikan Lowongan tersebut?</h3>
            <div class="flex items-center justify-center gap-3">
                <button @click="showModal = false" class="w-1/2 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-50">Batal</button>
                <button @click="showModal = false" class="w-1/2 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-bold hover:bg-blue-700 shadow-sm">Publikasikan</button>
            </div>
        </div>
    </div>

</div>
@endsection