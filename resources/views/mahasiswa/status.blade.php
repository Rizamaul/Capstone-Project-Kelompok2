@extends('layouts.mahasiswa')
@section('title','Status Pendaftaran')
@section('content')

@if(session('success'))
    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
        {{ session('success') }}
    </div>
@endif

<div class="mb-7 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
    <div>
        <p class="text-xs font-bold tracking-[.18em] text-[#2058B2]">PANTAU PROGRES</p>
        <h1 class="mt-2 text-2xl font-extrabold sm:text-3xl">Status Pendaftaran</h1>
        <p class="mt-2 text-sm text-slate-500">Setiap perubahan status ditampilkan agar kamu tahu langkah berikutnya.</p>
    </div>
    <a href="{{ route('mahasiswa.lowongan.index') }}" class="text-sm font-bold text-[#2058B2]">← Lihat pengumuman</a>
</div>

@if(! $pendaftaran)
    <section class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center">
        <h2 class="text-lg font-extrabold">Belum ada pendaftaran</h2>
        <p class="mt-2 text-sm text-slate-500">Pilih lowongan yang tersedia untuk mengirim formulir pendaftaran.</p>
        <a href="{{ route('mahasiswa.lowongan.index') }}" class="mt-5 inline-flex rounded-xl bg-[#2058B2] px-5 py-3 text-sm font-bold text-white hover:bg-blue-800">
            Lihat lowongan
        </a>
    </section>
@else
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
            <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ str_contains(strtolower($pendaftaran['status_terkini']), 'ditolak') ? 'bg-red-50 text-red-700' : ($pendaftaran['status_terkini'] === 'Pendaftaran disetujui' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700') }}">
                {{ $pendaftaran['status_terkini'] }}
            </span>
        </div>

        <div class="my-7 border-t border-slate-100"></div>
        @if($pendaftaran['catatan_dosen'])
            <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                <h3 class="font-bold">Catatan dosen</h3>
                <p class="mt-1 whitespace-pre-line">{{ $pendaftaran['catatan_dosen'] }}</p>
            </div>
        @endif

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

        @if($pendaftaran['status_terkini'] === 'Menunggu keputusan dosen')
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <h3 class="font-extrabold text-amber-900">Harap tunggu keputusan dosen</h3>
                <p class="mt-2 text-sm leading-6 text-amber-800">Hasil akhir belum diumumkan. Periksa halaman ini secara berkala untuk melihat perubahan status.</p>
            </div>
        @elseif($pendaftaran['status_terkini'] === 'Pendaftaran disetujui')
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                <h3 class="font-extrabold text-emerald-900">Pendaftaran disetujui</h3>
                <p class="mt-2 text-sm leading-6 text-emerald-800">Dosen telah mengumumkan hasil seleksi. Lihat catatan di atas untuk informasi lanjutan.</p>
            </div>
        @else
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                <h3 class="font-extrabold text-red-900">Pendaftaran belum berhasil</h3>
                <p class="mt-2 text-sm leading-6 text-red-800">Dosen telah mengumumkan hasil seleksi. Lihat catatan di atas untuk informasi lanjutan.</p>
            </div>
        @endif
    </aside>

</div>
@endif

@endsection