@extends('layouts.dosen')

@section('content')
<div class="mx-auto max-w-7xl pb-16">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[#2058B2]">Pendaftar Asisten Praktikum</h1>
            <p class="mt-1 text-sm text-gray-500">Tinjau berkas, hubungi pelamar, dan kelola tahapan wawancara.</p>
        </div>
        @if(request('q'))
            <a href="{{ route('dosen.pendaftar.index') }}" class="text-xs font-semibold text-[#1E5EFF] hover:underline">
                Reset pencarian "{{ request('q') }}" ✕
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @forelse($pendaftarans as $pendaftaran)
        <article onclick="window.location.href='{{ route('dosen.pendaftar.show', $pendaftaran) }}'" 
                 class="mb-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs hover:border-[#1E5EFF] hover:shadow-md cursor-pointer transition-all group">
            <div class="flex flex-col justify-between gap-5 lg:flex-row">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-bold text-gray-900 group-hover:text-[#1E5EFF] transition-colors">{{ $pendaftaran->nama }}</h2>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $pendaftaran->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : ($pendaftaran->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700') }}">
                            {{ ucfirst($pendaftaran->status) }}
                        </span>
                        @if($pendaftaran->berkas_diverifikasi)
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                ✓ Berkas Ditinjau
                            </span>
                        @endif
                        @if($pendaftaran->jadwal_wawancara)
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold bg-blue-50 text-[#1E5EFF] border border-blue-100">
                                🗓 Wawancara Dijadwalkan
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-500">NIM {{ $pendaftaran->nim }} · {{ $pendaftaran->lowongan?->mata_kuliah ?? 'Lowongan dihapus' }}</p>
                    @if($pendaftaran->mahasiswa)
                        <p class="mt-1 text-xs text-gray-600">
                            {{ $pendaftaran->mahasiswa->name }} ·
                            <a href="mailto:{{ $pendaftaran->mahasiswa->email }}" onclick="event.stopPropagation()" class="font-semibold text-blue-700 hover:underline">{{ $pendaftaran->mahasiswa->email }}</a>
                        </p>
                    @else
                        <p class="mt-1 text-xs text-amber-700">Akun pelamar belum terhubung; kontak akun belum tersedia.</p>
                    @endif
                    <p class="mt-3 whitespace-pre-line break-words text-sm text-gray-700">{{ $pendaftaran->motivasi }}</p>

                    @if($pendaftaran->jadwal_wawancara)
                        <div class="mt-3 p-3 bg-blue-50/60 rounded-xl border border-blue-100/80 text-xs text-blue-900 space-y-1">
                            <p class="font-bold flex items-center gap-1.5">
                                <span>🗓</span> Jadwal Wawancara: {{ $pendaftaran->jadwal_wawancara }}
                            </p>
                            <p class="text-blue-800 text-[11px]">Metode: {{ $pendaftaran->metode_wawancara === 'online' ? 'Daring' : ($pendaftaran->metode_wawancara === 'langsung' ? 'Tatap muka' : 'Belum dicatat') }}</p>
                            @if($pendaftaran->lokasi_wawancara)
                                <p class="text-blue-800 text-[11px] break-words">📍 Lokasi: {{ $pendaftaran->lokasi_wawancara }}</p>
                            @endif
                        </div>
                    @endif

                    @if($pendaftaran->catatan_dosen)
                        <p class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-700 break-words"><strong>Catatan:</strong> {{ $pendaftaran->catatan_dosen }}</p>
                    @endif
                </div>
            </div>
        </article>
    @empty
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
            <h2 class="font-bold text-gray-900">Belum ada pendaftar</h2>
            <p class="mt-2 text-sm text-gray-500">Pendaftar akan tampil di sini setelah mahasiswa mengirim formulir.</p>
        </div>
    @endforelse
</div>
@endsection
