<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Dosen') - ASISTIN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FA] font-sans antialiased text-gray-800 overflow-x-hidden">

    <!-- Container Utama Full Screen -->
    <div class="flex min-h-screen w-full relative">
        
        <!-- SIDEBAR KIRI -->
        <aside class="w-[260px] bg-white border-r border-gray-200 fixed inset-y-0 left-0 z-30 flex flex-col justify-between p-6">
            <div>
                <!-- Logo & Brand Universitas Pertamina -->
                <div class="flex items-center gap-3 mb-8">
                    <img src="{{ asset('images/logo-uper.svg') }}" alt="Logo UPER" class="w-12 h-10 object-contain">
                    <div>
                        <h1 class="text-base font-bold text-gray-900 tracking-tight leading-none">ASISTIN</h1>
                        <p class="text-[10px] text-gray-400 font-semibold tracking-wider mt-1">PORTAL DOSEN</p>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <nav class="space-y-1.5">
                    @php
                        $isDashboard = request()->is('dosen/dashboard');
                        $isCreateLowongan = request()->is('dosen/lowongan/create');
                        $isKelolaLowongan = request()->is('dosen/lowongan') || (request()->is('dosen/lowongan/*') && !request()->is('dosen/lowongan/create'));
                        $isPendaftar = request()->is('dosen/pendaftar*');
                        $isTimesheet = request()->is('dosen/timesheet*');
                    @endphp

                    <!-- Dashboard -->
                    <a href="{{ route('dosen.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isDashboard ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isDashboard ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Buat Lowongan baru -->
                    <a href="{{ route('dosen.lowongan.create') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isCreateLowongan ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isCreateLowongan ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Buat Lowongan baru</span>
                    </a>

                    <!-- Kelola Lowongan -->
                    <a href="{{ route('dosen.lowongan.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isKelolaLowongan ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isKelolaLowongan ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Kelola Lowongan</span>
                    </a>

                    <!-- Pendaftar -->
                    <a href="{{ route('dosen.pendaftar.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isPendaftar ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isPendaftar ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.824-1M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.824-1M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Pendaftar</span>
                    </a>

                    <!-- Timesheet -->
                    <a href="{{ route('dosen.timesheet') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isTimesheet ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isTimesheet ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Timesheet</span>
                    </a>
                </nav>
            </div>

            <!-- Tombol Logout (Sama dengan Mahasiswa: Abu-abu, hover merah) -->
            <div>
                <a href="{{ route('home') }}" aria-label="Keluar ke pilihan ruang kerja" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-red-600 transition-colors group">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Log Out</span>
                    <span class="sr-only">Keluar</span>
                </a>
            </div>
        </aside>

        <!-- KONTEN KANAN -->
        <div class="flex-1 ml-[260px] min-w-0 flex flex-col min-h-screen">
            
            <!-- Navbar Atas  -->
            <header class="h-20 bg-white border-b border-gray-200 sticky top-0 z-20 flex items-center justify-between px-8 w-full">
                <div class="flex flex-1 min-w-0 items-center gap-3">
                    <!-- Badge Semester Ganjil 2026 -->
                    <div class="flex items-center shrink-0">
                        <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#EDF5FF] text-[#2058B2] text-xs font-semibold rounded-xl">
                            <span class="w-2 h-2 rounded-full bg-[#1E5EFF]"></span>
                            <span>Semester Ganjil 2026</span>
                        </div>
                    </div>

                    <!-- Kolom Pencarian -->
                    <div class="flex-1 min-w-0">
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input
                                type="text"
                                class="w-full min-w-0 pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#1E5EFF]"
                                placeholder="Cari pendaftar, matakuliah, atau berkas lainnya..."
                            >
                        </div>
                    </div>
                </div>

                <!-- Bagian Kanan: Notifikasi & Profil Dosen (Menempel rapat di pojok kanan) -->
                <div class="flex items-center gap-6 shrink-0 ml-8">
                    <!-- Notifikasi -->
                    <div class="relative cursor-pointer text-gray-600 hover:text-[#1E5EFF]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-[#1E5EFF] rounded-full ring-2 ring-white"></span>
                    </div>

                    <!-- Profil Dosen -->
                    <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                        <img src="{{ asset('images/foto-avatar.svg') }}" alt="Foto" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-xs bg-gray-100">
                        <div>
                            <p class="text-sm font-bold text-gray-900 whitespace-nowrap">Nama Dosen Pemangku</p>
                            <p class="text-xs text-gray-500">Title dosen</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 w-full p-6 lg:p-8">
                @yield('content')
            </main>
        </div>

    </div>

    @stack('scripts')
</body>
</html>
