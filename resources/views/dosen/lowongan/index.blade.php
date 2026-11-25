@extends('layouts.dosen')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#2058B2]">Daftar Lowongan Asisten</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pengumuman dan status rekrutmen asisten praktikum.</p>
            @if(request('q'))
                <a href="{{ route('dosen.lowongan.index') }}" class="mt-2 inline-block text-xs font-semibold text-[#1E5EFF] hover:underline">
                    Reset pencarian "{{ request('q') }}" ✕
                </a>
            @endif
        </div>
        <a href="{{ route('dosen.lowongan.create') }}" class="bg-[#2058B2] text-white px-4 py-3 rounded-lg text-sm font-semibold hover:bg-blue-700">
            + Buat Pengumuman
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @foreach($lowongans as $lowongan)
            <article onclick="window.location.href='{{ route('dosen.lowongan.show', $lowongan['id']) }}'"
                     class="bg-white border border-gray-200 rounded-2xl p-5 shadow-xs hover:border-[#1E5EFF] hover:shadow-md cursor-pointer transition-all group">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ ($lowongan['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                            {{ ucfirst($lowongan['status'] ?? 'aktif') }}
                        </span>
                        <span class="text-xs font-medium text-gray-500">{{ $lowongan['kode'] ?? 'MK' }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 group-hover:text-[#1E5EFF] transition-colors">{{ $lowongan['nama'] ?? 'Lowongan' }}</h2>
                    <p class="text-sm text-gray-500 mt-1 break-words">{{ $lowongan['deskripsi'] ?? 'Deskripsi belum tersedia.' }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ $lowongan['total_pendaftar'] }} pendaftar · {{ $lowongan['terisi'] }}/{{ $lowongan['kuota'] }} kuota terisi</p>
                </div>
            </article>
        @endforeach
        @if($lowongans === [])
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                <h2 class="font-bold text-gray-900">Belum ada lowongan</h2>
                <p class="mt-2 text-sm text-gray-500">Buat pengumuman pertama untuk mulai menerima pendaftar.</p>
            </div>
        @endif
    </div>
</div>
@endsection
