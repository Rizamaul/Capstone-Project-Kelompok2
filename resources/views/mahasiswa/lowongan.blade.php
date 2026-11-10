@extends('layouts.mahasiswa')
@section('title', 'Pengumuman Lowongan')

@section('content')
<div class="space-y-5"
     x-data="{
        lowongans: {{ Js::from($lowongans) }},
        q: '',
        status: 'Semua',
        sort: 'Tenggat terdekat',
        detailUrl: @js(route('mahasiswa.lowongan.show', ['id' => '__ID__'])),
        get filteredLowongans() {
            return this.lowongans
                .filter(lowongan => {
                    const query = this.q.trim().toLowerCase();
                    const matchesQuery = !query || `${lowongan.nama} ${lowongan.kode}`.toLowerCase().includes(query);

                    return matchesQuery && (this.status === 'Semua' || this.status === lowongan.status);
                })
                .sort((a, b) => {
                    if (this.sort === 'Nama A-Z') {
                        return a.nama.localeCompare(b.nama);
                    }

                    if (this.sort === 'Kuota terbanyak') {
                        return b.kuota - a.kuota;
                    }

                    return new Date(a.tenggat_iso || '9999-12-31') - new Date(b.tenggat_iso || '9999-12-31');
                });
        }
     }">
    <header>
        <p class="text-sm font-bold text-[#2058B2]">PENGUMUMAN REKRUTMEN</p>
        <h1 class="mt-2 text-2xl font-extrabold sm:text-3xl">Lowongan Asisten Praktikum</h1>
        <p class="mt-2 text-sm text-slate-500">Temukan mata kuliah yang sesuai dengan minat dan kemampuanmu.</p>
    </header>

    <section class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-[1fr_190px_190px]">
        <label class="relative block">
            <span class="sr-only">Cari mata kuliah atau kode</span>
            <span class="absolute left-4 top-3 text-slate-400" aria-hidden="true">⌕</span>
            <input x-model="q" type="search" placeholder="Cari mata kuliah atau kode..." class="w-full rounded-xl border-slate-200 py-3 pl-10 text-sm focus:border-blue-500 focus:ring-blue-500">
        </label>
        <label>
            <span class="sr-only">Filter status</span>
            <select x-model="status" class="w-full rounded-xl border-slate-200 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option>Semua</option>
                <option>Dibuka</option>
                <option>Ditutup</option>
            </select>
        </label>
        <label>
            <span class="sr-only">Urutkan lowongan</span>
            <select x-model="sort" class="w-full rounded-xl border-slate-200 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option>Tenggat terdekat</option>
                <option>Nama A-Z</option>
                <option>Kuota terbanyak</option>
            </select>
        </label>
    </section>

    <div class="flex items-center justify-between gap-3">
        <p class="text-sm text-slate-500">
            Menampilkan <span class="font-bold text-slate-800" x-text="filteredLowongans.length"></span> lowongan
        </p>
        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700" x-show="lowongans.some(lowongan => lowongan.status === 'Dibuka')">
            ● Rekrutmen aktif
        </span>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        <template x-for="lowongan in filteredLowongans" :key="lowongan.id">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-bold tracking-wide text-[#2058B2]">
                            <span x-text="lowongan.kode"></span> · <span x-text="lowongan.sks"></span> SKS
                        </p>
                        <h2 class="mt-2 text-xl font-extrabold" x-text="lowongan.nama"></h2>
                        <p class="mt-1 text-sm text-slate-500">Dosen pengampu: <span x-text="lowongan.dosen"></span></p>
                    </div>
                    <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold"
                          :class="lowongan.status === 'Dibuka' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'"
                          x-text="lowongan.status"></span>
                </div>

                <p class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-600" x-text="lowongan.deskripsi"></p>

                <dl class="mt-5 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-4">
                    <div>
                        <dt class="text-xs text-slate-500">Kuota terisi</dt>
                        <dd class="mt-1 text-sm font-bold"><span x-text="lowongan.terisi"></span>/<span x-text="lowongan.kuota"></span></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Tenggat</dt>
                        <dd class="mt-1 text-sm font-bold" x-text="lowongan.tenggat || 'Belum ditentukan'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Jadwal</dt>
                        <dd class="mt-1 text-sm font-bold" x-text="lowongan.jadwal"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Kelas</dt>
                        <dd class="mt-1 text-sm font-bold" x-text="lowongan.kelas || '—'"></dd>
                    </div>
                </dl>

                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-xs text-slate-500">
                        <span x-show="lowongan.syarat_nilai">Nilai minimal: <span x-text="lowongan.syarat_nilai"></span></span>
                        <span x-show="!lowongan.syarat_nilai">Persyaratan tersedia di detail lowongan.</span>
                    </p>
                    <a :href="detailUrl.replace('__ID__', lowongan.id)" class="rounded-xl bg-[#2058B2] px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800">
                        Lihat Detail →
                    </a>
                </div>
            </article>
        </template>
        <p x-show="filteredLowongans.length === 0" class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500 xl:col-span-2">
            Tidak ada lowongan yang cocok dengan pencarian atau filter.
        </p>
    </div>
</div>
@endsection
