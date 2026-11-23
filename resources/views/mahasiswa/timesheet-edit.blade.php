@extends('layouts.mahasiswa')
@section('title', 'Edit Timesheet')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- NAVIGASI KEMBALI -->
    <div>
        <a href="{{ route('mahasiswa.timesheet.show', $entry->id) }}" class="text-xs font-semibold text-[#1E5EFF] hover:underline inline-flex items-center gap-1 mb-2">
            <span>← Kembali ke Detail Timesheet</span>
        </a>
        <div>
            <span class="text-[11px] font-bold text-[#1E5EFF] tracking-wider uppercase">PERBARUI TIMESHEET</span>
            <h1 class="text-2xl font-extrabold text-gray-900 mt-1">Edit Data Jam Kerja Asistensi</h1>
            <p class="text-xs text-gray-500 mt-1">Perbarui rincian aktivitas, jam pelaksanaan, atau catatan tugas asistensi kamu.</p>
        </div>
    </div>

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

    <!-- JIKA SEBELUMNYA DITOLAK: TAMPILKAN CATATAN REVISI DARI DOSEN -->
    @if($entry->status_approval === 'rejected' || $entry->catatan_revisi)
        <div class="rounded-2xl border border-amber-200 bg-[#FFF9EB] p-5 space-y-2">
            <div class="flex items-center gap-2">
                <span class="text-base">⚠️</span>
                <h3 class="font-bold text-amber-900 text-xs">Catatan Dosen Terkait Revisi:</h3>
            </div>
            <p class="text-xs text-amber-900/90 leading-relaxed font-medium">
                {{ $entry->catatan_revisi ?: 'Hasil pemeriksaan laporan kurang lengkap dan format rangkuman pertanyaan belum sesuai.' }}
            </p>
        </div>
    @endif

    <!-- FORM EDIT -->
    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-8 shadow-xs">
        <form action="{{ route('mahasiswa.timesheet.update', $entry->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Mata Kuliah -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Mata Kuliah</label>
                <select name="matakuliah" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF] bg-white">
                    @foreach($matakuliahAktif as $mk)
                        <option value="{{ $mk }}" {{ (old('matakuliah', $entry->mata_kuliah) === $mk || str_contains($mk, $courseName)) ? 'selected' : '' }}>
                            {{ $mk }}
                        </option>
                    @endforeach
                    @if(!in_array($entry->mata_kuliah, $matakuliahAktif))
                        <option value="{{ $entry->mata_kuliah }}" selected>{{ $entry->mata_kuliah }}</option>
                    @endif
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Pilih mata kuliah praktikum tempat kamu bertugas sebagai asisten.</p>
            </div>

            <!-- Tanggal & Jam -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Pelaksanaan</label>
                    <input type="date" 
                           name="tanggal" 
                           value="{{ old('tanggal', $entry->tanggal->toDateString()) }}" 
                           max="{{ now()->toDateString() }}" 
                           required 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">
                    <p class="text-[11px] text-gray-400 mt-1">Tanggal kegiatan asistensi.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Jumlah Jam</label>
                    <input type="number" 
                           step="0.5" 
                           min="0.5" 
                           max="40" 
                           name="jam" 
                           value="{{ old('jam', (float) $entry->jumlah_jam) }}" 
                           required 
                           placeholder="Contoh: 3.5" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">
                    <p class="text-[11px] text-gray-400 mt-1">Kelipatan 0.5 jam (misal: 1.5, 2, 3.5).</p>
                </div>
            </div>

            <!-- Aktivitas Utama -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Aktivitas Utama</label>
                <input type="text" 
                       name="aktivitas" 
                       value="{{ old('aktivitas', $entry->aktivitas) }}" 
                       required 
                       placeholder="Contoh: Mendampingi Praktikum / Memeriksa Laporan" 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF]">
                <p class="text-[11px] text-gray-400 mt-1">Ringkasan judul kegiatan yang dilakukan.</p>
            </div>

            <!-- Deskripsi / Uraian Pekerjaan -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Uraian / Deskripsi Pekerjaan</label>
                <textarea name="deskripsi" 
                          rows="4" 
                          required 
                          placeholder="Jelaskan secara detail hasil pelaksanaan asistensi, materi modul yang diajarkan, atau kendala praktikan..." 
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1E5EFF] leading-relaxed">{{ old('deskripsi', $entry->uraian_pekerjaan) }}</textarea>
                <p class="text-[11px] text-gray-400 mt-1">Deskripsi lengkap agar dosen pengampu dapat memvalidasi log kerja kamu.</p>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('mahasiswa.timesheet.show', $entry->id) }}" 
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#1E5EFF] hover:bg-blue-700 text-white text-xs font-bold transition-colors shadow-xs cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
