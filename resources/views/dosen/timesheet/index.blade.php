@extends('layouts.dosen')

@section('content')
<div class="py-2 px-2 sm:px-4 max-w-[1400px] mx-auto space-y-6">

    <span class="sr-only">Review Timesheet</span>

    <!-- HERO TITLE CARD -->
    <div class="bg-white rounded-2xl border border-gray-100 p-7 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1E5EFF] tracking-tight">Timesheet Asisten Praktikum Dosen</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-normal">Pantau aktivitas asisten praktikum dosen secara real time dan terpusat</p>
        </div>
        @if($statistik['menunggu'] > 0)
            <form action="{{ route('dosen.timesheet.approve-pending') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui semua {{ $statistik['menunggu'] }} timesheet yang masih menunggu?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-6 py-3.5 bg-[#1E5EFF] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-xs flex items-center gap-2 whitespace-nowrap cursor-pointer">
                    <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    Setujui Semua
                </button>
            </form>
        @else
            <button disabled class="px-6 py-3.5 bg-gray-100 text-gray-400 rounded-xl text-sm font-semibold cursor-not-allowed flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                Setujui Semua
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <!-- 4 KARTU METRIK RINGKASAN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Kartu 1: Disetujui -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-medium text-gray-500">Disetujui</span>
                <span class="w-8 h-8 rounded-xl bg-[#E8F8F0] text-[#10B981] flex items-center justify-center font-bold text-sm">
                    <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $statistik['disetujui'] }}</h3>
                    <span class="text-xs text-gray-400 font-normal">Pengajuan</span>
                </div>
                <p class="text-xs text-[#10B981] font-medium mt-3">Daftar disetujui</p>
            </div>
        </div>

        <!-- Kartu 2: Menunggu -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-medium text-gray-500">Menunggu</span>
                <span class="w-8 h-8 rounded-xl bg-[#FEF6E8] text-[#F59E0B] flex items-center justify-center font-bold text-sm">
                    <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $statistik['menunggu'] }}</h3>
                    <span class="text-xs text-gray-400 font-normal">Pengajuan</span>
                </div>
                <p class="text-xs text-[#F59E0B] font-medium mt-3">Perlu tindakan dari dosen</p>
            </div>
        </div>

        <!-- Kartu 3: Total Jam -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between" x-data="{ periode: 'minggu' }">
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-medium text-gray-500">Total Jam</span>
                <span class="w-8 h-8 rounded-xl bg-[#EDF4FF] text-[#1E5EFF] flex items-center justify-center font-bold text-sm">
                    <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-3xl font-bold text-gray-900">{{ number_format($statistik['total_jam'], 0) }}</h3>
                        <span class="text-xs text-gray-400 font-normal">Jam</span>
                    </div>
                    <!-- Toggle Pill Minggu / Bulan -->
                    <div class="flex items-center rounded-lg border border-gray-100 p-0.5 text-[11px] bg-gray-50/80">
                        <button type="button" @click="periode = 'minggu'" :class="periode === 'minggu' ? 'bg-white text-[#1E5EFF] font-semibold shadow-xs border border-gray-200/60' : 'text-gray-400 font-normal'" class="px-2.5 py-0.5 rounded-md transition-all cursor-pointer">
                            Minggu
                        </button>
                        <button type="button" @click="periode = 'bulan'" :class="periode === 'bulan' ? 'bg-white text-[#1E5EFF] font-semibold shadow-xs border border-gray-200/60' : 'text-gray-400 font-normal'" class="px-2.5 py-0.5 rounded-md transition-all cursor-pointer">
                            Bulan
                        </button>
                    </div>
                </div>
                <p class="text-xs text-[#1E5EFF] font-medium mt-3">Perminggu : 10j | Bulan : 40j</p>
            </div>
        </div>

        <!-- Kartu 4: Estimasi Honor -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-medium text-gray-500">Estimasi Honor</span>
                <span class="w-8 h-8 rounded-xl bg-[#F4EDFF] text-[#8B5CF6] flex items-center justify-center font-bold text-sm">
                    <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-sm font-medium text-gray-600">Rp.</span>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">{{ number_format($statistik['estimasi_honor'], 0, ',', '.') }}</h3>
                </div>
                <div class="mt-3 inline-block bg-[#FBF9FE] border border-purple-100 rounded-lg px-2.5 py-1 text-[11px] text-gray-500">
                    SK Dekanat 2026 : <span class="text-gray-800 font-medium">Rp. 35.000 / Jam</span>
                </div>
            </div>
        </div>

    </div>

    <!-- TOOLBAR PENCARIAN, FILTER & EKSPOR -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-xs">
        <form action="{{ route('dosen.timesheet') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <!-- Pencarian -->
            <div class="relative flex-1 min-w-[240px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari pendaftar, matakuliah, atau berkas lainnya..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#1E5EFF] focus:ring-1 focus:ring-[#1E5EFF]">
            </div>

            <!-- Filter Status -->
            <div class="relative">
                <select name="status" onchange="this.form.submit()" class="appearance-none pl-4 pr-9 py-2.5 bg-[#EFF5FF] border border-blue-200/60 rounded-xl text-xs font-semibold text-[#1E5EFF] focus:outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Menunggu</option>
                    <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Disetujui</option>
                    <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Ditolak</option>
                </select>
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[#1E5EFF]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </div>

            <!-- Filter Semester -->
            <div class="relative">
                <select class="appearance-none pl-4 pr-9 py-2.5 bg-[#EFF5FF] border border-blue-200/60 rounded-xl text-xs font-semibold text-[#1E5EFF] focus:outline-none cursor-pointer">
                    <option>Semua Semester</option>
                    <option>Semester Ganjil 2026</option>
                    <option>Semester Genap 2025</option>
                </select>
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[#1E5EFF]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </div>

            <!-- Filter Matakuliah -->
            <div class="relative">
                <select class="appearance-none pl-4 pr-9 py-2.5 bg-[#EFF5FF] border border-blue-200/60 rounded-xl text-xs font-semibold text-[#1E5EFF] focus:outline-none cursor-pointer">
                    <option>Semua Matakuliah</option>
                    <option>Algoritma & Struktur Data</option>
                    <option>Pemrograman Dasar</option>
                    <option>Jaringan Komputer</option>
                </select>
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[#1E5EFF]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </div>

            <!-- Tombol Eksport PDF -->
            <button type="button" onclick="alert('Fitur ekspor PDF laporan sedang disiapkan.')" class="px-4 py-2.5 bg-[#EFF5FF] border border-blue-200/60 rounded-xl text-xs font-semibold text-[#1E5EFF] hover:bg-blue-100 transition-colors flex items-center gap-2 cursor-pointer">
                <span class="text-red-500 font-bold">📄</span>
                Eksport PDF
            </button>

            <!-- Tombol Refresh -->
            <a href="{{ route('dosen.timesheet') }}" title="Refresh Data" class="p-2.5 bg-white border border-gray-200 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </a>

        </form>
    </div>

    <!-- SECTION TITLE & TABEL -->
    <div class="space-y-4">
        
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Daftar Timesheet Asisten</h2>
            <span class="px-3 py-1 bg-[#EFF5FF] text-[#1E5EFF] rounded-lg text-xs font-bold">
                {{ $entries->total() }} Ditampilkan
            </span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#F0F5FD] border-b border-blue-100/50 text-[#1E5EFF] font-bold">
                            <th class="py-4 px-6">Nama Asisten</th>
                            <th class="py-4 px-6">Kode & Mata Kuliah</th>
                            <th class="py-4 px-6">Tanggal & Waktu</th>
                            <th class="py-4 px-6">Aktivitas</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($entries as $entry)
                            <tr 
                                onclick="window.location='{{ route('dosen.timesheet.show', $entry) }}'" 
                                title="Klik baris untuk melihat detail timesheet"
                                class="hover:bg-blue-50/40 transition-colors cursor-pointer group"
                            >
                                <!-- Nama Asisten -->
                                <td class="py-4 px-6 font-semibold">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white border border-blue-200 text-[#1E5EFF] flex items-center justify-center font-bold text-base shadow-2xs shrink-0 group-hover:border-[#1E5EFF] transition-colors">
                                            {{ strtoupper(substr($entry->asisten?->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-gray-900 font-bold text-sm group-hover:text-[#1E5EFF] transition-colors">{{ $entry->asisten?->name ?? 'Mahasiswa #' . $entry->asisten_id }}</p>
                                            <p class="text-[11px] text-gray-400 mt-0.5">NIM : {{ $entry->asisten?->nim ?? '12' . str_pad($entry->asisten_id, 7, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kode & Mata Kuliah -->
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900 text-sm">{{ $entry->mata_kuliah ?? 'Algoritma & Struktur Data' }}</p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="px-2 py-0.5 bg-[#DCEBFF] text-[#1E5EFF] rounded text-[10px] font-bold">IK2402</span>
                                        <span class="text-[11px] text-gray-400">Kelas 2402 | 3 SKS</span>
                                    </div>
                                </td>

                                <!-- Tanggal & Waktu -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <p class="font-bold text-gray-900 text-sm">{{ $entry->tanggal->translatedFormat('d M, Y') }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">08.00 - 11.30 WIB</p>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#EFF5FF] text-[#1E5EFF] rounded text-[10px] font-bold mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ number_format((float) $entry->jumlah_jam, 1, ',', '.') }} Jam
                                    </span>
                                </td>

                                <!-- Aktivitas -->
                                <td class="py-4 px-6 max-w-xs">
                                    <p class="font-bold text-gray-900 text-sm">{{ $entry->aktivitas ?? 'Praktikum Modul 1' }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $entry->uraian_pekerjaan }}</p>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @if($entry->status_approval === 'approved')
                                        <span class="px-3.5 py-1.5 bg-[#E8F8F0] text-[#10B981] rounded-full font-bold text-xs inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                                            Disetujui
                                        </span>
                                    @elseif($entry->status_approval === 'rejected')
                                        <span class="px-3.5 py-1.5 bg-red-50 text-red-600 rounded-full font-bold text-xs inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-3.5 py-1.5 bg-[#FEF6E8] text-[#D97706] rounded-full font-bold text-xs inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#D97706]"></span>
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-center whitespace-nowrap w-32" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($entry->status_approval === 'pending')
                                            <!-- Tombol Tolak / Revisi -->
                                            <a href="{{ route('dosen.timesheet.show', $entry) }}" title="Tolak & Berikan Catatan Revisi" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center font-bold text-sm transition-colors shadow-2xs">
                                                ✕
                                            </a>

                                            <!-- Tombol Setujui -->
                                            <form action="{{ route('dosen.timesheet.status', $entry) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" title="Setujui Pengajuan" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center font-bold text-sm transition-colors shadow-2xs cursor-pointer">
                                                    ✓
                                                </button>
                                            </form>
                                        @else
                                            <!-- Tombol Detail Rapi untuk Status Selesai -->
                                            <a href="{{ route('dosen.timesheet.show', $entry) }}" class="h-8 px-3 rounded-lg bg-gray-50 border border-gray-200 text-gray-500 hover:bg-blue-50 hover:text-[#1E5EFF] hover:border-blue-200 text-xs font-semibold transition-colors inline-flex items-center gap-1">
                                                <span>Detail</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-6 text-center">
                                    <p class="text-sm font-bold text-gray-700">Belum ada timesheet asisten.</p>
                                    <p class="text-xs text-gray-400 mt-1">Pengajuan timesheet asisten akan tampil di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER PAGINASI -->
            <div class="p-4 border-t border-gray-100 flex items-center justify-between bg-white text-xs">
                <span class="text-[#1E5EFF] font-semibold">
                    Menampilkan {{ $entries->count() }} dari {{ $entries->total() }} daftar
                </span>

                <div class="flex items-center gap-1">
                    @if($entries->onFirstPage())
                        <span class="w-8 h-8 rounded-xl border border-gray-200 text-gray-300 flex items-center justify-center font-bold cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $entries->previousPageUrl() }}" class="w-8 h-8 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 flex items-center justify-center font-bold">‹</a>
                    @endif

                    <span class="w-8 h-8 rounded-xl border border-blue-200 bg-blue-50/50 text-[#1E5EFF] flex items-center justify-center font-bold">
                        {{ $entries->currentPage() }}
                    </span>

                    @if($entries->hasMorePages())
                        <a href="{{ $entries->nextPageUrl() }}" class="w-8 h-8 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 flex items-center justify-center font-bold">›</a>
                    @else
                        <span class="w-8 h-8 rounded-xl border border-gray-200 text-gray-300 flex items-center justify-center font-bold cursor-not-allowed">›</span>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
