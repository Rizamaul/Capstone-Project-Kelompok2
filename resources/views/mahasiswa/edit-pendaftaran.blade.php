@extends('layouts.mahasiswa')
@section('title', 'Edit Ringkasan Pendaftaran')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-16"
     x-data="{
        ipkVal: '{{ old('ipk', $pendaftaran->ipk) }}',
        ipkError: '',
        validateIpk(val) {
            if (val === undefined || val === null || val === '') {
                this.ipkError = 'Nilai IPK wajib diisi.';
                return;
            }
            if (val.includes('-')) {
                this.ipkError = 'Nilai IPK tidak boleh minus.';
                return;
            }
            if (/[^0-9.,]/.test(val)) {
                this.ipkError = 'Nilai IPK hanya boleh diisi angka dan koma atau titik.';
                return;
            }
            let parts = val.split(/[.,]/);
            if (parts.length > 2) {
                this.ipkError = 'Format IPK tidak valid.';
                return;
            }
            let num = parseFloat(val.replace(',', '.'));
            if (!isNaN(num)) {
                if (num <= 0) {
                    this.ipkError = 'Nilai IPK tidak boleh 0 atau minus.';
                    return;
                }
                if (num > 4.00) {
                    this.ipkError = 'Nilai IPK tidak boleh lebih dari 4.00.';
                    return;
                }
            }
            this.ipkError = '';
        }
     }">

    <!-- NAVIGASI KEMBALI -->
    <div>
        <a href="{{ route('mahasiswa.status.show', $pendaftaran->id) }}" class="text-xs font-semibold text-[#1E5EFF] hover:underline inline-flex items-center gap-1 mb-2">
            <span>← Kembali ke Detail Pendaftaran</span>
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Edit Ringkasan Pendaftaran</h1>
                <p class="text-xs text-gray-500 mt-1">Perbarui data diri, nilai akademik, dan dokumen persyaratan asisten praktikum.</p>
            </div>
            <div>
                <span class="px-3 py-1 rounded-lg bg-[#EDF5FF] text-[#1E5EFF] font-bold text-xs">
                    {{ $lowongan['kode'] }} · {{ $lowongan['sks'] }} SKS
                </span>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs text-red-800 space-y-1" role="alert">
            <p class="font-bold">Periksa kembali data formulir:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM EDIT -->
    <form action="{{ route('mahasiswa.pendaftaran.update', $pendaftaran->id) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-6"
          @submit="
              validateIpk(ipkVal);
              if (ipkError) {
                  $event.preventDefault();
                  return false;
              }
          ">
        @csrf
        @method('PUT')

        <!-- KARTU INFORMASI MATA KULIAH -->
        <div class="bg-blue-50/40 rounded-2xl border border-blue-100 p-5 flex items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-bold text-[#1E5EFF] uppercase tracking-wider">Mata Kuliah Dilamar</span>
                <h2 class="text-base font-bold text-gray-900 mt-0.5">{{ $lowongan['nama'] }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">Syarat minimum nilai: <span class="font-semibold text-gray-700">{{ $lowongan['syarat_nilai'] ?: 'C' }}</span> · Minimal IPK: <span class="font-semibold text-gray-700">{{ $lowongan['syarat_ipk'] ?: '3.00' }}</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-white text-[#1E5EFF] flex items-center justify-center font-bold text-lg shadow-2xs border border-blue-100 shrink-0">
                📚
            </div>
        </div>

        <!-- SECTION 1: DATA IDENTITAS MAHASISWA -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-5">
            <div class="pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">Data Identitas Mahasiswa</h3>
                <p class="text-xs text-gray-400 mt-0.5">Pastikan nama dan NIM sesuai data resmi di kartu mahasiswa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" 
                           name="nama" 
                           required 
                           value="{{ old('nama', $pendaftaran->nama) }}" 
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">NIM</label>
                    <input type="text" 
                           name="nim" 
                           required 
                           value="{{ old('nim', $pendaftaran->nim) }}" 
                           placeholder="Contoh: 105223045"
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- SECTION 2: NILAI & AKADEMIK -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-5">
            <div class="pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">Nilai & Kualifikasi Akademik</h3>
                <p class="text-xs text-gray-400 mt-0.5">Nilai mata kuliah prasyarat dan IPK kumulatif terakhir.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nilai Mata Kuliah</label>
                    <select name="nilai_matkul" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach($lowongan['nilai_options'] as $grade)
                            <option value="{{ $grade }}" @selected(old('nilai_matkul', $pendaftaran->nilai_matkul) === $grade)>
                                Nilai {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Minimal IPK Kumulatif</label>
                    <input type="text" 
                           name="ipk" 
                           required 
                           x-model="ipkVal"
                           @input="validateIpk(ipkVal)"
                           placeholder="Contoh: 3.25 atau 3,25"
                           class="w-full px-3.5 py-2.5 bg-white border rounded-xl text-xs text-gray-800 focus:outline-none focus:ring-1"
                           :class="ipkError ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500'">
                    <p x-show="ipkError" x-text="ipkError" class="text-[11px] text-red-500 mt-1 font-medium"></p>
                </div>
            </div>
        </div>

        <!-- SECTION 3: MOTIVASI & PORTOFOLIO -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-5">
            <div class="pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">Motivasi & Portofolio</h3>
                <p class="text-xs text-gray-400 mt-0.5">Jelaskan alasan dan komitmenmu menjadi asisten praktikum.</p>
            </div>

            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Esai Motivasi <span class="text-gray-400 font-normal">(Minimal 50 karakter)</span></label>
                    <textarea name="motivasi" 
                              rows="4" 
                              required 
                              minlength="50"
                              placeholder="Ceritakan motivasi dan pengalamanmu yang relevan..."
                              class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 leading-relaxed">{{ old('motivasi', $pendaftaran->motivasi) }}</textarea>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tautan Portofolio / GitHub / LinkedIn <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="url" 
                           name="portfolio" 
                           value="{{ old('portfolio', $pendaftaran->portfolio) }}" 
                           placeholder="https://github.com/username atau https://linkedin.com/in/username"
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- SECTION 4: DOKUMEN BERKAS PERSYARATAN -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-5">
            <div class="pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">Dokumen Berkas Persyaratan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Unggah berkas baru hanya jika kamu ingin memperbarui dokumen sebelumnya.</p>
            </div>

            <div class="space-y-4 text-xs">
                <!-- Dokumen CV -->
                <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold text-sm">📄</span>
                            <div>
                                <p class="font-bold text-gray-900">Curriculum Vitae (CV)</p>
                                <p class="text-[11px] text-gray-500 truncate max-w-xs sm:max-w-md">
                                    Berkas saat ini: <span class="font-medium text-gray-700">{{ $pendaftaran->cv_path ? basename($pendaftaran->cv_path) : 'Belum diunggah' }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#1E5EFF] font-semibold text-[10px]">Format: PDF</span>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Ganti Berkas CV (Kosongkan jika tidak ingin mengubah):</label>
                        <input type="file" 
                               name="cv" 
                               accept=".pdf"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs text-gray-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1E5EFF] hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

                <!-- Dokumen Transkrip / KSM -->
                <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold text-sm">🎓</span>
                            <div>
                                <p class="font-bold text-gray-900">Transkrip Nilai / KSM</p>
                                <p class="text-[11px] text-gray-500 truncate max-w-xs sm:max-w-md">
                                    Berkas saat ini: <span class="font-medium text-gray-700">{{ $pendaftaran->transkrip_path ? basename($pendaftaran->transkrip_path) : 'Belum diunggah' }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#1E5EFF] font-semibold text-[10px]">Format: PDF</span>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Ganti Berkas Transkrip (Kosongkan jika tidak ingin mengubah):</label>
                        <input type="file" 
                               name="transkrip" 
                               accept=".pdf"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs text-gray-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1E5EFF] hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <!-- TOMBOL AKSI BAWAH -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('mahasiswa.status.show', $pendaftaran->id) }}" 
               class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors shadow-xs flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>

    </form>

</div>
@endsection
