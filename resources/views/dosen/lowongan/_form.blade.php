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
        <span class="mb-2 block text-sm font-semibold text-gray-700">Nominal honor (Rp / jam)</span>
        <input type="number" name="honor" min="0" step="1000" placeholder="Contoh: 35000" value="{{ old('honor', $lowongan['honor'] ?? 35000) }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
        @error('honor') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </label>

    @php
        $existingJadwal = old('jadwal', $lowongan['jadwal'] ?? '');
        $defaultHari = 'Senin';
        $defaultMulai = '08:00';
        $defaultSelesai = '11:00';

        if (preg_match('/\b(Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu)\b/i', (string) $existingJadwal, $hariMatch)) {
            $defaultHari = ucfirst(strtolower($hariMatch[1]));
        } elseif ($existingJadwal !== '') {
            $defaultHari = '';
        }

        if (preg_match_all('/(\d{1,2})[:.](\d{2})/', (string) $existingJadwal, $timeMatches) && count($timeMatches[0]) >= 2) {
            $defaultMulai = str_pad($timeMatches[1][0], 2, '0', STR_PAD_LEFT).':'.$timeMatches[2][0];
            $defaultSelesai = str_pad($timeMatches[1][1], 2, '0', STR_PAD_LEFT).':'.$timeMatches[2][1];
        }
    @endphp
    <div class="block"
         x-data="{
            hari: @js($defaultHari),
            jamMulai: @js($defaultMulai),
            jamSelesai: @js($defaultSelesai),
            get formattedJadwal() {
                const start = (this.jamMulai || '08:00').replace(':', '.');
                const end = (this.jamSelesai || '11:00').replace(':', '.');
                const range = `${start} - ${end} WIB`;
                return this.hari ? `${this.hari}, ${range}` : range;
            }
         }">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Jadwal praktikum *</span>
        <input type="hidden" name="jadwal" :value="formattedJadwal">
        <div class="grid grid-cols-1 sm:grid-cols-[120px_1fr_auto_1fr] items-center gap-2">
            <select x-model="hari" aria-label="Hari praktikum" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Tanpa hari</option>
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                @endforeach
            </select>
            <input type="time" x-model="jamMulai" required aria-label="Jam mulai praktikum" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            <span class="text-center text-xs font-semibold text-gray-400">-</span>
            <input type="time" x-model="jamSelesai" required aria-label="Jam selesai praktikum" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <p class="mt-1.5 text-[11px] text-gray-500">
            Format tersimpan: <span class="font-semibold text-[#2058B2]" x-text="formattedJadwal"></span>
        </p>
        @error('jadwal') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    <label class="block">
        <span class="mb-2 block text-sm font-semibold text-gray-700">Tenggat pendaftaran *</span>
        <input type="text" data-datepicker="datetime" name="tenggat" required placeholder="DD/MM/YYYY HH:MM" value="{{ old('tenggat', $lowongan['tenggat_input'] ?? '') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 bg-white">
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
