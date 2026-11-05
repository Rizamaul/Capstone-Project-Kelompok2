<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f9fc">
    <title>Pilih Ruang Kerja - ASISTIN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-[#F7F9FC] font-sans text-slate-800 antialiased">
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="picker-orb absolute -left-32 -top-32 h-96 w-96 rounded-full bg-blue-200/40 blur-3xl"></div>
        <div class="picker-orb picker-orb-delayed absolute -right-36 top-1/3 h-[28rem] w-[28rem] rounded-full bg-sky-200/40 blur-3xl"></div>
        <div class="absolute inset-0 opacity-[0.22]" style="background-image: radial-gradient(#94a3b8 0.7px, transparent 0.7px); background-size: 24px 24px;"></div>
    </div>

    <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-5 sm:px-8">
        <header class="picker-enter flex items-center justify-between gap-4 py-6 sm:py-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="ASISTIN beranda">
                <img src="{{ asset('images/logo-uper.svg') }}" alt="" class="h-10 w-10 object-contain">
                <span>
                    <span class="block text-lg font-extrabold tracking-wide text-slate-900">ASISTIN</span>
                    <span class="block text-[10px] font-semibold tracking-[.2em] text-slate-500">PORTAL ASISTEN PRAKTIKUM</span>
                </span>
            </a>
            <span class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white/80 px-3 py-1.5 text-[10px] font-bold tracking-[.14em] text-[#2058B2] shadow-sm sm:px-4 sm:text-xs">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-60 motion-reduce:animate-none"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-[#2058B2]"></span>
                </span>
                DEVELOPMENT MODE
            </span>
        </header>

        <main class="flex flex-1 items-center py-10 sm:py-16">
            <div class="mx-auto w-full max-w-5xl">
                <div class="picker-enter picker-enter-delayed mx-auto mb-10 max-w-2xl text-center sm:mb-14">
                    <p class="mb-4 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[.16em] text-[#2058B2]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#2058B2]"></span>
                        Satu portal, dua ruang kerja
                    </p>
                    <h1 class="text-4xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-5xl md:text-6xl">
                        Pilih ruang kerjamu<span class="text-[#2873CF]">.</span>
                    </h1>
                    <p class="mx-auto mt-5 max-w-xl text-base leading-7 text-slate-500 sm:text-lg">
                        Selamat datang di portal ASISTIN. Pilih tampilan yang ingin kamu jelajahi.
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-2 md:gap-6">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="picker-link picker-card group relative flex min-h-64 flex-col overflow-hidden rounded-[1.75rem] border border-blue-100 bg-white p-7 shadow-[0_18px_55px_-30px_rgba(32,88,178,0.28)] transition duration-300 hover:-translate-y-1.5 hover:border-blue-200 hover:shadow-[0_28px_65px_-30px_rgba(32,88,178,0.38)] focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-200 sm:p-9">
                        <div class="pointer-events-none absolute -right-10 -top-12 h-48 w-48 rounded-full bg-blue-50 transition duration-500 group-hover:scale-125" aria-hidden="true"></div>
                        <div class="relative flex items-start justify-between">
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-[#2058B2] transition duration-300 group-hover:rotate-[-6deg] group-hover:scale-110" aria-hidden="true">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                                </svg>
                            </span>
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-[10px] font-bold uppercase tracking-[.14em] text-[#2058B2]">RUANG 01</span>
                        </div>
                        <div class="relative mt-8 flex items-end justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Mahasiswa</h2>
                                <p class="mt-2 max-w-xs text-sm leading-6 text-slate-500">Jelajahi pengumuman, daftar sebagai asisten, dan pantau aktivitasmu.</p>
                            </div>
                            <span class="picker-arrow mb-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-lg text-[#2058B2]" aria-hidden="true">→</span>
                        </div>
                        <span class="sr-only">Buka dashboard mahasiswa</span>
                    </a>

                    <a href="{{ route('dosen.dashboard') }}" class="picker-link picker-card picker-card-delayed group relative flex min-h-64 flex-col overflow-hidden rounded-[1.75rem] border border-sky-100 bg-white p-7 shadow-[0_18px_55px_-30px_rgba(32,88,178,0.28)] transition duration-300 hover:-translate-y-1.5 hover:border-sky-200 hover:shadow-[0_28px_65px_-30px_rgba(32,88,178,0.38)] focus:outline-none focus-visible:ring-4 focus-visible:ring-sky-200 sm:p-9">
                        <div class="pointer-events-none absolute -right-10 -top-12 h-48 w-48 rounded-full bg-sky-50 transition duration-500 group-hover:scale-125" aria-hidden="true"></div>
                        <div class="relative flex items-start justify-between">
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-[#2058B2] transition duration-300 group-hover:rotate-[6deg] group-hover:scale-110" aria-hidden="true">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7m-4 0h8M5 3h14a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm4 5h6m-6 3h4"/>
                                </svg>
                            </span>
                            <span class="rounded-full bg-sky-50 px-3 py-1 text-[10px] font-bold uppercase tracking-[.14em] text-[#2058B2]">RUANG 02</span>
                        </div>
                        <div class="relative mt-8 flex items-end justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Dosen</h2>
                                <p class="mt-2 max-w-xs text-sm leading-6 text-slate-500">Kelola lowongan asisten, tinjau pendaftar, dan pantau timesheet.</p>
                            </div>
                            <span class="picker-arrow mb-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-sky-50 text-lg text-[#2058B2]" aria-hidden="true">→</span>
                        </div>
                        <span class="sr-only">Buka dashboard dosen</span>
                    </a>
                </div>

                <p class="picker-enter mt-8 text-center text-xs leading-5 text-slate-400">
                    <span class="font-semibold text-slate-500">Halaman sementara untuk pengembangan.</span>
                    Pilihan ini langsung membuka dashboard tanpa proses login.
                </p>
            </div>
        </main>

        <footer class="picker-enter flex flex-col items-center justify-between gap-2 border-t border-slate-200/80 py-5 text-xs text-slate-400 sm:flex-row">
            <span>ASISTIN <span class="px-1 text-slate-300">·</span> Portal Rekrutmen Asisten Praktikum</span>
            <span>Semester Ganjil 2026</span>
        </footer>
    </div>
</body>
</html>
