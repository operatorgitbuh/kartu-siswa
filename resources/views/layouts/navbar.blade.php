<div x-show="showAlert" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-10 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-10 opacity-0"
    class="fixed bottom-5 right-5 z-[100] bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    <span class="font-medium">Aksi berhasil dilakukan!</span>
    <button @click="showAlert = false" class="ml-4 text-white/80 hover:text-white">&times;</button>
</div>

<div x-show="mobileMenuOpen" x-cloak class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
    <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="fixed inset-0 flex">
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full" @click.away="mobileMenuOpen = false"
            class="relative flex w-full max-w-xs flex-1 flex-col bg-white shadow-2xl">

            <div class="flex h-16 shrink-0 items-center justify-between px-6 border-b border-slate-100">
                <span class="text-indigo-600 font-bold text-xl tracking-tight uppercase">Admin Panel</span>
                <button @click="mobileMenuOpen = false" class="text-slate-400 p-2 text-2xl">&times;</button>
            </div>

            <nav class="flex flex-col h-full px-4 py-6" x-data="{ openMenu: null }">
                <div class="flex-1 space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Menu Utama</p>
                    @role('ADMIN')
                        <a href="/dashboard"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm transition rounded-xl {{ request()->is('dashboard') ? 'text-indigo-600 bg-indigo-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Overview
                        </a>
                    @endrole
                    @role('WALI_KELAS')
                        <a href="/wali-kelas/dashboard"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm transition rounded-xl {{ request()->is('wali-kelas*') ? 'text-indigo-600 bg-indigo-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Overview
                        </a>
                    @endrole

                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-2">Data Master
                    </p>
                    <div class="space-y-1">
                        <button @click="openMenu = (openMenu === 'master' ? null : 'master')"
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium transition rounded-xl {{ request()->is('classrooms*', 'students*') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7c-2 0-3 1-3 3zM9 11h6M9 15h3">
                                    </path>
                                </svg>
                                <span>Master Data</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="openMenu === 'master' ||
                                    {{ request()->is('classrooms*', 'students*') ? 'true' : 'false' }} ? 'rotate-180' :
                                    ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="openMenu === 'master' || {{ request()->is('classrooms*', 'students*') ? 'true' : 'false' }}"
                            x-collapse class="pl-11 space-y-1">
                            <a href="/classrooms"
                                class="flex items-center gap-2 py-2 text-sm {{ request()->is('classrooms*') ? 'text-indigo-600 font-semibold' : 'text-slate-500 hover:text-indigo-600' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->is('classrooms*') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                Classrooms
                            </a>
                            <a href="/students"
                                class="flex items-center gap-2 py-2 text-sm {{ request()->is('students*') ? 'text-indigo-600 font-semibold' : 'text-slate-500 hover:text-indigo-600' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->is('students*') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                Students
                            </a>
                        </div>
                    </div>

                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-2">Manajemen
                    </p>
                    <div class="space-y-1">
                        <button @click="openMenu = (openMenu === 'manajemen' ? null : 'manajemen')"
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium transition rounded-xl {{ request()->is('schools*', 'backgrounds*') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m4 0h1m-5 10h5m-5-4h5m-5-4h5">
                                    </path>
                                </svg>
                                <span>Sekolah</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="openMenu === 'manajemen' ||
                                    {{ request()->is('schools*', 'backgrounds*') ? 'true' : 'false' }} ? 'rotate-180' :
                                    ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenu === 'manajemen' || {{ request()->is('schools*', 'backgrounds*') ? 'true' : 'false' }}"
                            x-collapse class="pl-11 space-y-1">
                            <a href="/schools"
                                class="flex items-center gap-2 py-2 text-sm {{ request()->is('schools*') ? 'text-indigo-600 font-semibold' : 'text-slate-500 hover:text-indigo-600' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->is('schools*') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                Profile Sekolah
                            </a>
                            <a href="/backgrounds"
                                class="flex items-center gap-2 py-2 text-sm {{ request()->is('backgrounds*') ? 'text-indigo-600 font-semibold' : 'text-slate-500 hover:text-indigo-600' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->is('backgrounds*') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                Background
                            </a>
                        </div>
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-2">
                            Kartu
                        </p>
                        <a href="/card-students"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm transition rounded-xl mt-4 {{ request()->is('card-students*') ? 'text-indigo-600 bg-indigo-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                </path>
                            </svg>
                            Cetak Kartu
                        </a>
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-2">
                            Setting
                        </p>
                        <button @click="openMenu = (openMenu === 'settings' ? null : 'settings')"
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium transition rounded-xl {{ request()->is('users*', 'roles*') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                </svg>
                                <span>Akses Sistem</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="openMenu === 'settings' ||
                                    {{ request()->is('users*', 'roles*') ? 'true' : 'false' }} ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="openMenu === 'settings' || {{ request()->is('users*', 'roles*', 'permissions*') ? 'true' : 'false' }}"
                            x-collapse class="pl-11 space-y-1">
                            <a href="/users"
                                class="flex items-center gap-2 py-2 text-sm {{ request()->is('users*') ? 'text-indigo-600 font-semibold' : 'text-slate-500 hover:text-indigo-600' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->is('users*') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                Daftar Pengguna
                            </a>
                            <a href="/roles"
                                class="flex items-center gap-2 py-2 text-sm {{ request()->is('roles*') ? 'text-indigo-600 font-semibold' : 'text-slate-500 hover:text-indigo-600' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->is('roles*') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                Role
                            </a>
                            <a href="/permissions"
                                class="flex items-center gap-2 py-2 text-sm {{ request()->is('permissions*') ? 'text-indigo-600 font-semibold' : 'text-slate-500 hover:text-indigo-600' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->is('permissions*') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                Permission
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-6 border-t border-slate-100">
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-sidebar-form').submit();"
                        class="flex items-center gap-3 px-3 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition group">

                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Keluar Aplikasi
                    </a>

                    <form id="logout-sidebar-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </nav>
        </div>
    </div>
</div>

<header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center gap-3 group cursor-pointer shrink-0">
                <div
                    class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:rotate-6 transition-transform">
                    <i data-lucide="id-card" class="text-white w-6 h-6"></i>
                </div>
                <a href="/">
                    <div class="flex flex-col leading-tight">
                        <span class="font-extrabold text-base sm:text-lg tracking-tight">
                            E-KARTU
                        </span>
                        <span class="text-[8px] sm:text-[9px] font-bold text-indigo-500 tracking-[0.2em] uppercase">
                            SMKN 1 Wonosari
                        </span>
                    </div>
                </a>
            </div>

            <nav class="hidden lg:flex items-center space-x-1 font-medium text-sm text-slate-600">
                @role('ADMIN')
                    <a href="/dashboard"
                        class="flex items-center gap-2 px-4 py-2 rounded-full transition {{ request()->is('dashboard') ? 'text-indigo-600 bg-indigo-50' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Overview
                    </a>
                @endrole
                @role('WALI_KELAS')
                    <a href="/wali-kelas/dashboard"
                        class="flex items-center gap-2 px-4 py-2 rounded-full transition {{ request()->is('wali-kelas/dashboard') ? 'text-indigo-600 bg-indigo-50' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Overview
                    </a>
                @endrole
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button
                        class="flex items-center gap-2 px-4 py-2 rounded-full transition outline-none {{ request()->is('classrooms*', 'students*', 'wali-kelas/students') ? 'text-indigo-600 bg-indigo-50' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7c-2 0-3 1-3 3zM9 11h6M9 15h3">
                            </path>
                        </svg>
                        <span>Data Master</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak x-transition
                        class="absolute left-0 mt-1 w-48 bg-white border border-slate-100 rounded-xl shadow-lg py-2 z-50">
                        @role('ADMIN')
                            <a href="/classrooms"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('classrooms*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m4 0h1m-5 10h5m-5-4h5m-5-4h5">
                                    </path>
                                </svg>
                                Classrooms
                            </a>
                            <a href="/students"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('students*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                Students
                            </a>
                        @endrole
                        @role('WALI_KELAS')
                            <a href="/wali-kelas/students"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('wali-kelas/students') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                Students
                            </a>
                        @endrole
                    </div>
                </div>
                @role('ADMIN')
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-full transition outline-none {{ request()->is('schools*', 'backgrounds*') ? 'text-indigo-600 bg-indigo-50' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m4 0h1m-5 10h5m-5-4h5m-5-4h5">
                                </path>
                            </svg>
                            <span>Manajemen</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition
                            class="absolute left-0 mt-1 w-48 bg-white border border-slate-100 rounded-xl shadow-lg py-2 z-50">
                            <a href="/schools"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('schools*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                                Schools
                            </a>
                            <a href="/backgrounds"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('backgrounds*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Background
                            </a>
                        </div>
                    </div>
                @endrole
                @role('ADMIN')
                    <a href="/card-students"
                        class="flex items-center gap-2 px-4 py-2 rounded-full transition {{ request()->is('card-students*') ? 'text-indigo-600 bg-indigo-50' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                            </path>
                        </svg>
                        Card
                    </a>
                @endrole
                @role('WALI_KELAS')
                    <a href="/wali-kelas/card-students"
                        class="flex items-center gap-2 px-4 py-2 rounded-full transition {{ request()->is('wali-kelas/card-students*') ? 'text-indigo-600 bg-indigo-50' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                            </path>
                        </svg>
                        Card
                    </a>
                @endrole
                @role('ADMIN')
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-full transition outline-none {{ request()->is('users*', 'roles*', 'permissions*','backup-restore-database*') ? 'text-indigo-600 bg-indigo-50' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Pengaturan</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition
                            class="absolute left-0 mt-1 w-48 bg-white border border-slate-100 rounded-xl shadow-lg py-2 z-50">
                            <a href="/users"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('users*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                Users
                            </a>
                            <a href="/roles"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('roles*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                Role
                            </a>
                            <a href="/permissions"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('permissions*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                Permission
                            </a>
                            <a href="/backup-restore-database"
                                class="flex items-center gap-2 px-4 py-2 transition {{ request()->is('backup-restore-database*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                Backup/Restore
                            </a>
                        </div>
                    </div>
                @endrole
            </nav>

            <div class="flex items-center gap-3">

                <div class="relative hidden lg:block" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
                        class="h-9 w-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-200 cursor-pointer hover:scale-105 transition overflow-hidden">

                        @if (Auth::user()->avatars)
                            <img src="{{ asset('storage/' . Auth::user()->avatars) }}"
                                alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ collect(explode(' ', Auth::user()->name))->map(fn($name) => substr($name, 0, 1))->take(2)->implode('') }}
                        @endif

                    </button>

                    <div x-show="profileOpen" x-cloak x-transition.origin.top.right
                        class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-slate-100 shadow-xl py-2 z-50">

                        <div class="px-4 py-2 border-b border-slate-50 mb-1">
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        @role('ADMIN')
                            <a href="{{ route('users.indexProfile') }}"
                                class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition">
                                Profil Saya
                            </a>
                        @endrole
                        @role('WALI_KELAS')
                            <a href="{{ route('wali-kelas.users', Auth::user()->id) }}"
                                class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition">
                                Profil Saya
                            </a>
                        @endrole

                        <hr class="my-1 border-slate-50">

                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium transition">
                            Keluar
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>

                <button @click="mobileMenuOpen = true"
                    class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition ml-1">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
</header>
