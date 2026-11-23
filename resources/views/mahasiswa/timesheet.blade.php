@extends('layouts.mahasiswa')
@section('title', 'TimeSheet - Mahasiswa')

@section('content')
<div x-data="{ 
    addModalOpen: false, 
    rejectionModalOpen: false, 
    rejectedCourse: '', 
    rejectionNote: '',
    openRejectionModal(course, note) {
        this.rejectedCourse = course;
        this.rejectionNote = note;
        this.rejectionModalOpen = true;
    }
}" class="space-y-6">

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
                    @click="addModalOpen = true"
                    class="px-5 py-3 bg-[#1E5EFF] text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-xs inline-flex items-center gap-2 cursor-pointer">
                <span class="text-sm font-black">+</span>
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-900">{{ max(0, 40 - $totalJamDisetujui) }} <span class="text-xs font-semibold text-gray-400">Jam</span></p>
                <p class="text-[11px] text-gray-400 mt-1">Batas total: 40 jam - {{ $totalJamDisetujui ?: 40 }} - jam disetujui</p>
            </div>
        </div>

    </div>

    <!-- TABEL RINCIAN MINGGUAN -->
    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span>Rincian Mingguan</span>
                    <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-[#1E5EFF] font-bold text-xs">
                        {{ count($entries) }} Ditampilkan
                    </span>
                </h2>
                <p class="text-xs text-gray-400 mt-1">
                    Setiap entri menampilkan mata kuliah agar konteks jam kerja tidak tertukar.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#F4F8FF] text-gray-700 font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Mata Kuliah</th>
                        <th class="px-6 py-4">Aktivitas</th>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">Jam</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($entries as $entry)
                        @php
                            $isDisetujui = $entry['status'] === 'Disetujui';
                            $isDitolak = $entry['status'] === 'Ditolak';
                        @endphp
                        <tr onclick="window.location.href='{{ route('mahasiswa.timesheet.show', $entry['id']) }}'" 
                            class="hover:bg-blue-50/40 cursor-pointer transition-colors"
                            title="Klik untuk melihat detail timesheet">
                            
                            <!-- Mata Kuliah -->
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900 text-xs">{{ $entry['course'] }}</p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="px-2 py-0.5 rounded bg-blue-100/70 text-[#1E5EFF] font-bold text-[10px]">
                                        {{ $entry['code'] }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 font-medium">Kelas {{ $entry['class'] }}</span>
                                    <span class="text-[11px] text-gray-400 font-medium">{{ $entry['sks'] }} SKS</span>
                                </div>
                            </td>

                            <!-- Aktivitas -->
                            <td class="px-6 py-4 max-w-xs sm:max-w-sm">
                                <p class="font-bold text-gray-900">{{ $entry['title'] }}</p>
                                <p class="text-gray-500 mt-0.5 line-clamp-2 leading-relaxed text-[11px]">{{ $entry['activity'] }}</p>
                            </td>

                            <!-- Tanggal & Waktu -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-bold text-gray-900">{{ $entry['date'] }}</p>
                                <p class="text-gray-400 text-[11px] mt-0.5">{{ $entry['time_range'] }}</p>
                            </td>

                            <!-- Jam -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 border border-blue-100/80 text-[#1E5EFF] font-bold text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $entry['hours'] }} Jam</span>
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isDisetujui)
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#EBF9F1] text-[#22C55E] font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E]"></span>
                                        <span>Disetujui</span>
                                    </span>
                                @elseif($isDitolak)
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#FEECEC] text-[#EF4444] font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
                                        <span>Ditolak</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Menunggu</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Keterangan -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isDitolak)
                                    <button type="button" 
                                            @click.stop="openRejectionModal(@js($entry['course']), @js($entry['catatan_revisi']))"
                                            class="font-bold text-gray-900 hover:text-[#1E5EFF] inline-flex items-center gap-1 text-xs cursor-pointer">
                                        <span>Detail</span>
                                        <span>&rarr;</span>
                                    </button>
                                @else
                                    <span class="text-gray-400 font-bold text-xs">-</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-xs text-gray-400">
                                Belum ada timesheet. Ajukan aktivitas yang sudah dilakukan melalui tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- MODAL POPUP: KETERANGAN ALASAN DITOLAK -->
    <div x-show="rejectionModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs transition-opacity"
         @keydown.escape.window="rejectionModalOpen = false">
        
        <div @click.away="rejectionModalOpen = false"
             class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-gray-100 space-y-5 animate-in fade-in zoom-in-95 duration-150">
            
            <div>
                <h3 class="text-base font-extrabold text-gray-900">Keterangan Alasan ditolak</h3>
                <p class="text-xs text-gray-400 mt-0.5">Catatan penolakan dari dosen penanggung jawab.</p>
            </div>

            <!-- Box Catatan -->
            <div class="bg-[#FFF9EB] border border-[#FDE68A] rounded-2xl p-5 space-y-2">
                <p class="text-xs font-bold text-[#92400E]">Catatan Dosen:</p>
                <p class="text-xs text-[#92400E]/90 leading-relaxed font-medium" x-text="rejectionNote"></p>
            </div>

            <div class="pt-2">
                <button type="button" 
                        @click="rejectionModalOpen = false"
                        class="w-full py-3 bg-[#1E5EFF] hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                    Kembali
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP: FORM TAMBAH TIMESHEET -->
    <div x-show="addModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs transition-opacity"
         @keydown.escape.window="addModalOpen = false">
        
        <div @click.away="addModalOpen = false"
             class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-gray-100 space-y-6 max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900">Tambah Timesheet Baru</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Catat aktivitas asistensi praktikum untuk direview oleh dosen.</p>
                </div>
                <button type="button" @click="addModalOpen = false" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">✕</button>
            </div>

            <form action="{{ route('mahasiswa.timesheet.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Mata Kuliah</label>
                    <select name="matakuliah" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF] bg-white">
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($matakuliahAktif as $mk)
                            <option value="{{ $mk }}">{{ $mk }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Jam</label>
                        <input type="number" step="0.5" min="0.5" max="40" name="jam" value="{{ old('jam', '3.5') }}" required placeholder="Contoh: 3.5" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Aktivitas Utama</label>
                    <input type="text" name="aktivitas" value="{{ old('aktivitas') }}" required placeholder="Contoh: Mendampingi Praktikum / Memeriksa Laporan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Uraian / Deskripsi Pekerjaan</label>
                    <textarea name="deskripsi" rows="3" required placeholder="Jelaskan secara ringkas aktivitas dan kendala/hasil asistensi..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#1E5EFF] hover:bg-blue-700 text-white text-xs font-bold transition-colors shadow-xs cursor-pointer">
                        Ajukan Timesheet
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
