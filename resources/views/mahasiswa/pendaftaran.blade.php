@extends('layouts.mahasiswa')
@section('title', 'Form Pendaftaran')

@section('content')

{{-- Tombol kembali --}}
<a href="{{ route('mahasiswa.lowongan.show', $lowongan['id']) }}"
   class="text-sm font-bold text-[#2058B2]">
    ← Kembali ke detail lowongan
</a>

<div class="mx-auto mt-5 max-w-3xl">

    {{-- ===== Header Form ===== --}}
    <div class="mb-6">
        <p class="text-xs font-bold tracking-[.18em] text-[#2058B2]">FORMULIR PENDAFTARAN</p>
        <h1 class="mt-2 text-2xl font-extrabold">Daftar Asisten {{ $lowongan['nama'] }}</h1>
        <p class="mt-2 text-sm text-slate-500">
            Lengkapi data berikut dengan benar. Kolom bertanda * wajib diisi.
        </p>
    </div>

    <form action="{{ route('mahasiswa.pendaftaran.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf
        <input type="hidden" name="lowongan_id" value="{{ $lowongan['id'] }}">

        {{-- ===== Bagian 1: Informasi Akademik ===== --}}
        <div>
            <h2 class="font-extrabold">Informasi Akademik</h2>
            <p class="mt-1 text-sm text-slate-500">
                Nilai mata kuliah menjadi salah satu informasi untuk proses seleksi.
            </p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">

            {{-- Nama --}}
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Nama lengkap</span>
                <input name="nama"
                       required
                       value="{{ old('nama') }}"
                       placeholder="Masukkan nama lengkap"
                       class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                @error('nama')
                    <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            {{-- NIM --}}
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">NIM</span>
                <input name="nim"
                       required
                       value="{{ old('nim') }}"
                       placeholder="Contoh: 105222056"
                       class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            </label>

            {{-- Nilai Matkul --}}
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Nilai {{ $lowongan['nama'] }} *</span>
                <select name="nilai_matkul"
                        required
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih nilai</option>
                    @foreach(['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'E'] as $nilai)
                        <option value="{{ $nilai }}" @selected(old('nilai_matkul') === $nilai)>
                            {{ $nilai }}
                        </option>
                    @endforeach
                </select>
            </label>

            {{-- IPK --}}
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">IPK (opsional)</span>
                <input type="number"
                       name="ipk"
                       min="0" max="4" step="0.01"
                       value="{{ old('ipk') }}"
                       placeholder="Contoh: 3.65"
                       class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            </label>

        </div>

        {{-- ===== Bagian 2: Motivasi & Portofolio ===== --}}
        <div class="border-t border-slate-100 pt-6">
            <h2 class="font-extrabold">Motivasi dan Portofolio</h2>

            {{-- Motivasi --}}
            <label class="mt-4 block">
                <span class="mb-2 block text-sm font-semibold">Motivasi menjadi asisten *</span>
                <textarea name="motivasi"
                          rows="5"
                          minlength="50"
                          maxlength="1500"
                          required
                          placeholder="Ceritakan alasan dan kontribusi yang ingin kamu berikan (min. 50 karakter)..."
                          class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('motivasi') }}</textarea>
                <span class="mt-1 block text-xs text-slate-500">50–1.500 karakter.</span>
            </label>

            {{-- Portofolio --}}
            <label class="mt-4 block">
                <span class="mb-2 block text-sm font-semibold">Tautan portofolio / GitHub (opsional)</span>
                <input type="url"
                       name="portfolio"
                       value="{{ old('portfolio') }}"
                       placeholder="https://github.com/username"
                       class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            </label>
        </div>

        {{-- ===== Bagian 3: Dokumen ===== --}}
        <div class="border-t border-slate-100 pt-6">
            <h2 class="font-extrabold">Unggah Dokumen PDF</h2>
            <p class="mt-1 text-sm text-slate-500">Format PDF, maksimal 2 MB per berkas.</p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">

                {{-- CV --}}
                <label class="block rounded-xl border border-dashed border-slate-300 p-4">
                    <span class="mb-2 block text-sm font-semibold">CV / Resume *</span>
                    <input type="file"
                           name="cv"
                           required
                           accept="application/pdf,.pdf"
                           class="block w-full text-xs text-slate-500
                                  file:mr-3 file:rounded-lg file:border-0
                                  file:bg-blue-50 file:px-3 file:py-2
                                  file:text-xs file:font-bold file:text-[#2058B2]">
                    <span class="mt-2 block text-xs text-slate-400">PDF · Maks. 2 MB</span>
                </label>

                {{-- Transkrip --}}
                <label class="block rounded-xl border border-dashed border-slate-300 p-4">
                    <span class="mb-2 block text-sm font-semibold">Transkrip nilai *</span>
                    <input type="file"
                           name="transkrip"
                           required
                           accept="application/pdf,.pdf"
                           class="block w-full text-xs text-slate-500
                                  file:mr-3 file:rounded-lg file:border-0
                                  file:bg-blue-50 file:px-3 file:py-2
                                  file:text-xs file:font-bold file:text-[#2058B2]">
                    <span class="mt-2 block text-xs text-slate-400">PDF · Maks. 2 MB</span>
                </label>

            </div>
        </div>

        {{-- ===== Persetujuan ===== --}}
        <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 text-sm leading-6">
            <input type="checkbox"
                   name="persetujuan"
                   value="1"
                   required
                   class="mt-1 rounded border-slate-300 text-[#2058B2] focus:ring-blue-500">
            <span>
                Saya menyatakan data dan dokumen yang diberikan benar, serta bersedia mengikuti
                proses seleksi asisten praktikum.
            </span>
        </label>

        {{-- ===== Tombol Submit ===== --}}
        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('mahasiswa.lowongan.show', $lowongan['id']) }}"
               class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-bold text-slate-600 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                    class="rounded-xl bg-[#2058B2] px-6 py-3 text-sm font-bold text-white hover:bg-blue-800">
                Kirim Pendaftaran →
            </button>
        </div>

    </form>

</div>

@endsection