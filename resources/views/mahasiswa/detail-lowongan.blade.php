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
        <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-6 text-slate-600">
            <li>Nilai minimal {{ $lowongan['syarat'] }} pada mata kuliah terkait.</li>
            <li>Memiliki IPK kumulatif minimal 3,25.</li>
            <li>Bersedia mendampingi praktikum setiap minggu.</li>
            <li>Mampu bekerja kolaboratif dan komunikatif.</li>
            <li>Jadwal tidak bentrok dengan KRS semester berjalan.</li>
        </ul>

        {{-- Dokumen --}}
        <h2 class="mt-7 text-lg font-extrabold">Dokumen yang diperlukan</h2>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            @foreach(['CV / Resume PDF', 'Transkrip nilai PDF'] as $doc)
            <div class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">
                <span class="rounded-lg bg-red-50 p-2 text-sm font-extrabold text-red-600">PDF</span>
                <span class="text-sm font-semibold">{{ $doc }}</span>
            </div>
            @endforeach
        </div>

    </section>

    {{-- ===== Sidebar Ringkasan ===== --}}
    <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        {{-- Status --}}
        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
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
                ['Lokasi',         'Lab Komputer'],
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