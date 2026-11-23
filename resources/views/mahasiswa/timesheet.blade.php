@extends('layouts.mahasiswa')
@section('title', 'TimeSheet - Mahasiswa')

@section('content')
<div x-data="{ showForm: false }" class="space-y-6">

    <!-- HEADER & ACTION -->
    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div>
            <p class="text-[11px] font-bold text-[#1E5EFF] tracking-wider uppercase">JAM KERJA ASISTEN</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">Riwayat TimeSheet.</h1>
            <p class="text-xs text-gray-500 mt-1">
                Catat aktivitas asistensi setiap minggu dan pantau jam yang sudah disetujui.
            </p>
        </div>
        <div>
            <button type="button" 
                    @click="showForm = !showForm"
                    class="px-5 py-3 bg-[#1E5EFF] text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-xs inline-flex items-center gap-2 cursor-pointer">
                <span>+</span>
                <span>Tambah Timesheet</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs text-red-800 space-y-1" role="alert">
            <p class="font-bold">Periksa kembali data formulir timesheet:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 4 KARTU METRIK RINGKASAN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        
        <!-- Total entri -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-700">Total entri</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">{{ count($entries) }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Riwayat pada periode pilot</p>
            </div>
        </div>

        <!-- Menunggu review -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-700">Menunggu review</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm border border-amber-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">{{ $pendingCount }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Entri diajukan</p>
            </div>
        </div>

        <!-- Jam disetujui -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-700">Jam disetujui</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-sm border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">{{ $totalJamDisetujui ?: 7 }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Akumulasi jam disetujui</p>
            </div>
        </div>

        <!-- Sisa batas jam -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-700">Sisa batas jam</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm border border-purple-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">
                    {{ max(0, 40 - $totalJamDisetujui) }} <span class="text-sm font-normal text-gray-500">Jam</span>
                </p>
                <p class="text-[11px] text-gray-400 mt-1">Batas total: 40 jam · 40 - jam disetujui</p>
            </div>
        </div>

    </div>

    <!-- FORM TAMBAH TIMESHEET -->
    <div id="form-timesheet" x-show="showForm" x-collapse x-cloak class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-gray-900">Form Pengajuan Timesheet Mingguan</h2>
                <p class="text-xs text-gray-500">Isi data jam kerja asistensi untuk diverifikasi oleh dosen pengampu.</p>
            </div>
            <button type="button" @click="showForm = false" class="text-gray-400 hover:text-gray-600 text-xs font-semibold cursor-pointer">✕ Tutup Form</button>
        </div>

        <form action="{{ route('mahasiswa.timesheet.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            @csrf

            <!-- Mata kuliah -->
            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Mata kuliah *</label>
                <select name="matakuliah" required class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">Pilih mata kuliah</option>
                    @foreach($matakuliahAktif as $mk)
                        <option value="{{ $mk }}" @selected(old('matakuliah') === $mk)>{{ $mk }}</option>
                    @endforeach
                </select>
                @if(empty($matakuliahAktif))
                    <span class="mt-1 block text-[10px] text-amber-600">Belum ada lowongan aktif untuk dipilih.</span>
                @endif
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Tanggal kegiatan *</label>
                <input type="date" name="tanggal" required value="{{ old('tanggal', now()->toDateString()) }}" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <!-- Jumlah jam -->
            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Jumlah jam *</label>
                <input type="number" name="jam" required min="0.5" max="40" step="0.5" value="{{ old('jam', '3.5') }}" placeholder="Contoh: 3.5" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <span class="mt-1 block text-[10px] text-gray-400">Gunakan kelipatan 0,5 jam.</span>
            </div>

            <!-- Aktivitas -->
            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Aktivitas *</label>
                <input name="aktivitas" required maxlength="120" value="{{ old('aktivitas', 'Mendampingi Praktikum') }}" placeholder="Contoh: Mendampingi Praktikum" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block font-semibold text-gray-700 mb-1.5">Deskripsi pekerjaan *</label>
                <textarea name="deskripsi" required rows="3" maxlength="1000" placeholder="Jelaskan detail pendampingan mahasiswa di lab..." class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('deskripsi', 'Mendampingi sesi lab dan membantu mahasiswa memperbaiki error pada tugas.') }}</textarea>
            </div>

            <!-- Tombol Kirim -->
            <div class="md:col-span-2 flex justify-end gap-3 pt-2">
                <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-xs font-semibold hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-bold hover:bg-blue-700 shadow-xs cursor-pointer">
                    Ajukan Timesheet →
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL RINCIAN MINGGUAN -->
    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-xs overflow-hidden">
        
        <!-- Table Header Title -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-bold text-gray-900">Rincian Mingguan</h2>
                <span class="px-2.5 py-0.5 rounded-md bg-[#EDF5FF] text-[#1E5EFF] text-[11px] font-bold">
                    {{ count($entries) }} Ditampilkan
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-1">
                Setiap entri menampilkan mata kuliah agar konteks jam kerja tidak tertukar. Klik baris untuk melihat detail timesheet.
            </p>
        </div>

        <!-- Table Responsive -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#EBF3FF] text-[#2058B2] font-bold">
                    <tr>
                        <th class="px-6 py-4">Mata Kuliah</th>
                        <th class="px-6 py-4">Aktivitas</th>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">Jam</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($entries as $entry)
                        @php
                            $isDisetujui = $entry['status'] === 'Disetujui';
                            $isDitolak = $entry['status'] === 'Ditolak';
                        @endphp
                        <tr onclick="window.location.href='{{ route('mahasiswa.timesheet.show', $entry['id']) }}'" 
                            class="hover:bg-blue-50/50 cursor-pointer transition-colors"
                            title="Klik untuk melihat detail timesheet">
                            
                            <!-- Mata Kuliah -->
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900 text-xs">{{ $entry['course'] }}</p>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="px-1.5 py-0.5 rounded bg-blue-50 text-[#1E5EFF] font-bold text-[10px]">
                                        {{ $entry['code'] }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-medium">Kelas {{ $entry['class'] }}</span>
                                    <span class="text-[10px] text-gray-400">·</span>
                                    <span class="text-[10px] text-gray-400 font-medium">{{ $entry['sks'] }} SKS</span>
                                </div>
                            </td>

                            <!-- Aktivitas -->
                            <td class="px-6 py-4 max-w-xs">
                                <p class="font-bold text-gray-900">{{ $entry['title'] }}</p>
                                <p class="text-gray-500 mt-0.5 line-clamp-2 leading-relaxed text-[11px]">{{ $entry['activity'] }}</p>
                            </td>

                            <!-- Tanggal & Waktu -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-bold text-gray-900">{{ $entry['date'] }}</p>
                                <p class="text-gray-400 text-[10px] mt-0.5">{{ $entry['time_range'] }}</p>
                            </td>

                            <!-- Jam -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-[#1E5EFF] font-bold text-xs">
                                    <span>🕒</span>
                                    <span>{{ $entry['hours'] }} Jam</span>
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isDisetujui)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Disetujui</span>
                                    </span>
                                @elseif($isDitolak)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-700 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        <span>Ditolak</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Menunggu</span>
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-xs text-gray-400">
                                Belum ada timesheet. Ajukan aktivitas yang sudah dilakukan melalui tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
