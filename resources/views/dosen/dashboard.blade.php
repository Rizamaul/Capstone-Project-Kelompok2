@extends('layouts.dosen')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16">
    <header class="flex flex-col justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-[#2058B2]">Dashboard Rekrutmen Asisten</h1>
            <p class="mt-1 text-sm text-gray-500">Ringkasan pengumuman, pendaftar, dan timesheet berdasarkan data tersimpan.</p>
        </div>
        <a href="{{ route('dosen.lowongan.create') }}" class="rounded-xl bg-[#2058B2] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-blue-800">
            + Buat pengumuman
        </a>
    </header>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5" aria-label="Ringkasan rekrutmen">
        <article class="rounded-2xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-semibold text-gray-500">Pengumuman aktif</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $statistik['pengumuman_aktif'] }}</p>
        </article>
        <article class="rounded-2xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-semibold text-gray-500">Total pendaftar</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $statistik['total_pendaftar'] }}</p>
        </article>
        <article class="rounded-2xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-semibold text-gray-500">Kuota terisi</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $statistik['kuota_terisi'] }}<span class="text-lg font-semibold text-gray-400"> / {{ $statistik['kuota_total'] }}</span></p>
        </article>
        <article class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-sm font-semibold text-amber-800">Perlu review berkas</p>
            <p class="mt-2 text-3xl font-bold text-amber-900">{{ $statistik['review_berkas'] }}</p>
        </article>
        <article class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
            <p class="text-sm font-semibold text-blue-800">Timesheet menunggu</p>
            <p class="mt-2 text-3xl font-bold text-blue-900">{{ $statistik['timesheet_menunggu'] }}</p>
        </article>
    </section>

    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col justify-between gap-3 border-b border-gray-100 p-6 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Pengumuman terbaru</h2>
                <p class="mt-1 text-sm text-gray-500">Kelola lowongan dan tinjau pelamar yang masuk.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dosen.lowongan.index') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Semua lowongan</a>
                <a href="{{ route('dosen.pendaftar.index') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Tinjau pendaftar</a>
                <a href="{{ route('dosen.timesheet') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Review timesheet</a>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($lowongans as $lowongan)
                <article class="flex flex-col justify-between gap-4 p-6 sm:flex-row sm:items-center">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-gray-900">{{ $lowongan->mata_kuliah }}</h3>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $lowongan->status_aktif && $lowongan->tenggat?->isFuture() ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $lowongan->status_aktif && $lowongan->tenggat?->isFuture() ? 'Dibuka' : 'Ditutup' }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">{{ $lowongan->kode ?? 'MK' }} · {{ $lowongan->sks }} SKS · Tenggat {{ $lowongan->tenggat?->translatedFormat('d M Y') ?? 'Belum ditentukan' }}</p>
                        <p class="mt-2 text-sm text-gray-600">{{ $lowongan->pendaftarans_count }} pendaftar · {{ $lowongan->review_count }} belum ditinjau · {{ $lowongan->terisi }}/{{ $lowongan->kuota }} kuota terisi</p>
                    </div>
                    <div class="flex shrink-0 gap-2">
                        <a href="{{ route('dosen.lowongan.show', $lowongan) }}" class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-semibold text-[#2058B2] hover:bg-blue-100">Detail</a>
                        <a href="{{ route('dosen.lowongan.edit', $lowongan) }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Edit</a>
                    </div>
                </article>
            @empty
                <div class="p-10 text-center">
                    <p class="font-semibold text-gray-900">Belum ada pengumuman</p>
                    <p class="mt-1 text-sm text-gray-500">Buat pengumuman pertama untuk mulai menerima pendaftar.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
