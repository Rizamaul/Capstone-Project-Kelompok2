@extends('layouts.dosen')
@section('title', 'Buat Lowongan Baru')

@section('content')
<div class="max-w-4xl mx-auto pb-16 space-y-6"
     x-data="{
        kuota: {{ old('kuota', 2) }},
        jadwalHari: '{{ old('jadwal_hari', 'Senin') }}',
        jadwalJam: '{{ old('jadwal_jam', '08.00 - 10.00 WIB') }}',
        syaratList: [
            'Mahasiswa aktif dan bersedia mendampingi sesi praktikum di laboratorium',
            'Mampu berkomunikasi dengan baik dan menguasai materi praktikum'
        ],
        showSyaratModal: false,
        newSyaratText: '',
        
        manfaatList: [
            {
                title: 'Konversi SKS',
                desc: 'Dapat ditukarkan menjadi 2 SKS yang anda inginkan'
            },
            {
                title: 'Sertifikat & Pengalaman',
                desc: 'Mendapatkan sertifikat asisten laboratorium resmi dan honor jam asistensi'
            }
        ],
        showManfaatModal: false,
        newManfaatTitle: '',
        newManfaatDesc: '',

        selectedDocs: ['Transkrip nilai', 'CV'],

        kelasVal: '{{ old('kelas', '2402') }}',
        kelasError: '',
        validateKelas(val) {
            if (val === undefined || val === null || val === '') {
                this.kelasError = '';
                return;
            }
            if (/[^0-9]/.test(val)) {
                this.kelasError = 'Kelas hanya boleh diisi angka.';
                return;
            }
            this.kelasError = '';
        },

        ipkVal: '{{ old('syarat_ipk', '3.25') }}',
        ipkError: '',
        validateIpk(val) {
            if (val === undefined || val === null || val === '') {
                this.ipkError = '';
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
        },

        openSyaratModal() {
            this.newSyaratText = '';
            this.showSyaratModal = true;
            this.$nextTick(() => { if ($refs.syaratInput) $refs.syaratInput.focus(); });
        },
        saveSyarat() {
            if (this.newSyaratText && this.newSyaratText.trim() !== '') {
                this.syaratList.push(this.newSyaratText.trim());
                this.newSyaratText = '';
                this.showSyaratModal = false;
            }
        },
        removeSyarat(index) {
            this.syaratList.splice(index, 1);
        },

        openManfaatModal() {
            this.newManfaatTitle = '';
            this.newManfaatDesc = '';
            this.showManfaatModal = true;
            this.$nextTick(() => { if ($refs.manfaatTitleInput) $refs.manfaatTitleInput.focus(); });
        },
        saveManfaat() {
            if (this.newManfaatTitle && this.newManfaatTitle.trim() !== '') {
                this.manfaatList.push({
                    title: this.newManfaatTitle.trim(),
                    desc: this.newManfaatDesc.trim() || 'Mendapatkan benefit sesuai ketentuan asistensi praktikum.'
                });
                this.newManfaatTitle = '';
                this.newManfaatDesc = '';
                this.showManfaatModal = false;
            }
        },
        removeManfaat(index) {
            this.manfaatList.splice(index, 1);
        },

        toggleDoc(doc) {
            if (this.selectedDocs.includes(doc)) {
                this.selectedDocs = this.selectedDocs.filter(d => d !== doc);
            } else {
                this.selectedDocs.push(doc);
            }
        }
     }">

    <!-- HEADER -->
    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-xs">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Buat Lowongan Baru untuk Asisten Praktikum Baru</h1>
        <p class="text-xs text-gray-500 mt-1">Silahkan isi formulir berikut untuk membuka pendaftaran asisten praktikum baru.</p>
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

    <form action="{{ route('dosen.lowongan.store') }}" 
          method="POST" 
          class="space-y-6"
          @submit="
              validateKelas(kelasVal);
              validateIpk(ipkVal);
              if (kelasError || ipkError) {
                  $event.preventDefault();
                  return false;
              }
          ">
        @csrf

        <!-- Hidden defaults agar terhubung sempurna ke sistem mahasiswa -->
        <input type="hidden" name="status" value="aktif">
        <input type="hidden" name="sks" value="3">
        <input type="hidden" name="jadwal" :value="jadwalHari + ' ' + jadwalJam">
        <input type="hidden" name="berkas_diminta_present" value="1">

        <!-- ================= SECTION 1: INFORMASI MATA KULIAH ================= -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-6">
            
            <!-- Section Header -->
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-lg border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Informasi Mata Kuliah</h2>
                    <p class="text-xs text-gray-400">Tentukan mata kuliah, Kuota pelamar dan Tenggat waktu pendaftaran yang di butuhkan.</p>
                </div>
            </div>

            <!-- Grid Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                
                <!-- Mata Kuliah Diampu (Ketik Sendiri, Tanpa Dropdown) -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Mata Kuliah Diampu</label>
                    <input type="text" 
                           name="nama" 
                           required 
                           placeholder="Ketik Nama Mata Kuliah (contoh: Algoritma & Struktur Data)" 
                           value="{{ old('nama', 'Algoritma & Struktur Data') }}"
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <!-- Kode Mata Kuliah -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kode Mata Kuliah</label>
                    <input type="text" 
                           name="kode" 
                           required 
                           placeholder="Masukan Kode Mata Kuliah (contoh: IF101)" 
                           value="{{ old('kode', 'IF101') }}"
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kelas</label>
                    <input type="text" 
                           name="kelas" 
                           x-model="kelasVal"
                           @input="validateKelas(kelasVal)"
                           placeholder="Contoh: 2402" 
                           class="w-full px-3.5 py-2.5 bg-white border rounded-xl text-xs text-gray-800 focus:outline-none focus:ring-1"
                           :class="kelasError ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500'">
                    <p x-show="kelasError" x-text="kelasError" class="text-[11px] text-red-500 mt-1 font-medium"></p>
                </div>

                <!-- Semester -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Semester</label>
                    <select name="semester" class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="Semester Ganjil 2026" @selected(old('semester') === 'Semester Ganjil 2026')>Semester Ganjil 2026</option>
                        <option value="Semester Genap 2026" @selected(old('semester') === 'Semester Genap 2026')>Semester Genap 2026</option>
                        <option value="Semester Antara 2026" @selected(old('semester') === 'Semester Antara 2026')>Semester Antara 2026</option>
                    </select>
                </div>

                <!-- Jadwal Pelaksanaan Box 1: Hari -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Jadwal Pelaksanaan - Hari</label>
                    <select name="jadwal_hari" 
                            x-model="jadwalHari" 
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>

                <!-- Jadwal Pelaksanaan Box 2: Jam -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Jadwal Pelaksanaan - Jam</label>
                    <input type="text" 
                           name="jadwal_jam" 
                           x-model="jadwalJam" 
                           placeholder="Contoh: 08.00 - 10.00 WIB" 
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <!-- Kuota Asisten Dosen yang Dibutuhkan -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kuota Asisten Dosen yang Dibutuhkan</label>
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center border border-gray-200 rounded-xl overflow-hidden bg-white">
                            <button type="button" 
                                    @click="if(kuota > 1) kuota--" 
                                    class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-100 text-sm font-bold border-r border-gray-200 cursor-pointer">
                                −
                            </button>
                            <input type="number" 
                                   name="kuota" 
                                   x-model="kuota" 
                                   min="1" 
                                   class="w-12 text-center text-xs font-bold text-gray-900 border-0 focus:ring-0">
                            <button type="button" 
                                    @click="kuota++" 
                                    class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-100 text-sm font-bold border-l border-gray-200 cursor-pointer">
                                +
                            </button>
                        </div>
                        <span class="text-xs text-gray-400">Mahasiswa</span>
                    </div>
                </div>

                <!-- Tenggat Waktu -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tenggat Waktu</label>
                    <div class="relative">
                        <input type="datetime-local" 
                               name="tenggat" 
                               required 
                               value="{{ old('tenggat', now()->addWeeks(2)->format('Y-m-d\T08:00')) }}"
                               class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>

            </div>

        </div>

        <!-- ================= SECTION 2: KRITERIA & SYARAT SELEKSI ================= -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-6">
            
            <!-- Section Header -->
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-lg border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Kriteria & Syarat Seleksi</h2>
                    <p class="text-xs text-gray-400">Tentukan standar minimum akademik dan kompetensi khusus kandidat</p>
                </div>
            </div>

            <!-- Grid Nilai & IPK -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nilai Minimum Mata Kuliah</label>
                    <select name="syarat_nilai" class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="A" @selected(old('syarat_nilai') === 'A')>Nilai A (Sangat Baik)</option>
                        <option value="A-" @selected(old('syarat_nilai', 'A-') === 'A-')>Nilai A-</option>
                        <option value="B+" @selected(old('syarat_nilai') === 'B+')>Nilai B+</option>
                        <option value="B" @selected(old('syarat_nilai') === 'B')>Nilai B</option>
                        <option value="B-" @selected(old('syarat_nilai') === 'B-')>Nilai B-</option>
                        <option value="C+" @selected(old('syarat_nilai') === 'C+')>Nilai C+</option>
                        <option value="C" @selected(old('syarat_nilai') === 'C')>Nilai C</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Minimal IPK Kumulatif</label>
                    <input type="text" 
                           name="syarat_ipk" 
                           id="syarat_ipk"
                           required 
                           x-model="ipkVal"
                           @input="validateIpk(ipkVal)"
                           placeholder="Contoh: 3.25" 
                           class="w-full px-3.5 py-2.5 bg-white border rounded-xl text-xs text-gray-800 focus:outline-none focus:ring-1"
                           :class="ipkError ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500'">
                    <p x-show="ipkError" x-text="ipkError" class="text-[11px] text-red-500 mt-1 font-medium"></p>
                </div>
            </div>

            <!-- Syarat Tambahan Dinamis -->
            <div class="pt-2 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-700">Syarat Tambahan</span>
                    <button type="button" 
                            @click="openSyaratModal()" 
                            class="text-xs font-bold text-[#1E5EFF] hover:underline cursor-pointer flex items-center gap-1">
                        <span>+</span>
                        <span>Tambah Syarat</span>
                    </button>
                </div>

                <div class="space-y-2.5">
                    <template x-for="(syarat, index) in syaratList" :key="index">
                        <div class="rounded-xl border border-blue-200/80 bg-blue-50/20 px-4 py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                    ✓
                                </span>
                                <input type="text" 
                                       name="syarat_tambahan[]" 
                                       x-model="syaratList[index]" 
                                       class="w-full bg-transparent border-0 text-xs text-gray-800 font-medium focus:ring-0 p-0">
                            </div>
                            <button type="button" 
                                    @click="removeSyarat(index)" 
                                    title="Hapus Syarat"
                                    class="text-gray-400 hover:text-gray-600 font-bold text-sm cursor-pointer p-1">
                                ✕
                            </button>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <!-- ================= SECTION 3: MANFAAT & BENEFIT ASISTEN ================= -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-6">
            
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-lg border border-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Manfaat & Benefit Asisten</h2>
                        <p class="text-xs text-gray-400">Masukan manfaat & benefit untuk asisten praktikum yang di inginkan</p>
                    </div>
                </div>
                <button type="button" 
                        @click="openManfaatModal()" 
                        class="text-xs font-bold text-[#1E5EFF] hover:underline cursor-pointer sm:self-center flex items-center gap-1">
                    <span>+</span>
                    <span>Tambah Manfaat & Benefit</span>
                </button>
            </div>

            <!-- Dynamic Manfaat List -->
            <div class="space-y-3">
                <template x-for="(benefit, index) in manfaatList" :key="index">
                    <div class="rounded-xl border border-blue-200/80 bg-blue-50/20 px-4 py-3 flex items-center justify-between gap-3">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">
                                ✓
                            </span>
                            <div class="flex-1 min-w-0">
                                <input type="text" 
                                       x-model="benefit.title" 
                                       class="w-full bg-transparent border-0 text-xs font-bold text-gray-900 focus:ring-0 p-0 mb-0.5">
                                <input type="text" 
                                       x-model="benefit.desc" 
                                       class="w-full bg-transparent border-0 text-[11px] text-gray-500 focus:ring-0 p-0">
                                <input type="hidden" 
                                       name="manfaat[]" 
                                       :value="`${benefit.title}: ${benefit.desc}`">
                            </div>
                        </div>
                        <button type="button" 
                                @click="removeManfaat(index)" 
                                title="Hapus Manfaat"
                                class="text-gray-400 hover:text-gray-600 font-bold text-sm cursor-pointer p-1">
                            ✕
                        </button>
                    </div>
                </template>
            </div>

        </div>

        <!-- ================= SECTION 4: DAFTAR DOKUMEN YANG DIMINTA ================= -->
        <div class="bg-white rounded-2xl border border-gray-200/90 p-6 sm:p-7 shadow-xs space-y-6">
            
            <!-- Section Header -->
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-lg border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Daftar Dokumen yang Diminta</h2>
                    <p class="text-xs text-gray-400">Format berkas dokumen yang wajib diunggah mahasiswa pada formulir lamaran</p>
                </div>
            </div>

            <!-- Document Selection Cards -->
            <div class="space-y-3">
                
                <!-- Dokumen 1: Transkrip Nilai -->
                <div @click="toggleDoc('Transkrip nilai')" 
                     class="rounded-xl border p-4 flex items-center justify-between gap-4 cursor-pointer transition-colors"
                     :class="selectedDocs.includes('Transkrip nilai') ? 'border-[#1E5EFF] bg-blue-50/20' : 'border-gray-200 hover:border-gray-300'">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-lg border border-blue-100 shrink-0">
                            🎓
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-xs">Transkrip Nilai Akademik Terakhir</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">memuat nilai mata kuliah prasyarat secara sah & terverifikasi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="px-3 py-1 rounded-lg bg-blue-50 text-[#1E5EFF] font-semibold text-[10px]">
                            Format : PDF
                        </span>
                        <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors"
                             :class="selectedDocs.includes('Transkrip nilai') ? 'border-[#1E5EFF] bg-[#1E5EFF] text-white' : 'border-gray-300'">
                            <span x-show="selectedDocs.includes('Transkrip nilai')" class="text-[10px] font-bold">✓</span>
                        </div>
                    </div>
                    <input type="checkbox" name="berkas_diminta[]" value="Transkrip nilai" class="hidden" :checked="selectedDocs.includes('Transkrip nilai')">
                </div>

                <!-- Dokumen 2: CV -->
                <div @click="toggleDoc('CV')" 
                     class="rounded-xl border p-4 flex items-center justify-between gap-4 cursor-pointer transition-colors"
                     :class="selectedDocs.includes('CV') ? 'border-[#1E5EFF] bg-blue-50/20' : 'border-gray-200 hover:border-gray-300'">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-lg border border-blue-100 shrink-0">
                            📄
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-xs">Curriculum Vitae (CV)</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">memuat pengalaman akademik, keorganisasian, dan keahlian teknis pelamar</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="px-3 py-1 rounded-lg bg-blue-50 text-[#1E5EFF] font-semibold text-[10px]">
                            Format : PDF
                        </span>
                        <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors"
                             :class="selectedDocs.includes('CV') ? 'border-[#1E5EFF] bg-[#1E5EFF] text-white' : 'border-gray-300'">
                            <span x-show="selectedDocs.includes('CV')" class="text-[10px] font-bold">✓</span>
                        </div>
                    </div>
                    <input type="checkbox" name="berkas_diminta[]" value="CV" class="hidden" :checked="selectedDocs.includes('CV')">
                </div>

                <!-- Dokumen 3: Portofolio -->
                <div @click="toggleDoc('Portofolio')" 
                     class="rounded-xl border p-4 flex items-center justify-between gap-4 cursor-pointer transition-colors"
                     :class="selectedDocs.includes('Portofolio') ? 'border-[#1E5EFF] bg-blue-50/20' : 'border-gray-200 hover:border-gray-300'">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E5EFF] flex items-center justify-center text-lg border border-blue-100 shrink-0">
                            💼
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-xs">Portofolio / Sertifikat Tambahan</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">berkas opsional pendukung keahlian proyek atau lomba yang relevan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="px-3 py-1 rounded-lg bg-blue-50 text-[#1E5EFF] font-semibold text-[10px]">
                            Format : PDF
                        </span>
                        <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors"
                             :class="selectedDocs.includes('Portofolio') ? 'border-[#1E5EFF] bg-[#1E5EFF] text-white' : 'border-gray-300'">
                            <span x-show="selectedDocs.includes('Portofolio')" class="text-[10px] font-bold">✓</span>
                        </div>
                    </div>
                    <input type="checkbox" name="berkas_diminta[]" value="Portofolio" class="hidden" :checked="selectedDocs.includes('Portofolio')">
                </div>

            </div>

        </div>

        <!-- ================= TOMBOL AKSI BAWAH ================= -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('dosen.lowongan.index') }}" 
               class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors">
                Batalkan
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#1E5EFF] text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors shadow-xs flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 rotate-45 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                <span>Publikasikan</span>
            </button>
        </div>

    </form>

    <!-- ================= MODAL / POP-UP CARD: TAMBAH SYARAT ================= -->
    <div x-show="showSyaratModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-xs transition-opacity"
         @keydown.escape.window="showSyaratModal = false">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-4"
             @click.away="showSyaratModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold text-sm">
                        +
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Tambah Syarat Tambahan</h3>
                </div>
                <button type="button" @click="showSyaratModal = false" class="text-gray-400 hover:text-gray-600 text-base font-bold cursor-pointer">✕</button>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-gray-700">Isi Syarat Kualifikasi</label>
                <textarea x-ref="syaratInput" 
                          x-model="newSyaratText" 
                          rows="3" 
                          placeholder="Ketik syarat yang diinginkan, misal: Mahasiswa angkatan 2023 atau 2024 dan tidak sedang mengambil mata kuliah praktikum bentrok..."
                          class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          @keydown.enter.prevent="saveSyarat()"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" 
                        @click="showSyaratModal = false" 
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="button" 
                        @click="saveSyarat()" 
                        class="px-4 py-2 bg-[#1E5EFF] text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors shadow-xs cursor-pointer">
                    Tambahkan Syarat
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL / POP-UP CARD: TAMBAH MANFAAT & BENEFIT ================= -->
    <div x-show="showManfaatModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-xs transition-opacity"
         @keydown.escape.window="showManfaatModal = false">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-4"
             @click.away="showManfaatModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E5EFF] flex items-center justify-center font-bold text-sm">
                        +
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Tambah Manfaat & Benefit</h3>
                </div>
                <button type="button" @click="showManfaatModal = false" class="text-gray-400 hover:text-gray-600 text-base font-bold cursor-pointer">✕</button>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Judul Manfaat / Benefit</label>
                    <input type="text" 
                           x-ref="manfaatTitleInput"
                           x-model="newManfaatTitle" 
                           placeholder="Contoh: Honor Asistensi Bulanan" 
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Keterangan / Rincian Benefit</label>
                    <textarea x-model="newManfaatDesc" 
                              rows="2" 
                              placeholder="Contoh: Diberikan insentif jam asistensi laboratorium resmi dari fakultas"
                              class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" 
                        @click="showManfaatModal = false" 
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="button" 
                        @click="saveManfaat()" 
                        class="px-4 py-2 bg-[#1E5EFF] text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors shadow-xs cursor-pointer">
                    Tambahkan Benefit
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
