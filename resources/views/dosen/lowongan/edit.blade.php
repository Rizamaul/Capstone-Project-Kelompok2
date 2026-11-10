@extends('layouts.dosen')

@section('content')
<div class="mx-auto max-w-4xl pb-16">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#2058B2]">Edit Lowongan Asisten</h1>
        <p class="mt-1 text-sm text-gray-500">Perbarui informasi dan persyaratan lowongan.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
            <p class="font-bold">Periksa kembali formulir:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dosen.lowongan.update', $lowongan['id']) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @include('dosen.lowongan._form')

        <div class="flex justify-end gap-3">
            <a href="{{ route('dosen.lowongan.index') }}" class="rounded-lg border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-700">Batal</a>
            <button type="submit" class="rounded-lg bg-[#2058B2] px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
