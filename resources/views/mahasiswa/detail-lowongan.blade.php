@extends('layouts.mahasiswa')
@section('title', 'Detail Lowongan')

@section('content')

{{-- Tombol kembali --}}
<a href="{{ route('mahasiswa.lowongan.index') }}"
   class="text-sm font-bold text-[#2058B2]">
    ← Kembali ke pengumuman
</a>

<div class="mt-5 grid gap-6 xl:grid-cols-[1fr_340px]">

    {{-- ===== Konten Utama ===== --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">

        {{-- Badge & Judul --}}
        <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-[#2058B2]">
            {{ $lowongan['kode'] }} · {{ $lowongan['sks'] }} SKS
        </span>
        <h1 class="mt-4 text-2xl font-extrabold sm:text-3xl">{{ $lowongan['nama'] }}</h1>
        <p class="mt-2 text-sm text-slate-500">
            Rekrutmen asisten praktikum · {{ $lowongan['dosen'] }}
        </p>

        <div class="my-7 border-t border-slate-100"></div>

        {{-- Tentang Mata Kuliah --}}
        <h2 class="text-lg font-extrabold">Tentang mata kuliah</h2>
        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $lowongan['deskripsi'] }}</p>

        {{-- Syarat --}}
        <h2 class="mt-7 text-lg font-extrabold">Syarat pendaftaran</h2>
        <div class="mt-3 space-y-2 text-sm leading-6 text-slate-600">
            @if($lowongan['syarat_nilai'])
                <p><span class="font-semibold">Nilai minimum:</span> {{ $lowongan['syarat_nilai'] }}</p>
            @endif
            @if($lowongan['syarat_ipk'])
                <p><span class="font-semibold">IPK minimum:</span> {{ number_format((float) $lowongan['syarat_ipk'], 2, ',', '.') }}</p>
            @endif
            <p class="whitespace-pre-line">{{ $lowongan['persyaratan'] ?: 'Tidak ada persyaratan tambahan.' }}</p>
        </div>

        {{-- Dokumen --}}
        <h2 class="mt-7 text-lg font-extrabold">Dokumen yang diperlukan</h2>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            @forelse($lowongan['berkas_diminta'] as $doc)
            <div class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">
                <span class="rounded-lg bg-red-50 p-2 text-sm font-extrabold text-red-600">PDF</span>
                <span class="text-sm font-semibold">{{ $doc }}</span>
            </div>
            @empty
                <p class="text-sm text-slate-500">Tidak ada dokumen tambahan yang diminta.</p>
            @endforelse
        </div>

    </section>

    {{-- ===== Sidebar Ringkasan ===== --}}
    <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        {{-- Status --}}
        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $lowongan['status'] === 'Dibuka' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
            {{ $lowongan['status'] }}
        </span>

        <h2 class="mt-5 text-lg font-extrabold">Ringkasan Lowongan</h2>

        {{-- Detail list --}}
        <dl class="mt-4 space-y-4 text-sm">
            @foreach([
                ['Tenggat',        $lowongan['tenggat']],
                ['Kuota',          $lowongan['terisi'] . ' / ' . $lowongan['kuota'] . ' asisten'],
                ['Dosen Pengampu', $lowongan['dosen']],
                ['Jadwal',         $lowongan['jadwal']],
                ['Kelas',          $lowongan['kelas'] ?? 'Belum ditentukan'],
                ['Semester',       $lowongan['semester'] ?? 'Belum ditentukan'],
            ] as $row)
            <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                <dt class="text-slate-500">{{ $row[0] }}</dt>
                <dd class="text-right font-bold">{{ $row[1] }}</dd>
            </div>
            @endforeach
        </dl>

        {{-- Tombol aksi --}}
        @if($lowongan['status'] === 'Dibuka')
            <a href="{{ route('mahasiswa.pendaftaran.create', $lowongan['id']) }}"
               class="mt-6 block rounded-xl bg-[#2058B2] px-4 py-3 text-center text-sm font-bold text-white hover:bg-blue-800">
                Daftar Sekarang →
            </a>
        @else
            <button disabled
                    class="mt-6 w-full rounded-xl bg-slate-200 px-4 py-3 text-sm font-bold text-slate-500">
                Pendaftaran Ditutup
            </button>
        @endif

        <p class="mt-3 text-xs leading-5 text-slate-500">
            Pastikan semua data dan dokumen sudah benar sebelum dikirim.
        </p>

    </aside>

</div>

@endsection