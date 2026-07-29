<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Kartu SMKN 1 Wonosari</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('main/img/logo-smk.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .login-card {
            max-height: 95vh;
            border-radius: 3rem;
        }

        .login-card::-webkit-scrollbar {
            width: 0px;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div
        class="max-w-4xl w-full grid lg:grid-cols-2 bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100 login-card">

        <div
            class="hidden lg:flex flex-col justify-between p-8 xl:p-10 bg-indigo-600 relative overflow-hidden rounded-l-[3rem]">
            <div class="absolute -top-24 -left-24 w-64 h-44 bg-indigo-500 rounded-full opacity-50"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-indigo-400 rounded-full opacity-30"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                            </path>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg tracking-tight uppercase">E-Kartu</span>
                </div>

                <h1 class="text-2xl xl:text-3xl font-extrabold text-white leading-tight mb-3">
                    Kelola Kartu Pelajar <br>Jadi Lebih Mudah.
                </h1>
                <p class="text-indigo-100 text-sm xl:text-base opacity-90">
                    Sistem manajemen cetak kartu siswa SMK Negeri 1 Wonosari.
                </p>
            </div>

            <div class="relative z-10 -mt-12">
                <div class="bg-indigo-500/30 p-5 rounded-[2rem] border border-white/10 backdrop-blur-md">
                    <p class="text-white italic text-[11px] xl:text-xs mb-3">"Efisiensi dalam pendataan adalah langkah
                        awal menuju digitalisasi sekolah yang lebih baik."</p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-7 h-7 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-[10px] shadow-inner">
                            OP</div>
                        <div>
                            <p class="text-white font-bold text-[10px]">Operator Sekolah</p>
                            <p class="text-indigo-200 text-[9px]">SMKN 1 Wonosari</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 lg:p-10 xl:p-6 flex flex-col justify-center bg-white rounded-r-[3rem]">
            <div class="mb-8 text-center lg:text-left">
                <h2 class="text-2xl font-bold text-slate-800 mb-0.5">Selamat Datang</h2>
                <p class="text-xs text-slate-500">Masuk ke akun operator Anda</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-3.5 xl:space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Email / Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="user@smkn1wonosari.sch.id"
                            class="w-full pl-10 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-sm"
                            required autofocus>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5 ml-1">
                        <label class="text-xs font-semibold text-slate-700">Kata Sandi</label>
                        <a href="#" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700">Lupa?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </span>
                        <input id="passwordInput" type="password" name="password" placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-sm"
                            required>
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-indigo-600">
                            <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path id="eyeOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path id="eyeOpen2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center ml-1">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-3.5 h-3.5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <label for="remember" class="ml-2 text-[11px] text-slate-600 cursor-pointer select-none">Ingat
                        saya</label>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all transform active:scale-[0.98] text-xs">
                    Masuk Sekarang
                </button>

                <p class="text-center text-slate-500 text-[11px]">
                    Belum punya akun? <a href="#" class="text-indigo-600 font-bold hover:underline ">Hubungi
                        Admin</a>
                    </p>
                    <a href="/">
                        <p class="text-[10px] text-center text-indigo-600  font-bold mt-3 -mb-3">Kembali Ke Landing</p>
                    </a>
            </form>

            <div class="pt-5 -mb-5 border-t border-slate-50 text-center">
                <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-bold mb-3">© 2024 SMKN 1 Wonosari</p>
                
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>
