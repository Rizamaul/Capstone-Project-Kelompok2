@extends('layouts.dosen')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto" x-data="{ showRejectModal: false, showApproveModal: false }">

    <!-- KEMBALI -->
    <a href="{{ route('dosen.timesheet') }}" class="text-xs font-semibold text-[#1E5EFF] hover:underline mb-4 inline-block">
        ← Kembali ke Daftar Timesheet
    </a>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- HEADER DETAIL -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 mb-6 shadow-sm">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Detail Timesheet Asisten Praktikum Dosen</h1>
        <p class="text-xs text-gray-500">Periksa detail nama, tanggal, jumlah jam, mata kuliah, dan aktivitas sebelum mengambil keputusan.</p>
    </div>

    <!-- KARTU RINCIAN -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-6 mb-6 text-xs">

        <!-- Info Mahasiswa -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr($entry->asisten?->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $entry->asisten?->name ?? 'Mahasiswa #' . $entry->asisten_id }}</p>
                    <p class="text-gray-400">NIM : {{ $entry->asisten?->nim ?? '12' . str_pad($entry->asisten_id, 7, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full font-semibold {{ match ($entry->status_approval) {'approved' => 'bg-emerald-50 text-emerald-700', 'rejected' => 'bg-red-50 text-red-700', default => 'bg-amber-50 text-amber-600'} }}">
                {{ match ($entry->status_approval) {'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Menunggu'} }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Mata Kuliah</span>
            <span class="font-semibold text-gray-800 text-right">{{ $entry->mata_kuliah ?? 'Algoritma & Struktur Data' }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Kode Mata Kuliah</span>
            <span class="font-mono font-semibold text-gray-800 text-right">IK2402</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Kelas</span>
            <span class="font-semibold text-gray-800 text-right">2402</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Total SKS</span>
            <span class="font-semibold text-gray-800 text-right">3 SKS</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Tanggal Aktivitas</span>
            <span class="font-semibold text-gray-800 text-right">{{ $entry->tanggal->translatedFormat('d M, Y') }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Jumlah Jam</span>
            <span class="font-semibold text-blue-600 text-right">{{ number_format((float) $entry->jumlah_jam, 1, ',', '.') }} Jam</span>
        </div>
        <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
            <span class="text-gray-500">Aktivitas Utama</span>
            <span class="font-semibold text-gray-800 text-right">{{ $entry->aktivitas ?? 'Aktivitas Praktikum' }}</span>
        </div>

        <div>
            <p class="font-bold text-gray-800 mb-2">Uraian / Deskripsi Pekerjaan</p>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-gray-600 space-y-1">
                <p class="whitespace-pre-line text-gray-700 leading-relaxed">{{ $entry->uraian_pekerjaan }}</p>
            </div>
        </div>

        @if($entry->catatan_revisi)
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                <h3 class="font-bold text-red-900 text-xs mb-1">Catatan Revisi dari Dosen:</h3>
                <p class="whitespace-pre-line text-xs text-red-800">{{ $entry->catatan_revisi }}</p>
            </div>
        @endif
    </div>

    <!-- TOMBOL AKSI KEPUTUSAN -->
    @if($entry->status_approval === 'pending')
        <div class="flex items-center justify-end gap-3">
            <button type="button" @click="showRejectModal = true" class="px-6 py-3 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition-colors shadow-sm cursor-pointer">
                ✕ Ditolak
            </button>
            <button type="button" @click="showApproveModal = true" class="px-6 py-3 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm cursor-pointer">
                ✓ Disetujui
            </button>
        </div>

        <!-- MODAL TOLAK TIMESHEET -->
        <div x-show="showRejectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4" x-cloak>
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4" @click.away="showRejectModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm">Berikan Alasan Penolakan</h3>
                    <button type="button" @click="showRejectModal = false" class="text-gray-400 hover:text-gray-600 font-bold">✕</button>
                </div>
                
                <form action="{{ route('dosen.timesheet.status', $entry) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    
                    <textarea name="catatan_revisi" required rows="4" placeholder="Masukan alasan penolakan untuk asisten praktikum..." class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-xs text-gray-800 focus:outline-none focus:border-red-500"></textarea>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showRejectModal = false" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-semibold hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-xl text-xs font-semibold hover:bg-red-700">
                            Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL KONFIRMASI PERSETUJUAN -->
        <div x-show="showApproveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4" x-cloak>
            <div class="bg-white rounded-3xl max-w-md w-full p-8 text-center shadow-2xl" @click.away="showApproveModal = false">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-[#1E5EFF]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-6">Apakah Anda yakin ingin Mengirim Persetujuan tersebut?</h3>
                
                <form action="{{ route('dosen.timesheet.status', $entry) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">

                    <div class="flex items-center justify-center gap-3">
                        <button type="button" @click="showApproveModal = false" class="w-1/2 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="w-1/2 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-bold shadow-sm hover:bg-blue-700">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection
