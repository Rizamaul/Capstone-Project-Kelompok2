<div class="grid gap-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:grid-cols-2">
    <label class="block sm:col-span-2">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Mata kuliah *</span>
        <input name="nama" required maxlength="255" value="{{ old('nama', $lowongan['nama'] ?? '') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
        @error('nama') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Kode mata kuliah *</span>
        <input name="kode" required maxlength="20" value="{{ old('kode', $lowongan['kode'] ?? '') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
        @error('kode') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">SKS *</span>
        <input type="number" name="sks" required min="1" max="6" value="{{ old('sks', $lowongan['sks'] ?? 3) }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
        @error('sks') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Kelas</span>
        <input name="kelas" maxlength="50" value="{{ old('kelas', $lowongan['kelas'] ?? '') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Semester</span>
        <input name="semester" maxlength="60" value="{{ old('semester', $lowongan['semester'] ?? '') }}" placeholder="Contoh: Ganjil 2026" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Kuota asisten *</span>
        <input type="number" name="kuota" required min="1" value="{{ old('kuota', $lowongan['kuota'] ?? 1) }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
        @error('kuota') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Jadwal praktikum *</span>
        <input name="jadwal" required maxlength="100" value="{{ old('jadwal', $lowongan['jadwal'] ?? '') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
        @error('jadwal') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Tenggat pendaftaran *</span>
        <input type="datetime-local" name="tenggat" required value="{{ old('tenggat', $lowongan['tenggat_input'] ?? '') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
        @error('tenggat') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Nilai minimum mata kuliah</span>
        <select name="syarat_nilai" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Tidak ditentukan</option>
            @foreach(['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'] as $grade)
                <option value="{{ $grade }}" @selected(old('syarat_nilai', $lowongan['syarat_nilai'] ?? '') === $grade)>{{ $grade }}</option>
            @endforeach
        </select>
    </label>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Minimal IPK</span>
        <input type="number" name="syarat_ipk" min="0" max="4" step="0.01" value="{{ old('syarat_ipk', $lowongan['syarat_ipk'] ?? '') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
    </label>

    <label class="block sm:col-span-2">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Deskripsi mata kuliah *</span>
        <textarea name="deskripsi" required maxlength="2000" rows="4" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi', $lowongan['deskripsi'] ?? '') }}</textarea>
        @error('deskripsi') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    <label class="block sm:col-span-2">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Persyaratan tambahan *</span>
        <textarea name="syarat" required maxlength="2000" rows="3" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('syarat', $lowongan['syarat'] ?? '') }}</textarea>
        @error('syarat') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    @php($selectedDocuments = old('berkas_diminta', $lowongan['berkas_diminta'] ?? ['CV', 'Transkrip nilai']))
    @if(! is_array($selectedDocuments))
        @php($selectedDocuments = [])
    @endif
    <fieldset class="sm:col-span-2">
        <legend class="mb-2 text-sm font-semibold text-gray-700">Dokumen yang diminta</legend>
        <input type="hidden" name="berkas_diminta_present" value="1">
        <div class="flex flex-wrap gap-5">
            @foreach(['CV', 'Transkrip nilai'] as $document)
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="berkas_diminta[]" value="{{ $document }}" @checked(in_array($document, $selectedDocuments, true)) class="rounded border-gray-300 text-[#2058B2] focus:ring-blue-500">
                    {{ $document }}
                </label>
            @endforeach
        </div>
    </fieldset>

    <label class="block sm:col-span-2">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Status pendaftaran *</span>
        <select name="status" required class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="aktif" @selected(old('status', $lowongan['status'] ?? 'aktif') === 'aktif')>Dibuka</option>
            <option value="tutup" @selected(old('status', $lowongan['status'] ?? '') === 'tutup')>Ditutup</option>
        </select>
    </label>
</div>
