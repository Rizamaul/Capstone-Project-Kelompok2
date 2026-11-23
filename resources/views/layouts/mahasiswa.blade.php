<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Mahasiswa') - ASISTIN</title>
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
                        <p class="text-[10px] text-gray-400 font-semibold tracking-wider mt-1">PORTAL MAHASISWA</p>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <nav class="space-y-1.5">
                    @php
                        $isDashboard = request()->is('mahasiswa/dashboard');
                        $isPengumuman = request()->is('mahasiswa/lowongan*');
                        $isStatus = request()->is('mahasiswa/status*');
                        $isTimesheet = request()->is('mahasiswa/timesheet*');
                    @endphp

                    <!-- Dashboard -->
                    <a href="{{ route('mahasiswa.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isDashboard ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isDashboard ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Pengumuman -->
                    <a href="{{ route('mahasiswa.lowongan.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isPengumuman ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isPengumuman ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Pengumuman</span>
                    </a>

                    <!-- Status Pendaftaran -->
                    <a href="{{ route('mahasiswa.status') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isStatus ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isStatus ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Status Pendaftaran</span>
                    </a>

                    <!-- TimeSheet -->
                    <a href="{{ route('mahasiswa.timesheet') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ $isTimesheet ? 'bg-[#EBF3FF] text-[#1E5EFF]' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ $isTimesheet ? 'text-[#1E5EFF]' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>TimeSheet</span>
                    </a>
                </nav>
            </div>

            <!-- Tombol Logout -->
            <div>
                <a href="{{ route('home') }}" aria-label="Keluar" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Log Out</span>
                    <span class="sr-only">Keluar</span>
                </a>
            </div>
        </aside>

        <!-- AREA KONTEN (KANAN) -->
        <div class="flex-1 ml-[260px] flex flex-col min-h-screen">
            
            <!-- TOP BAR HEADER -->
            <header class="h-20 bg-white border-b border-gray-200 sticky top-0 z-20 px-8 flex items-center justify-between gap-4">
                
                <!-- Badge Semester -->
                <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#EDF5FF] text-[#2058B2] text-xs font-semibold rounded-xl">
                    <span class="w-2 h-2 rounded-full bg-[#1E5EFF]"></span>
                    <span>Semester Ganjil 2026</span>
                </div>

                <!-- Search Input Bar -->
                <form action="{{ route('mahasiswa.lowongan.index') }}" method="GET" class="flex-1 max-w-xl">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="search" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Cari pendaftar, matakuliah, atau berkas lainnya..." 
                               class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-xs placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                    </div>
                </form>

                <!-- Profile & Notifications -->
                <div class="flex items-center gap-5">
                    <!-- Bell Icon with notification dot -->
                    <button type="button" class="relative text-gray-500 hover:text-gray-700 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-[#1E5EFF] rounded-full ring-2 ring-white"></span>
                    </button>

                    <!-- Profil Mahasiswa -->
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/foto-avatar.svg') }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-gray-900 leading-tight">Nama Mahasiswa Aktif</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Jurusan</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ISI KONTEN UTAMA -->
            <main class="flex-1 p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="py-6 text-center text-xs text-gray-400">
                ASISTIN · Portal Rekrutmen Asisten Praktikum
            </footer>

        </div>

    </div>

</body>
</html>