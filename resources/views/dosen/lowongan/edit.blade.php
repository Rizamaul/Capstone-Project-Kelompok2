@extends('layouts.dosen')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-[#2058B2] mb-6">Edit Lowongan</h1>

        <form action="{{ route('dosen.lowongan.update', $lowongan['id']) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul lowongan</label>
                <input type="text" name="nama" value="{{ $lowongan['nama'] ?? '' }}" class="w-full border border-gray-200 rounded-xl p-3">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kode</label>
                <input type="text" name="kode" value="{{ $lowongan['kode'] ?? '' }}" class="w-full border border-gray-200 rounded-xl p-3">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-200 rounded-xl p-3">
                    <option value="aktif" {{ ($lowongan['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tutup" {{ ($lowongan['status'] ?? 'aktif') === 'tutup' ? 'selected' : '' }}>Tutup</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="5" class="w-full border border-gray-200 rounded-xl p-3">{{ $lowongan['deskripsi'] ?? '' }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-[#2058B2] text-white px-4 py-2 rounded-lg font-semibold">Simpan</button>
                <a href="{{ route('dosen.lowongan.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
