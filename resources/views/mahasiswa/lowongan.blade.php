@extends('layouts.mahasiswa')
@section('title', 'Pengumuman Lowongan')

@section('content')
<div class="space-y-6"
     x-data="{
        lowongans: {{ Js::from($lowongans) }},
        q: @js(request('q', '')),
        status: @js(request('status', 'Semua')),
        sort: @js(request('sort', 'Tenggat terdekat')),
        detailUrl: @js(route('mahasiswa.lowongan.show', ['id' => '__ID__'])),
        get filteredLowongans() {
            return this.lowongans
                .filter(lowongan => {
                    const query = this.q.trim().toLowerCase();
                    const matchesQuery = !query || `${lowongan.nama} ${lowongan.kode}`.toLowerCase().includes(query);

                    return matchesQuery && (this.status === 'Semua' || this.status.toLowerCase() === lowongan.status.toLowerCase());
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

    <!-- HEADER -->
    <div>
        <p class="text-[11px] font-bold text-[#1E5EFF] tracking-wider uppercase">PENGUMUMAN REKRUTMEN</p>
        <h1 class="text-2xl font-extrabold text-gray-900 mt-1">Lowongan Asisten Praktikum</h1>
        <p class="text-xs text-gray-500 mt-1">Temukan mata kuliah yang sesuai dengan minat dan kemampuanmu.</p>
    </div>

    <!-- TOOLBAR PENCARIAN & FILTER -->
    <div class="bg-white rounded-2xl border border-gray-200/90 p-4 shadow-xs">
        <form method="GET" action="{{ route('mahasiswa.lowongan.index') }}" class="grid grid-cols-1 md:grid-cols-[1fr_200px_200px] gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input name="q" 
                       x-model="q" 
                       type="search" 
                       placeholder="Cari mata kuliah atau kode..." 
                       value="{{ request('q') }}" 
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <select name="status" 
                        x-model="status" 
                        class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="Semua" @selected(request('status') === 'Semua')>Semua</option>
                    <option value="Dibuka" @selected(request('status') === 'Dibuka')>Dibuka</option>
                    <option value="Ditutup" @selected(request('status') === 'Ditutup')>Ditutup</option>
                </select>
            </div>

            <div>
                <select name="sort" 
                        x-model="sort" 
                        class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="Tenggat terdekat" @selected(request('sort') === 'Tenggat terdekat')>Tenggat Terdekat</option>
                    <option value="Nama A-Z" @selected(request('sort') === 'Nama A-Z')>Nama A-Z</option>
                    <option value="Kuota terbanyak" @selected(request('sort') === 'Kuota terbanyak')>Kuota Terbanyak</option>
                </select>
            </div>
        </form>
    </div>

    <!-- SUBHEADER INFO JUMLAH DATA -->
    <div class="flex items-center justify-between gap-3 pt-1">
        <p class="text-xs text-gray-600 font-medium">
            Menampilkan <span class="font-bold text-gray-900" x-text="filteredLowongans.length"></span> Lowongan
        </p>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold"
              x-show="lowongans.some(l => l.status.toLowerCase() === 'dibuka')">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Rekrutmen aktif</span>
        </span>
    </div>

    <!-- DAFTAR LOWONGAN (GRID 2 KOLOM) -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        <template x-for="lowongan in filteredLowongans" :key="lowongan.id">
            <article class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <!-- Header Badges -->
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-2.5 py-1 rounded-lg bg-[#EDF5FF] text-[#1E5EFF] font-bold text-[11px]"
                              x-text="`${lowongan.kode} · ${lowongan.sks} SKS`">
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md font-semibold text-[11px]"
                              :class="lowongan.status.toLowerCase() === 'dibuka' ? 'bg-[#E8F8F0] text-[#059669]' : 'bg-gray-100 text-gray-500'"
                              x-text="lowongan.status.toLowerCase()">
                        </span>
                    </div>

                    <!-- Title & Lecturer -->
                    <h2 class="text-base font-bold text-gray-900 mt-3" x-text="lowongan.nama"></h2>
                    <p class="text-xs text-gray-500 mt-0.5">Dosen pengampu: <span x-text="lowongan.dosen"></span></p>

                    <!-- Description -->
                    <p class="mt-3 text-xs leading-relaxed text-gray-600 line-clamp-3" x-text="lowongan.deskripsi || 'Mata kuliah ini memperkenalkan konsep praktikum dan pendampingan di laboratorium.'"></p>

                    <!-- Info Grid 4 Kolom -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 bg-gray-50/80 rounded-xl p-3 border border-gray-100 mt-4 text-xs">
                        <div>
                            <p class="text-[10px] text-gray-400">Kuota</p>
                            <p class="font-bold text-gray-800 mt-0.5"><span x-text="lowongan.terisi"></span>/<span x-text="lowongan.kuota"></span></p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Tenggat</p>
                            <p class="font-bold text-gray-800 mt-0.5" x-text="lowongan.tenggat || '-'"></p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Jadwal</p>
                            <p class="font-bold text-gray-800 mt-0.5 truncate" x-text="lowongan.jadwal || '-'"></p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Lokasi</p>
                            <p class="font-bold text-gray-800 mt-0.5">Lab Komputer</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Aksi -->
                <div class="flex items-center justify-between gap-3 mt-5 pt-2">
                    <span class="text-xs text-gray-500 font-medium">
                        Syarat minimal: <span class="font-semibold text-gray-700" x-text="lowongan.syarat_nilai || 'B+'"></span>
                    </span>
                    <a :href="detailUrl.replace('__ID__', lowongan.id)"
                       class="px-5 py-2.5 rounded-xl bg-[#1E5EFF] text-white text-xs font-bold hover:bg-blue-700 transition-colors shadow-xs">
                        Lihat Detail →
                    </a>
                </div>
            </article>
        </template>
    </div>

    <!-- State Kosong jika tidak ada hasil -->
    <div x-show="filteredLowongans.length === 0" class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
        <p class="text-sm font-bold text-gray-800">Tidak ada lowongan yang sesuai</p>
        <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci atau filter status pencarian Anda.</p>
    </div>

</div>
@endsection
