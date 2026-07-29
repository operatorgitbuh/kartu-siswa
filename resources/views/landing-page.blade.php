<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Kartu | SMKN 1 Wonosari</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('main/img/logo-smk.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
            background-color: #ffffff;
        }

        .text-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-blob {
            position: absolute;
            filter: blur(100px);
            z-index: -1;
            opacity: 0.3;
            animation: move 20s infinite alternate;
        }

        @keyframes move {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(100px, 50px) scale(1.2);
            }
        }

        .card-perspective {
            perspective: 1500px;
        }

        .card-rotate {
            transform: rotateY(-15deg) rotateX(10deg);
            transition: transform 0.6s ease;
        }

        .card-perspective:hover .card-rotate {
            transform: rotateY(0deg) rotateX(0deg);
        }

        /* New Section Styles */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -20px rgba(0, 0, 0, 0.1);
        }

        [x-cloak] {
            display: none !important;
        }

        .step-number {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="text-slate-900 overflow-x-hidden" x-data="{
    scrolled: false,
    modalOpen: false,
    loading: false,
    nisn: '',
    result: null,
    errorMessage: ''
}"
    @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Background Decoration -->
    <div class="hero-blob bg-indigo-400 w-[500px] h-[500px] top-[-200px] right-[-100px]"></div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300"
        :class="scrolled ? 'py-3 bg-white/80 backdrop-blur-md shadow-sm' : 'py-6 bg-transparent'">

        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between gap-4">

            <div class="flex items-center gap-3 group cursor-pointer shrink-0">
                <div
                    class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:rotate-6 transition-transform">
                    <i data-lucide="id-card" class="text-white w-6 h-6"></i>
                </div>

                <div class="flex flex-col leading-tight">
                    <span class="font-extrabold text-base sm:text-lg tracking-tight">
                        E-KARTU
                    </span>
                    <span class="text-[8px] sm:text-[9px] font-bold text-indigo-500 tracking-[0.2em] uppercase">
                        SMKN 1 Wonosari
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-4 sm:gap-8">
                <div class="hidden xs:flex items-center gap-4 sm:gap-10">
                    <a href="#fitur"
                        class="text-xs sm:text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Fitur</a>
                    <a href="#alur"
                        class="text-xs sm:text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Alur</a>
                </div>

                @auth
                    @php
                        // Tentukan link berdasarkan role
                        $dashboardLink = Auth::user()->hasRole('ADMIN')
                            ? url('/dashboard')
                            : url('/wali-kelas/dashboard');

                        // Tentukan label (opsional, jika ingin membedakan tulisan "Dashboard")
                        $roleLabel = Auth::user()->hasRole('ADMIN') ? 'Admin Panel' : 'Wali Kelas';
                    @endphp

                    <a href="{{ $dashboardLink }}" class="flex items-center gap-3 group">
                        <div
                            class="w-11 h-11 rounded-full border-2 border-indigo-500/20 p-0.5 group-hover:border-indigo-600 transition-all shadow-sm overflow-hidden flex items-center justify-center bg-indigo-50">
                            @if (Auth::user()->avatars)
                                <img src="{{ asset('storage/' . Auth::user()->avatars) }}" alt="{{ Auth::user()->name }}"
                                    class="w-full h-full rounded-full object-cover">
                            @else
                                <span class="text-xs font-black text-indigo-600 uppercase">
                                    {{ collect(explode(' ', Auth::user()->name))->map(fn($name) => substr($name, 0, 1))->take(2)->implode('') }}
                                </span>
                            @endif
                        </div>

                        <div class="hidden lg:flex flex-col leading-none">
                            <span class="text-xs font-black text-slate-900">{{ Auth::user()->name }}</span>
                            <span
                                class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">{{ $roleLabel }}</span>
                        </div>
                    </a>
                @endauth

                @guest
                    <a href="/login"
                        class="px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-indigo-600 transition flex items-center gap-2 shadow-md">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                        <span>Masuk</span>
                    </a>
                @endguest
            </div>

        </div>
    </nav>

    <!-- Hero Section (Margin Adjusted) -->
    <section class="relative pt-32 pb-32 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">
            <div class="text-left">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold mb-8 border border-indigo-100">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                    Sistem Identitas Digital Terintegrasi
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-[1.05] tracking-tight mb-8">
                    Cara Modern Kelola <br>
                    <span class="text-gradient">Kartu Siswa.</span>
                </h1>
                <p class="text-slate-500 text-lg md:text-xl leading-relaxed mb-10 max-w-xl">
                    Sistem otomasi pembuatan kartu pelajar digital. Lebih cepat, akurat,
                    dan ramah lingkungan.
                </p>
                <div class="flex flex-wrap gap-4">
                    <button @click="modalOpen = true"
                        class="px-8 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 flex items-center gap-3">
                        Cek Keaktifan Kartu
                        <i data-lucide="search" class="w-5 h-5"></i> </button>
                    <button
                        class="px-8 py-4 bg-white text-slate-900 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition flex items-center gap-3">
                        <i data-lucide="play-circle" class="w-5 h-5"></i>
                        Lihat Video
                    </button>
                </div>
            </div>

            <!-- Card UI -->
            <div class="card-perspective hidden lg:block">
                <div
                    class="card-rotate bg-white p-4 rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15)] border border-white/50 relative overflow-hidden">
                    <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white relative">
                        <div class="flex justify-between items-start mb-16">
                            <div>
                                <h4 class="text-xl font-black tracking-widest leading-none">SMKN 1 WONOSARI</h4>
                                <p class="text-[10px] font-bold text-indigo-400 tracking-[0.3em]">40500149</p>
                            </div>
                            <i data-lucide="nfc" class="w-8 h-8 text-white/20"></i>
                        </div>
                        <div class="flex items-center gap-8 mb-12">
                            <div class="w-24 h-32 bg-white/10 rounded-2xl border border-white/20 overflow-hidden">
                                <img src="/main/img/students.jpg" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-1">Ayunda Saputri Lahai</h3>
                                <p class="text-indigo-300 text-sm font-semibold mb-4 text-balance">
                                    Akuntansi & Keuangan Lembaga
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[9px] text-white/40 uppercase font-black tracking-widest">NISN
                                        </p>
                                        <p class="text-xs font-mono">0082736152</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-white/40 uppercase font-black tracking-widest">
                                            Exp Date
                                        </p>
                                        <p class="text-xs font-mono">31/12/2030</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-8 border-t border-white/10">
                            <div class="flex items-center gap-2 text-indigo-400">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                                <span class="text-[10px] font-bold tracking-widest uppercase text-white">Verified
                                    Identity</span>
                            </div>
                            <span class="text-[10px] font-bold px-3 py-1 bg-white/10 rounded-full">V.2.1.0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Layanan -->
    <section id="fitur" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="max-w-xl">
                    <h2 class="text-indigo-600 font-extrabold text-sm tracking-[0.2em] uppercase mb-4">Layanan Unggulan
                    </h2>
                    <p class="text-4xl font-extrabold text-slate-900 leading-tight">Solusi Digital untuk
                        <br>Administrasi Tanpa Kertas.
                    </p>
                </div>
                <p class="text-slate-500 max-w-xs pb-1">Mengurangi beban admin sekolah hingga 80% dengan otomasi cerdas.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="glass-card p-10 rounded-[2.5rem]">
                    <div
                        class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-8">
                        <i data-lucide="refresh-cw" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-4">Import Data</h3>
                    <p class="text-slate-500 leading-relaxed mb-6">Data siswa tinggal Import Dari Excel.
                        Tidak ada input ganda, tidak ada data duplikat.</p>
                    <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-indigo-600 h-full w-[95%]"></div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="glass-card p-10 rounded-[2.5rem]">
                    <div
                        class="w-14 h-14 bg-violet-100 text-violet-600 rounded-2xl flex items-center justify-center mb-8">
                        <i data-lucide="printer" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-4">Cetak Massal Cepat</h3>
                    <p class="text-slate-500 leading-relaxed mb-6">Generate ribuan kartu pelajar dalam hitungan detik
                        dengan format PDF resolusi tinggi siap cetak.</p>
                    <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-violet-600 h-full w-[88%]"></div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="glass-card p-10 rounded-[2.5rem]">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-8">
                        <i data-lucide="qr-code" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-4">E-Verification</h3>
                    <p class="text-slate-500 leading-relaxed mb-6">Setiap kartu dilengkapi QR unik yang bisa discan
                        untuk verifikasi status siswa aktif di lingkungan sekolah.</p>
                    <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full w-[100%]"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Alur Kerja -->
    <section id="alur" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-100 rounded-full blur-3xl opacity-50">
                    </div>
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=1000"
                        class="rounded-[3rem] shadow-2xl relative z-10" alt="Dashboard Preview">
                    <div
                        class="absolute -bottom-6 -right-6 bg-slate-900 p-8 rounded-3xl text-white z-20 shadow-xl hidden md:block">
                        <p class="text-3xl font-black mb-1">99.9%</p>
                        <p class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Uptime System</p>
                    </div>
                </div>
                <div>
                    <h2 class="text-indigo-600 font-extrabold text-sm tracking-[0.2em] uppercase mb-4">Cara Kerja</h2>
                    <p class="text-4xl font-extrabold text-slate-900 mb-12">Sangat Mudah, <br>Hanya 3 Langkah.</p>

                    <div class="space-y-10">
                        <div class="flex gap-6">
                            <span class="text-4xl font-black step-number">01</span>
                            <div>
                                <h4 class="text-xl font-bold text-slate-900 mb-2">Impor Data</h4>
                                <p class="text-slate-500">Unggah file Excel atau hubungkan API Dapodik untuk memuat
                                    data siswa secara otomatis.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <span class="text-4xl font-black step-number">02</span>
                            <div>
                                <h4 class="text-xl font-bold text-slate-900 mb-2">Atur Template</h4>
                                <p class="text-slate-500">Pilih desain kartu, sesuaikan logo sekolah, dan tambahkan
                                    tanda tangan digital kepala sekolah.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <span class="text-4xl font-black step-number">03</span>
                            <div>
                                <h4 class="text-xl font-bold text-slate-900 mb-2">Terbitkan & Cetak</h4>
                                <p class="text-slate-500">Kartu siap diunduh oleh admin atau diakses langsung oleh
                                    siswa melalui dashboard masing-masing.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md" x-cloak>

        <div @click.away="modalOpen = false; result = null; errorMessage = ''"
            class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-[0_32px_64px_-15px_rgba(0,0,0,0.2)] relative overflow-hidden border border-white/20"
            x-show="modalOpen" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-12 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>

            <div class="relative p-8">
                <button @click="modalOpen = false; result = null; errorMessage = ''"
                    class="absolute top-8 right-8 p-2 bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all z-10">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <template x-if="!result">
                    <div class="max-w-md mx-auto py-6 space-y-8">
                        <div class="text-center">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wider">Live Verification</span>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Cek Kartu Pelajar</h3>
                            <p class="text-slate-500 mt-2 text-sm">Masukkan NISN untuk akses database pusat.</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="relative flex-1 group">
                                    <input type="text" x-model="nisn" @input="errorMessage = ''"
                                        placeholder="Masukkan NISN..."
                                        class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:bg-white outline-none font-bold text-slate-900 transition-all">
                                </div>

                                <button
                                    @click="
                                    if(!nisn) { errorMessage = 'Silakan masukkan nomor NISN!'; return; }
                                    errorMessage = ''; 
                                    loading = true; 
                                    fetch('/check-card/' + nisn)
                                        .then(response => response.json())
                                        .then(res => {
                                            loading = false;
                                            if(res.success) { 
                                                result = res.data; 
                                                $nextTick(() => { if(window.lucide) lucide.createIcons(); });
                                            } else { 
                                                errorMessage = res.message || 'NISN tidak ditemukan!'; 
                                            }
                                        })
                                        .catch(err => { 
                                            loading = false; 
                                            errorMessage = 'Gagal terhubung ke server.'; 
                                        })"
                                    class="px-5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-indigo-600 transition-all flex items-center justify-center gap-2">
                                    <span x-show="!loading" class="text-sm">Cek</span>
                                    <div x-show="loading"
                                        class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin">
                                    </div>
                                    <i x-show="!loading" data-lucide="search" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <div x-show="errorMessage" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-600"
                                x-cloak>
                                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                <p class="text-xs font-bold" x-text="errorMessage"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="result">
                    <div x-transition class="py-2">
                        <div class="flex flex-col md:flex-row gap-8 items-stretch">
                            <div
                                class="w-full md:w-1/3 flex flex-col items-center justify-center p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                                <div class="relative mb-4">
                                    <img :src="result.foto"
                                        class="w-24 h-32 object-cover rounded-2xl shadow-xl border-4 border-white">
                                    <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center shadow-lg border-2 border-white"
                                        :class="result.status === 'active' ? 'bg-emerald-500' : 'bg-rose-500'">
                                        <i :data-lucide="result.status === 'active' ? 'check' : 'alert-circle'"
                                            class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                                <h4 class="font-black text-slate-900 text-center leading-tight mb-1"
                                    x-text="result.nama"></h4>
                                <p class="text-[10px] font-normal text-slate-600 uppercase tracking-widest"
                                    x-text="nisn"></p>
                            </div>

                            <div class="flex-1 flex flex-col justify-between space-y-6">
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 mb-1">Status Verifikasi</h3>
                                    <p class="text-xs text-slate-400">Data terverifikasi oleh SMKN 1 Wonosari</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div :class="result.status === 'active' ? 'bg-emerald-500 shadow-emerald-100' :
                                        'bg-rose-500 shadow-rose-100'"
                                        class="p-5 rounded-[2rem] text-white shadow-lg transition-all duration-300 transform hover:scale-[1.02]">

                                        <p class="text-[8px] font-black uppercase tracking-[0.2em] opacity-80 mb-3">
                                            Status Keaktifan
                                        </p>

                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-white/20 rounded-xl">
                                                <i :data-lucide="result.status === 'active' ? 'shield-check' : 'shield-alert'"
                                                    class="w-5 h-5 text-white"></i>
                                            </div>
                                            <p class="text-sm font-black tracking-tight"
                                                x-text="result.status === 'active' ? 'AKTIF' : 'NON-AKTIF'">
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="p-5 rounded-[2rem] bg-indigo-600 text-white shadow-lg shadow-indigo-100 transition-all duration-300 transform hover:scale-[1.02]">
                                        <p class="text-[8px] font-black uppercase tracking-[0.2em] opacity-80 mb-3">
                                            Berlaku Hingga
                                        </p>

                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-white/20 rounded-xl">
                                                <i data-lucide="calendar" class="w-5 h-5 text-white"></i>
                                            </div>
                                            <p class="text-sm font-black font-mono tracking-tight"
                                                x-text="result.exp_date">
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <button @click="result = null; nisn = ''; errorMessage = ''"
                                    class="w-full py-4 bg-slate-50 text-slate-500 font-bold rounded-2xl hover:bg-slate-100 transition-all text-sm flex items-center justify-center gap-2">
                                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                    Cek Nomor Lain
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="bg-slate-50/50 px-8 py-4 flex items-center justify-between border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-indigo-500"></i>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Automated Verification
                        System SMKN 1 Wonosari</span>
                </div>
                <span class="text-[9px] font-black text-slate-300">v5.2.0-LS</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 pt-2 pb-12 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="pt-5 flex flex-col md:flex-row justify-between items-center gap-6 -mb-5">
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">© 2024 SMKN 1 Wonosari •
                    Built by Digital Team</p>
                <div class="flex gap-8 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                    <a href="#" class="hover:text-white">Privacy</a>
                    <a href="#" class="hover:text-white">Terms</a>
                    <a href="#" class="hover:text-white">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function refreshIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        document.addEventListener("DOMContentLoaded", refreshIcons);
    </script>
</body>

</html>
