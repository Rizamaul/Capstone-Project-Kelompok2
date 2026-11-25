@extends('layouts.dosen')
@section('title', 'Detail Lowongan')

@section('content')
<div class="mx-auto max-w-7xl pb-12">

    {{-- Tombol kembali --}}
    <a href="{{ route('dosen.lowongan.index') }}"
       class="text-sm font-bold text-[#2058B2] hover:underline">
        ← Kembali ke kelola lowongan
    </a>

    @if(session('success'))
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-5 grid gap-6 xl:grid-cols-[1fr_340px]">

        {{-- ===== Konten Utama ===== --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">

            {{-- Badge & Judul --}}
            <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-[#2058B2]">
                {{ $lowongan['kode'] ?? 'MK' }} · {{ $lowongan['sks'] ?? 3 }} SKS
            </span>
            <h1 class="mt-4 text-2xl font-extrabold text-gray-900 sm:text-3xl">{{ $lowongan['nama'] ?? 'Lowongan' }}</h1>
            <p class="mt-2 text-sm text-slate-500">
                Rekrutmen asisten praktikum · {{ $lowongan['dosen'] ?? 'Dosen pengampu' }}
            </p>

            <div class="my-7 border-t border-slate-100"></div>

            {{-- Tentang Mata Kuliah --}}
            <h2 class="text-lg font-extrabold text-gray-900">Tentang mata kuliah</h2>
            <p class="mt-3 text-sm leading-7 text-slate-600 break-words whitespace-pre-line">{{ $lowongan['deskripsi'] ?: 'Tidak ada deskripsi.' }}</p>

            {{-- Syarat --}}
            <h2 class="mt-7 text-lg font-extrabold text-gray-900">Syarat pendaftaran</h2>
            <div class="mt-3 space-y-2 text-sm leading-6 text-slate-600">
                @if(!empty($lowongan['syarat_nilai']))
                    <p><span class="font-semibold text-gray-800">Nilai minimum:</span> {{ $lowongan['syarat_nilai'] }}</p>
                @endif
                @if(!empty($lowongan['syarat_ipk']))
                    <p><span class="font-semibold text-gray-800">IPK minimum:</span> {{ number_format((float) $lowongan['syarat_ipk'], 2, ',', '.') }}</p>
                @endif
                <p class="whitespace-pre-line break-words">{{ $lowongan['persyaratan'] ?: 'Tidak ada persyaratan tambahan.' }}</p>
            </div>

            {{-- Dokumen --}}
            <h2 class="mt-7 text-lg font-extrabold text-gray-900">Dokumen yang diperlukan</h2>
            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                @forelse($lowongan['berkas_diminta'] ?? [] as $doc)
                    <div class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">
                        <span class="rounded-lg bg-red-50 p-2 text-sm font-extrabold text-red-600">PDF</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $doc }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Tidak ada dokumen tambahan yang diminta.</p>
                @endforelse
            </div>

        </section>

        {{-- ===== Sidebar Ringkasan ===== --}}
        <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            {{-- Status --}}
            @php($isAktif = ($lowongan['status'] ?? 'aktif') === 'aktif')
            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $isAktif ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $isAktif ? 'Dibuka' : 'Ditutup' }}
            </span>

            <h2 class="mt-5 text-lg font-extrabold text-gray-900">Ringkasan Lowongan</h2>

            {{-- Detail list --}}
            <dl class="mt-4 space-y-4 text-sm">
                @foreach([
                    ['Tenggat',        $lowongan['tenggat_label'] ?? $lowongan['tenggat'] ?? '-'],
                    ['Kuota',          ($lowongan['terisi'] ?? 0) . ' / ' . ($lowongan['kuota'] ?? 0) . ' asisten'],
                    ['Honor',          !empty($lowongan['honor']) ? 'Rp ' . number_format((int) $lowongan['honor'], 0, ',', '.') . ' / jam' : 'Sesuai SK Dekanat'],
                    ['Dosen Pengampu', $lowongan['dosen'] ?? 'Dosen pengampu'],
                    ['Jadwal',         $lowongan['jadwal'] ?? 'Belum diatur'],
                    ['Kelas',          $lowongan['kelas'] ?: 'Belum ditentukan'],
                    ['Semester',       $lowongan['semester'] ?: 'Belum ditentukan'],
                ] as $row)
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                    <dt class="text-slate-500">{{ $row[0] }}</dt>
                    <dd class="text-right font-bold text-gray-900">{{ $row[1] }}</dd>
                </div>
                @endforeach
            </dl>

            {{-- Tombol aksi --}}
            <div class="mt-6 space-y-2.5">
                <a href="{{ route('dosen.lowongan.edit', $lowongan['id']) }}"
                   class="block rounded-xl bg-[#2058B2] px-4 py-3 text-center text-sm font-bold text-white hover:bg-blue-800 transition-colors">
                    Edit Lowongan
                </a>
                <form action="{{ route('dosen.lowongan.toggle-status', $lowongan['id']) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ ($lowongan['status_aktif'] ?? 'aktif') === 'aktif' ? 'tutup' : 'aktif' }}">
                    <button type="submit"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                        {{ ($lowongan['status_aktif'] ?? 'aktif') === 'aktif' ? 'Tutup Lowongan' : 'Buka Lowongan' }}
                    </button>
                </form>
                <a href="{{ route('dosen.pendaftar.index') }}"
                   class="block rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Lihat Pendaftar ({{ $lowongan['total_pendaftar'] ?? 0 }})
                </a>
                @if(($lowongan['total_pendaftar'] ?? 0) === 0)
                    <form action="{{ route('dosen.lowongan.destroy', $lowongan['id']) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full rounded-xl border border-red-200 px-4 py-2.5 text-center text-sm font-semibold text-red-700 hover:bg-red-50 transition-colors cursor-pointer">
                            Hapus Lowongan
                        </button>
                    </form>
                @endif
            </div>

        </aside>

    </div>

</div>
@endsection
