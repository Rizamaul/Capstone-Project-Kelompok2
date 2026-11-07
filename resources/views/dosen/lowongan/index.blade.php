@extends('layouts.dosen')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#2058B2]">Daftar Lowongan Asisten</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pengumuman dan status rekrutmen asisten praktikum.</p>
        </div>
        <a href="{{ route('dosen.lowongan.create') }}" class="bg-[#2058B2] text-white px-4 py-3 rounded-lg text-sm font-semibold hover:bg-blue-700">
            + Buat Pengumuman
        </a>
    </div>

    <div class="space-y-4">
        @foreach($lowongans as $lowongan)
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ ($lowongan['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                {{ ucfirst($lowongan['status'] ?? 'aktif') }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $lowongan['kode'] ?? 'MK' }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $lowongan['nama'] ?? 'Lowongan' }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $lowongan['deskripsi'] ?? 'Deskripsi belum tersedia.' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('dosen.lowongan.show', $lowongan['id']) }}" class="px-4 py-2 bg-blue-50 text-[#1E5EFF] rounded-lg text-sm font-semibold">Detail</a>
                        <a href="{{ route('dosen.lowongan.edit', $lowongan['id']) }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700">Edit</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
