@extends('layouts.dosen')

@section('content')
<div class="mx-auto max-w-7xl pb-16">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#2058B2]">Pendaftar Asisten Praktikum</h1>
        <p class="mt-1 text-sm text-gray-500">Tinjau formulir dan umumkan keputusan seleksi.</p>
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
        <article class="mb-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col justify-between gap-5 lg:flex-row">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-bold text-gray-900">{{ $pendaftaran->nama }}</h2>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $pendaftaran->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : ($pendaftaran->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700') }}">
                            {{ ucfirst($pendaftaran->status) }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">NIM {{ $pendaftaran->nim }} · {{ $pendaftaran->lowongan?->mata_kuliah ?? 'Lowongan dihapus' }}</p>
                    <p class="mt-3 whitespace-pre-line text-sm text-gray-700">{{ $pendaftaran->motivasi }}</p>
                    <div class="mt-3 flex flex-wrap gap-4 text-sm">
                        @if($pendaftaran->cv_path)
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pendaftaran->cv_path) }}" target="_blank" rel="noopener" class="font-semibold text-blue-700 hover:underline">Lihat CV</a>
                        @endif
                        @if($pendaftaran->transkrip_path)
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pendaftaran->transkrip_path) }}" target="_blank" rel="noopener" class="font-semibold text-blue-700 hover:underline">Lihat transkrip</a>
                        @endif
                        @if($pendaftaran->portfolio)
                            <a href="{{ $pendaftaran->portfolio }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-blue-700 hover:underline">Portofolio</a>
                        @endif
                    </div>
                    @if($pendaftaran->catatan_dosen)
                        <p class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-700"><strong>Catatan:</strong> {{ $pendaftaran->catatan_dosen }}</p>
                    @endif
                </div>

                @if(! $pendaftaran->is_published)
                    <form action="{{ route('dosen.pendaftar.status', $pendaftaran) }}" method="POST" class="w-full space-y-3 lg:max-w-sm">
                        @csrf
                        @method('PATCH')
                        <label class="block">
                            <span class="mb-1 block text-sm font-semibold text-gray-700">Keputusan</span>
                            <select name="status_seleksi" required class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih keputusan</option>
                                <option value="disetujui" @selected(old('status_seleksi') === 'disetujui')>Setujui</option>
                                <option value="ditolak" @selected(old('status_seleksi') === 'ditolak')>Tolak</option>
                            </select>
                        </label>
                        <textarea name="catatan_dosen" rows="2" maxlength="2000" placeholder="Catatan (opsional)" class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('catatan_dosen') }}</textarea>
                        <button type="submit" class="w-full rounded-lg bg-[#2058B2] px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Simpan Keputusan</button>
                    </form>
                @endif
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
