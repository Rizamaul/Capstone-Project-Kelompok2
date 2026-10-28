@extends('layouts.mahasiswa')
@section('title', 'Pengumuman Lowongan')

@section('content')

{{-- ===== Header Halaman ===== --}}
<div class="mb-7">
    <p class="text-sm font-bold text-[#2058B2]">PENGUMUMAN REKRUTMEN</p>
    <h1 class="mt-2 text-2xl font-extrabold sm:text-3xl">Lowongan Asisten Praktikum</h1>
    <p class="mt-2 text-sm text-slate-500">Temukan mata kuliah yang sesuai dengan minat dan kemampuanmu.</p>
</div>

<div x-data="{ q: '', status: 'Semua', sort: 'Tenggat terdekat' }" class="space-y-5">

    {{-- ===== Filter Bar ===== --}}
    <div class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-[1fr_190px_190px]">

        {{-- Pencarian --}}
        <label class="relative block">
            <span class="absolute left-4 top-3 text-slate-400">⌕</span>
            <input x-model="q"
                   type="search"
                   placeholder="Cari mata kuliah atau kode..."
                   class="w-full rounded-xl border-slate-200 py-3 pl-10 text-sm focus:border-blue-500 focus:ring-blue-500">
        </label>

        {{-- Filter status --}}
        <select x-model="status" class="rounded-xl border-slate-200 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option>Semua</option>
            <option>Dibuka</option>
            <option>Ditutup</option>
        </select>

        {{-- Urutan --}}
        <select x-model="sort" class="rounded-xl border-slate-200 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option>Tenggat terdekat</option>
            <option>Nama A-Z</option>
            <option>Kuota terbanyak</option>
        </select>

    </div>

    {{-- ===== Info Jumlah & Status ===== --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500">
            Menampilkan
            <span class="font-bold text-slate-800"
                  x-text="document.querySelectorAll('[data-lowongan]:not([hidden])').length"></span>
            lowongan
        </p>
        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
            ● Rekrutmen aktif
        </span>
    </div>

    {{-- ===== Daftar Kartu Lowongan ===== --}}
    <div class="grid gap-5 xl:grid-cols-2">
        @foreach($lowongans as $lowongan)
        <article
            data-lowongan
            x-show="('{{ strtolower($lowongan['nama'] . ' ' . $lowongan['kode']) }}'.includes(q.toLowerCase()))
                     && (status === 'Semua' || status === '{{ $lowongan['status'] }}')"
            x-bind:style="sort === 'Nama A-Z' ? 'order: {{ strtolower($lowongan['nama']) }}' : ''"
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            {{-- Header kartu --}}
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold tracking-wide text-[#2058B2]">
                        {{ $lowongan['kode'] }} · {{ $lowongan['sks'] }} SKS
                    </p>
                    <h2 class="mt-2 text-xl font-extrabold">{{ $lowongan['nama'] }}</h2>
                    <p class="mt-1 text-sm text-slate-500">Dosen pengampu: {{ $lowongan['dosen'] }}</p>
                </div>
                <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold
                    {{ $lowongan['status'] === 'Dibuka' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                    {{ $lowongan['status'] }}
                </span>
            </div>

            {{-- Deskripsi --}}
            <p class="mt-4 text-sm leading-6 text-slate-600">{{ $lowongan['deskripsi'] }}</p>

            {{-- Detail singkat --}}
            <div class="mt-5 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-4">
                <div>
                    <p class="text-xs text-slate-500">Kuota</p>
                    <p class="mt-1 text-sm font-bold">{{ $lowongan['terisi'] }}/{{ $lowongan['kuota'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Tenggat</p>
                    <p class="mt-1 text-sm font-bold">{{ $lowongan['tenggat'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Jadwal</p>
                    <p class="mt-1 text-sm font-bold">{{ $lowongan['jadwal'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Lokasi</p>
                    <p class="mt-1 text-sm font-bold">Lab Komputer</p>
                </div>
            </div>

            {{-- Footer kartu --}}
            <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs text-slate-500">Syarat minimal: {{ $lowongan['syarat'] }}</p>
                <a href="{{ route('mahasiswa.lowongan.show', $lowongan['id']) }}"
                   class="rounded-xl bg-[#2058B2] px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800">
                    Lihat Detail →
                </a>
            </div>

        </article>
        @endforeach
    </div>

</div>

@endsection