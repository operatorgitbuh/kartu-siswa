@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <nav class="flex mb-3 -mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('wali-kelas.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-indigo-600 font-bold">Profil Saya</span>
                </li>
            </ol>
        </nav>

        <div class="mb-4 pb-4 border-b border-slate-100">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Profil</h2>
            <p class="text-slate-500 text-sm italic">Kelola informasi akun Anda di SMKN 1 Wonosari.</p>
        </div>

        <form action="{{ route('wali-kelas.users.updateWakel', Auth::user()->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-3">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm text-center">
                        <div class="relative inline-block mx-auto mb-5">
                            <div
                                class="h-36 w-36 rounded-3xl border-4 border-white shadow-xl overflow-hidden bg-slate-50 flex items-center justify-center ring-1 ring-slate-100">
                                <img id="avatar-preview"
                                    src="{{ Auth::user()->avatars ? asset('storage/' . Auth::user()->avatars) : '#' }}"
                                    class="{{ Auth::user()->avatars ? '' : 'hidden' }} w-full h-full object-cover">

                                @if (!Auth::user()->avatars)
                                    <div id="avatar-placeholder" class="text-4xl text-indigo-600 uppercase">
                                        {{ collect(explode(' ', Auth::user()->name))->map(fn($name) => substr($name, 0, 1))->take(2)->implode('') }}
                                    </div>
                                @endif
                            </div>

                            <label for="avatars"
                                class="absolute -bottom-2 -right-2 bg-indigo-600 p-2.5 rounded-xl shadow-lg text-white cursor-pointer hover:bg-indigo-700 transition-all hover:scale-110 active:scale-95">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" id="avatars" name="avatars" class="hidden" accept="image/*"
                                    onchange="previewImage(this)">
                            </label>
                        </div>
                        <h3 class="text-md text-slate-800 leading-tight">{{ Auth::user()->name }}</h3>
                        <p class="text-[10px] text-slate-700 mt-1 uppercase tracking-[0.2em]">
                            @if (Auth::user()->classroom)
                                Wali Kelas : {{ Auth::user()->classroom->classroom }} - {{ Auth::user()->classroom->code_classroom }}
                            @else
                                Wali Kelas Aktif
                            @endif
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-9 space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-50">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-7 relative z-10">

                            <div class="md:col-span-2 group">
                                <label
                                    class="text-[11px] text-slate-400 uppercase ml-1 mb-2 block tracking-wider group-focus-within:text-indigo-600 transition-colors">
                                    Nama Lengkap
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-sm text-slate-700 shadow-sm"
                                        placeholder="Masukkan nama lengkap...">
                                </div>
                            </div>

                            <div class="md:col-span-2 group">
                                <label
                                    class="text-[11px] text-slate-400 uppercase ml-1 mb-2 block tracking-wider group-focus-within:text-indigo-600 transition-colors">
                                    Alamat Email
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-sm text-slate-700 shadow-sm"
                                        placeholder="email@contoh.com" readonly>
                                </div>
                            </div>

                            <div class="md:col-span-2 group" x-data="{ show: false }">
                                <div class="flex justify-between items-center mb-2 ml-1">
                                    <label
                                        class="text-[11px] text-slate-400 uppercase tracking-wider group-focus-within:text-indigo-600 transition-colors">
                                        Keamanan Password
                                    </label>
                                    <span class="text-[10px] text-slate-400 italic">* Minimal 8 karakter</span>
                                </div>

                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </span>

                                    <input :type="show ? 'text' : 'password'" name="password" minlength="8"
                                        class="w-full pl-11 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-sm text-slate-700 shadow-sm"
                                        placeholder="Isi hanya jika ingin mengganti password">

                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="text-red-500 text-[10px] mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t border-slate-50 flex items-center justify-end gap-4">
                            <a href="{{ route('wali-kelas.dashboard') }}"
                                class="px-5 py-3 text-xs text-slate-400 hover:text-slate-600 transition-all uppercase tracking-widest">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-8 py-3 bg-indigo-600 text-white rounded-2xl text-xs shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 active:scale-95 transition-all uppercase tracking-widest">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
