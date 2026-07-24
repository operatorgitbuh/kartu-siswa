@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <nav class="flex mb-3 -mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a></li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                </li>
                <li><a href="{{ route('users.index') }}" class="hover:text-indigo-600 transition">User</a>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-indigo-600 font-bold">Edit User</span>
                </li>
            </ol>
        </nav>

        <div class="mb-4 pb-4 border-b border-slate-100">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Pengguna</h2>
            <p class="text-slate-500 text-sm italic">Memperbarui informasi akun anda</p>
        </div>

        <form action="{{ route('users.updateProfile', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-3">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm text-center">
                        <div class="relative inline-block mx-auto mb-5">
                            <div
                                class="h-36 w-36 rounded-3xl border-4 border-white shadow-xl overflow-hidden bg-slate-50 flex items-center justify-center ring-1 ring-slate-100">
                                <img id="avatar-preview"
                                    src="{{ $user->avatars ? asset('storage/' . $user->avatars) : '#' }}"
                                    class="{{ $user->avatars ? '' : 'hidden' }} w-full h-full object-cover">

                                @if (!$user->avatars)
                                    <div id="avatar-placeholder" class="text-4xl text-indigo-600 uppercase">
                                        {{ collect(explode(' ', $user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->implode('') }}
                                    </div>
                                @endif
                            </div>

                            <label for="avatars"
                                class="absolute -bottom-2 -right-2 bg-indigo-600 p-2.5 rounded-xl shadow-lg text-white cursor-pointer hover:bg-indigo-700 transition-all hover:scale-110">
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
                        <br>
                        <p class="text-slate-600 mb-1 text-[12px] font-bold uppercase rounded-full">{{ $user->name }}</p>
                        <span class="px-3 py-1 text-slate-600 text-[10px] font-bold uppercase rounded-full tracking-widest">
                            @foreach ($user->getRoleNames() as $role)
                                @php
                                    $colorClass = match (strtoupper($role)) {
                                        'ADMIN' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'WALI_KELAS' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        default => 'bg-slate-50 text-slate-500 border-slate-100',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest border {{ $colorClass }} shadow-sm">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-1.5 {{ strtoupper($role) === 'ADMIN' ? 'bg-rose-500' : 'bg-indigo-500' }}"></span>
                                    {{ str_replace('_', ' ', $role) }}
                                </span>
                            @endforeach
                        </span>
                    </div>
                </div>

                <div class="lg:col-span-9 space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-7 relative z-10">

                            <div class="md:col-span-2 group">
                                <label class="text-[11px] text-slate-400 uppercase ml-1 mb-2 block tracking-wider">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm shadow-sm"
                                    required>
                            </div>

                            <div class="group">
                                <label
                                    class="text-[11px] text-slate-400 uppercase ml-1 mb-2 block tracking-wider">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm shadow-sm"
                                    required>
                            </div>

                            <div class="group">
                                <label class="text-[11px] text-slate-400 uppercase ml-1 mb-2 block tracking-wider">Role /
                                    Hak Akses</label>
                                <select name="role"
                                    class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm shadow-sm">
                                    <option value="ADMIN" {{ $user->role == 'ADMIN' ? 'selected' : '' }}>
                                        Administrator
                                    </option>
                                    <option value="WALI_KELAS" {{ $user->role == 'WALI_KELAS' ? 'selected' : '' }}>
                                        Wali Kelas
                                    </option>

                                </select>
                            </div>

                            <div class="md:col-span-2 group" x-data="{ show: false }">
                                <label class="text-[11px] text-slate-400 uppercase ml-1 mb-2 block tracking-wider">Ganti
                                    Password (Kosongkan jika tidak diubah)</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" minlength="8"
                                        placeholder="Ketikan disini password anda"
                                        class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm shadow-sm">
                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-4 flex items-center text-slate-400">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t border-slate-50 flex items-center justify-end gap-4">
                            <a href="{{ route('users.index') }}"
                                class="px-5 py-3 text-xs text-slate-400 uppercase tracking-widest">Batal</a>
                            <button type="submit"
                                class="px-8 py-3 bg-indigo-600 text-white rounded-2xl text-xs shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition-all uppercase tracking-widest">
                                Simpan
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Konfigurasi Dasar Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // 2. Munculkan Toast jika ada Session Success (Berhasil Simpan)
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}",
                    customClass: {
                        popup: 'rounded-2xl bg-white shadow-xl border border-emerald-50'
                    }
                });
            @endif

            // 3. Munculkan Toast jika ada Error (Gagal Validasi)
            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: "{{ $errors->first() }}",
                    customClass: {
                        popup: 'rounded-2xl bg-white shadow-xl border border-rose-50'
                    }
                });
            @endif
        });
    </script>
@endsection
