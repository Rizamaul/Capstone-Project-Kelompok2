@extends('layouts.dosen')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 pb-16">
    <header class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <a href="{{ route('dosen.timesheet') }}" class="text-sm font-semibold text-[#2058B2] hover:underline">← Kembali ke daftar timesheet</a>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Detail Timesheet</h1>
        <p class="mt-1 text-sm text-gray-500">Tinjau rincian kegiatan sebelum menyimpan keputusan.</p>
    </header>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <section class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col justify-between gap-3 border-b border-gray-100 pb-5 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $entry->asisten?->name ?? 'Mahasiswa #'.$entry->asisten_id }}</h2>
                <p class="mt-1 text-sm text-gray-500">Timesheet #{{ $entry->id }}</p>
            </div>
            <span class="w-fit rounded-full px-3 py-1 text-xs font-semibold {{ match ($entry->status_approval) {'approved' => 'bg-emerald-50 text-emerald-700', 'rejected' => 'bg-red-50 text-red-700', default => 'bg-amber-50 text-amber-700'} }}">
                {{ match ($entry->status_approval) {'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Menunggu'} }}
            </span>
        </div>

        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Mata kuliah</dt>
                <dd class="mt-1 font-semibold text-gray-900">{{ $entry->mata_kuliah ?? 'Asistensi Praktikum' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Tanggal kegiatan</dt>
                <dd class="mt-1 font-semibold text-gray-900">{{ $entry->tanggal->translatedFormat('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Jumlah jam</dt>
                <dd class="mt-1 font-semibold text-gray-900">{{ number_format((float) $entry->jumlah_jam, 1, ',', '.') }} jam</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Aktivitas</dt>
                <dd class="mt-1 font-semibold text-gray-900">{{ $entry->aktivitas ?? '—' }}</dd>
            </div>
        </dl>

        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
            <h3 class="font-semibold text-gray-900">Deskripsi pekerjaan</h3>
            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $entry->uraian_pekerjaan }}</p>
        </div>

        @if($entry->catatan_revisi)
            <div class="rounded-xl border border-red-200 bg-red-50 p-5">
                <h3 class="font-semibold text-red-900">Catatan revisi</h3>
                <p class="mt-2 whitespace-pre-line text-sm text-red-800">{{ $entry->catatan_revisi }}</p>
            </div>
        @endif

        @if($entry->status_approval === 'pending')
            <form action="{{ route('dosen.timesheet.status', $entry) }}" method="POST" class="space-y-4 border-t border-gray-100 pt-6">
                @csrf
                @method('PATCH')
                <label for="catatan_revisi" class="block text-sm font-semibold text-gray-800">Catatan revisi <span class="font-normal text-gray-500">(wajib jika ditolak)</span></label>
                <textarea id="catatan_revisi" name="catatan_revisi" rows="3" maxlength="2000" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('catatan_revisi') }}</textarea>
                @error('catatan_revisi')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('status')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
                    <button type="submit" name="status" value="rejected" class="rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700 hover:bg-red-100">
                        Tolak pengajuan
                    </button>
                    <button type="submit" name="status" value="approved" class="rounded-xl bg-[#2058B2] px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                        Setujui timesheet
                    </button>
                </div>
            </form>
        @endif
    </section>
</div>
@endsection
