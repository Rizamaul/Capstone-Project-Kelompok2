@extends('layouts.dosen')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-widest text-[#2058B2] font-bold">Lowongan</p>
                <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $lowongan['nama'] ?? 'Lowongan' }}</h1>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ ($lowongan['status'] ?? 'aktif') === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                {{ ucfirst($lowongan['status'] ?? 'aktif') }}
            </span>
        </div>

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="bg-gray-50 p-4 rounded-xl">
                <dt class="text-gray-500">Kode</dt>
                <dd class="font-bold text-gray-900 mt-1">{{ $lowongan['kode'] ?? 'MK' }}</dd>
            </div>
            <div class="bg-gray-50 p-4 rounded-xl">
                <dt class="text-gray-500">Kuota</dt>
                <dd class="font-bold text-gray-900 mt-1">{{ $lowongan['kuota'] ?? 0 }} mahasiswa</dd>
            </div>
            <div class="bg-gray-50 p-4 rounded-xl">
                <dt class="text-gray-500">Jadwal</dt>
                <dd class="font-bold text-gray-900 mt-1">{{ $lowongan['jadwal'] ?? 'Belum diatur' }}</dd>
            </div>
            <div class="bg-gray-50 p-4 rounded-xl">
                <dt class="text-gray-500">Tenggat</dt>
                <dd class="font-bold text-gray-900 mt-1">{{ $lowongan['tenggat'] ?? '-' }}</dd>
            </div>
        </dl>

        <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-4">
            <h2 class="font-bold text-gray-900 mb-2">Deskripsi</h2>
            <p class="text-sm text-gray-600">{{ $lowongan['deskripsi'] ?? 'Tidak ada deskripsi.' }}</p>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('dosen.lowongan.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700">Kembali</a>
            <a href="{{ route('dosen.lowongan.edit', $lowongan['id']) }}" class="px-4 py-2 bg-[#2058B2] text-white rounded-lg text-sm font-semibold">Edit</a>
        </div>
    </div>
</div>
@endsection
