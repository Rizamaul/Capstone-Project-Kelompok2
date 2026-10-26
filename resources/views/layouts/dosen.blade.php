<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Dosen - ASISTIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F9FA] flex min-h-screen text-gray-800">

    <!-- Sidebar Kiri (Navigasi Utama) -->
    <aside class="w-[260px] bg-white border-r border-gray-200 fixed h-full flex flex-col z-20">
        <!-- Logo Area -->
        <div class="h-[80px] flex items-center px-6 border-b border-transparent">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-uper.svg') }}" alt="Logo Universitas Pertamina" class="h-10 w-auto object-contain">
                <div>
                    <h1 class="text-blue-700 font-bold text-lg leading-none tracking-wide">ASISTIN</h1>
                    <p class="text-[10px] font-semibold text-gray-500 mt-1 tracking-wider">PORTAL DOSEN</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu dengan Dynamic Active State -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <!-- Menu Dashboard -->
            <a href="/dosen/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-colors {{ request()->is('dosen/dashboard') ? 'bg-[#EBF1FF] text-[#1E5EFF]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <!-- Menu Buat Lowongan Baru -->
            <a href="/dosen/lowongan/create" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-colors {{ request()->is('dosen/lowongan*') ? 'bg-[#EBF1FF] text-[#1E5EFF]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Buat Lowongan baru
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-700 rounded-xl font-medium text-sm transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Draft Lowongan Asdos
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-700 rounded-xl font-medium text-sm transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Timesheet
            </a>
        </nav>

        <!-- Logout Button -->
        <div class="px-4 mb-8">
            <button class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-xl font-medium text-sm w-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Log Out
            </button>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="ml-[260px] flex-1 flex flex-col w-full">
        <!-- Top Navbar -->
        <header class="h-[80px] bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-10">
            <!-- Semester Badge -->
            <div class="bg-[#EBF1FF] text-[#1E5EFF] px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                <span class="w-2 h-2 bg-[#1E5EFF] rounded-full"></span>
                Semester Ganjil 2026
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-2xl mx-8">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors" placeholder="Cari pendaftar, matakuliah, atau berkas lainnya...">
                </div>
            </div>

            <!-- Right Profile & Notification -->
            <div class="flex items-center gap-6">
                <!-- Notification Bell -->
                <button class="relative text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <!-- Red/Blue Dot -->
                    <span class="absolute top-0.5 right-0.5 block w-2 h-2 bg-[#1E5EFF] rounded-full ring-2 ring-white"></span>
                </button>

                <!-- Profile -->
                <div class="flex items-center gap-3 border-l border-gray-200 pl-6">
                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="https://ui-avatars.com/api/?name=Dosen+Pemangku&background=F3F4F6&color=1F2937" alt="Profile">
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-bold text-gray-800 leading-tight">Nama Dosen Pemangku</p>
                        <p class="text-[11px] font-medium text-gray-500 mt-0.5">Title dosen</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Area -->
        <div class="p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>