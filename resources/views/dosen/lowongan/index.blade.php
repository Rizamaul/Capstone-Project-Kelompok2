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

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

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
                        <p class="mt-2 text-sm text-gray-500">{{ $lowongan['total_pendaftar'] }} pendaftar · {{ $lowongan['terisi'] }}/{{ $lowongan['kuota'] }} kuota terisi</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('dosen.lowongan.show', $lowongan['id']) }}" class="px-4 py-2 bg-blue-50 text-[#1E5EFF] rounded-lg text-sm font-semibold">Detail</a>
                        <a href="{{ route('dosen.lowongan.edit', $lowongan['id']) }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700">Edit</a>
                        <form action="{{ route('dosen.lowongan.toggle-status', $lowongan['id']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $lowongan['status_aktif'] === 'aktif' ? 'tutup' : 'aktif' }}">
                            <button type="submit" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                {{ $lowongan['status_aktif'] === 'aktif' ? 'Tutup' : 'Buka' }}
                            </button>
                        </form>
                        @if($lowongan['total_pendaftar'] === 0)
                            <form action="{{ route('dosen.lowongan.destroy', $lowongan['id']) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 rounded-lg border border-red-200 text-sm font-semibold text-red-700 hover:bg-red-50">Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
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
