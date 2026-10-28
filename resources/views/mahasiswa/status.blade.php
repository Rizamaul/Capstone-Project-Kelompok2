@extends('layouts.mahasiswa')
@section('title','Status Pendaftaran')
@section('content')

<div class="mb-7 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
    <div>
        <p class="text-xs font-bold tracking-[.18em] text-[#2058B2]">PANTAU PROGRES</p>
        <h1 class="mt-2 text-2xl font-extrabold sm:text-3xl">Status Pendaftaran</h1>
        <p class="mt-2 text-sm text-slate-500">Setiap perubahan status ditampilkan agar kamu tahu langkah berikutnya.</p>
    </div>
    <a href="{{ route('mahasiswa.lowongan.index') }}" class="text-sm font-bold text-[#2058B2]">← Lihat pengumuman</a>
</div>

<div class="grid gap-6 xl:grid-cols-[1fr_340px]">

    {{-- Jejak status --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-[#2058B2]">
                    {{ $pendaftaran['kode'] }} · {{ $pendaftaran['sks'] }} SKS
                </span>
                <h2 class="mt-3 text-xl font-extrabold">{{ $pendaftaran['matkul'] }}</h2>
                <p class="mt-1 text-sm text-slate-500">Didaftarkan {{ $pendaftaran['didaftarkan_pada'] }}</p>
            </div>
            <span class="rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700">
                {{ $pendaftaran['status_terkini'] }}
            </span>
        </div>

        <div class="my-7 border-t border-slate-100"></div>

        <h3 class="font-extrabold">Jejak Status</h3>
        <p class="mt-1 text-sm text-slate-500">Riwayat perubahan status pendaftaran.</p>

        <ol class="mt-6 space-y-0">
            @foreach($langkah as $step)
            <li class="relative flex gap-4 pb-8 last:pb-0">
                <div class="relative flex flex-col items-center">
                    <span class="z-10 flex h-8 w-8 items-center justify-center rounded-full
                        {{ $step['selesai'] ? 'bg-emerald-100 text-emerald-700' : 'border-2 border-slate-200 bg-white text-slate-400' }}">
                        {{ $step['selesai'] ? '✓' : '·' }}
                    </span>
                    @if(!$loop->last)
                        <span class="absolute top-8 h-full w-px bg-slate-200"></span>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex flex-col justify-between gap-1 sm:flex-row">
                        <h4 class="text-sm font-bold {{ $step['selesai'] ? 'text-slate-900' : 'text-slate-500' }}">
                            {{ $step['judul'] }}
                        </h4>
                        <span class="text-xs text-slate-400">{{ $step['waktu'] }}</span>
                    </div>
                    <p class="mt-1 text-sm leading-6 text-slate-500">{{ $step['deskripsi'] }}</p>
                </div>
            </li>
            @endforeach
        </ol>
    </section>

    {{-- Ringkasan sidebar --}}
    <aside class="space-y-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h3 class="font-extrabold">Ringkasan Pendaftaran</h3>
            <dl class="mt-4 space-y-4 text-sm">
                @foreach([
                    ['Nama',             $pendaftaran['nama']],
                    ['NIM',              $pendaftaran['nim']],
                    ['Nilai mata kuliah',$pendaftaran['nilai_matkul']],
                    ['Dokumen CV',       $pendaftaran['cv']],
                    ['Transkrip',        $pendaftaran['transkrip']],
                ] as $row)
                <div class="flex justify-between gap-3 border-b border-slate-100 pb-3">
                    <dt class="text-slate-500">{{ $row[0] }}</dt>
                    <dd class="text-right font-semibold">{{ $row[1] }}</dd>
                </div>
                @endforeach
            </dl>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <h3 class="font-extrabold text-amber-900">Harap tunggu keputusan dosen</h3>
            <p class="mt-2 text-sm leading-6 text-amber-800">
                Hasil akhir belum diumumkan. Periksa halaman ini secara berkala untuk melihat perubahan status.
            </p>
        </div>
    </aside>

</div>

@endsection