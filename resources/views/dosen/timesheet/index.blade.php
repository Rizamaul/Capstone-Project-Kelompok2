@extends('layouts.dosen')

@section('content')
<div class="mx-auto max-w-7xl space-y-6 pb-16">
    <header class="flex flex-col justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-[#2058B2]">Review Timesheet</h1>
            <p class="mt-1 text-sm text-gray-500">Tinjau jam kerja asisten praktikum dan simpan keputusan.</p>
        </div>
        @if($statistik['menunggu'] > 0)
            <form action="{{ route('dosen.timesheet.approve-pending') }}" method="POST" onsubmit="return confirm('Setujui semua timesheet yang masih menunggu?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-xl bg-[#2058B2] px-4 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                    Setujui semua yang menunggu
                </button>
            </form>
        @endif
    </header>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan timesheet">
        <article class="rounded-2xl border border-gray-200 bg-white p-5">
            <p class="text-sm font-semibold text-gray-500">Total timesheet</p>
            <p class="mt-2 text-3xl font-bold">{{ $statistik['total'] }}</p>
        </article>
        <article class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-sm font-semibold text-amber-800">Menunggu review</p>
            <p class="mt-2 text-3xl font-bold text-amber-900">{{ $statistik['menunggu'] }}</p>
        </article>
        <article class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
            <p class="text-sm font-semibold text-emerald-800">Disetujui</p>
            <p class="mt-2 text-3xl font-bold text-emerald-900">{{ $statistik['disetujui'] }}</p>
        </article>
        <article class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
            <p class="text-sm font-semibold text-blue-800">Jam disetujui</p>
            <p class="mt-2 text-3xl font-bold text-blue-900">{{ number_format($statistik['total_jam'], 1, ',', '.') }}</p>
        </article>
    </section>

    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <form action="{{ route('dosen.timesheet') }}" method="GET" class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row">
            <label class="sr-only" for="timesheet-search">Cari timesheet</label>
            <input id="timesheet-search" type="search" name="q" value="{{ $filters['q'] ?? '' }}" maxlength="100" placeholder="Cari nama, mata kuliah, atau aktivitas..." class="min-w-0 flex-1 rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            <label class="sr-only" for="timesheet-status">Filter status</label>
            <select id="timesheet-status" name="status" class="rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua status</option>
                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Menunggu</option>
                <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Disetujui</option>
                <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Ditolak</option>
            </select>
            <button type="submit" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cari</button>
            @if(($filters['q'] ?? '') !== '' || ($filters['status'] ?? '') !== '')
                <a href="{{ route('dosen.timesheet') }}" class="rounded-xl px-4 py-2 text-center text-sm font-semibold text-gray-500 hover:bg-gray-50">Reset</a>
            @endif
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Asisten</th>
                        <th class="px-5 py-4 font-semibold">Mata kuliah / Aktivitas</th>
                        <th class="px-5 py-4 font-semibold">Tanggal</th>
                        <th class="px-5 py-4 font-semibold">Jam</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($entries as $entry)
                        <tr>
                            <td class="px-5 py-4 font-semibold text-gray-900">{{ $entry->asisten?->name ?? 'Mahasiswa #'.$entry->asisten_id }}</td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-900">{{ $entry->mata_kuliah ?? 'Asistensi Praktikum' }}</p>
                                <p class="mt-1 max-w-md truncate text-gray-500">{{ $entry->aktivitas ? $entry->aktivitas.': ' : '' }}{{ $entry->uraian_pekerjaan }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $entry->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="whitespace-nowrap px-5 py-4 font-semibold">{{ number_format((float) $entry->jumlah_jam, 1, ',', '.') }} jam</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ match ($entry->status_approval) {'approved' => 'bg-emerald-50 text-emerald-700', 'rejected' => 'bg-red-50 text-red-700', default => 'bg-amber-50 text-amber-700'} }}">
                                    {{ match ($entry->status_approval) {'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Menunggu'} }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <a href="{{ route('dosen.timesheet.show', $entry) }}" class="font-semibold text-[#2058B2] hover:underline">Detail</a>
                                @if($entry->status_approval === 'pending')
                                    <form action="{{ route('dosen.timesheet.status', $entry) }}" method="POST" class="ml-3 inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="font-semibold text-emerald-700 hover:underline">Setujui</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="font-semibold text-gray-800">Tidak ada timesheet yang cocok.</p>
                                <p class="mt-1 text-sm text-gray-500">Coba ubah pencarian atau filter status.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($entries->hasPages())
            <div class="border-t border-gray-100 p-4">{{ $entries->links() }}</div>
        @endif
    </section>
</div>
@endsection
