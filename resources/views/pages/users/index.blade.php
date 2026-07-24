@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        showModal: false,
        editMode: false,
        search: '',
        formData: { id: '', name: '', email: '', password: '', avatar_url: '', role: '' }
    }">

        <nav class="flex mb-2 -mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="#" class="hover:text-indigo-600 transition">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-slate-900">Pengaturan</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-indigo-600 font-bold">Users</span>
                </li>
            </ol>
        </nav>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Pengguna</h2>
            <p class="text-slate-500 text-sm italic">Kelola akun operator dan guru SMKN 1 Wonosari.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div
                class="p-5 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/30">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative group">
                        <select onchange="window.location.href = '?perPage=' + this.value"
                            class="pl-3 pr-8 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-[13px] shadow-sm appearance-none cursor-pointer text-slate-600 font-bold hover:border-indigo-300">
                            @foreach ([5, 10, 20, 30, 50] as $val)
                                <option value="{{ $val }}" {{ request('perPage') == $val ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                        <div
                            class="absolute right-3 top-3.5 pointer-events-none text-slate-400 group-hover:text-indigo-500 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative w-full md:w-64">
                        <input type="text" x-model="search" placeholder="Cari nama atau email..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm shadow-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button
                        @click="editMode = false; formData = { id: '', name: '', email: '', password: '', avatar_url: '' }; showModal = true"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition font-bold text-sm shadow-lg shadow-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah User
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 w-10">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Profil</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Email</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Role</th>
                            {{-- <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</th> --}}
                            <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($users as $item)
                            <tr x-show="'{{ strtolower($item->name . $item->email) }}'.includes(search.toLowerCase())"
                                class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-slate-400 font-medium">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs uppercase overflow-hidden">
                                            @if ($item->avatars)
                                                <img src="{{ asset('storage/' . $item->avatars) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                {{ substr($item->name, 0, 2) }}
                                            @endif
                                        </div>
                                        <span class="font-normal text-slate-700">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $item->email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1 text-center">
                                        @foreach ($item->getRoleNames() as $role)
                                            @php
                                                // Logika warna khusus untuk ADMIN dan WALI_KELAS
                                                $colorClass = match(strtoupper($role)) {
                                                    'ADMIN' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                    'WALI_KELAS' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                    default => 'bg-slate-50 text-slate-500 border-slate-100',
                                                };
                                            @endphp
                                            
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest border {{ $colorClass }} shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ strtoupper($role) === 'ADMIN' ? 'bg-rose-500' : 'bg-indigo-500' }}"></span>
                                                {{ str_replace('_', ' ', $role) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button
                                            @click="editMode = true; formData = { 
                                                id: '{{ $item->id }}', 
                                                name: '{{ $item->name }}', 
                                                email: '{{ $item->email }}', 
                                                role: '{{ $item->roles->pluck('name')->first() }}', 
                                                avatar_url: '{{ $item->avatars ? asset('storage/' . $item->avatars) : '' }}' 
                                            }; showModal = true"
                                            class="flex items-center gap-1 px-3 py-1.5 text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-100 transition border border-amber-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" onclick="confirmDelete('{{ $item->id }}')"
                                            class="flex items-center gap-1 px-3 py-1.5 text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition border border-rose-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('users.destroy', $item->id) }}" method="POST"
                                            class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Data pengguna
                                    belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                    Showing <span class="text-indigo-600">{{ $users->firstItem() ?? 0 }}</span> to <span
                        class="text-indigo-600">{{ $users->lastItem() ?? 0 }}</span> of <span
                        class="text-slate-700">{{ $users->total() }}</span> Entries
                </div>
                {{ $users->links('vendor.pagination.custom') }}
            </div>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-hidden"
            x-cloak>

            <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            <div @click.away="showModal = false" x-show="showModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                class="relative bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] w-full max-w-md overflow-hidden border border-slate-100 z-10">

                <div class="px-8 pt-4 pb-4 relative">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight"
                        x-text="editMode ? 'Edit Pengguna' : 'Tambah Pengguna'"></h3>
                    <p class="text-xs text-slate-400 mt-1"
                        x-text="editMode ? 'Perbarui informasi akun pengguna.' : 'Daftarkan pengguna baru ke sistem.'"></p>

                    <button @click="showModal = false"
                        class="absolute top-4 right-8 text-slate-300 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="editMode ? '/users/' + formData.id : '{{ route('users.store') }}'" method="POST"
                    enctype="multipart/form-data" class="px-8 pb-3 space-y-1">
                    @csrf
                    <template x-if="editMode">@method('PUT')</template>

                    <div class="flex flex-col items-center justify-center space-y-3 py-2">
                        <div class="relative group cursor-pointer">
                            <div
                                class="w-24 h-24 rounded-full overflow-hidden ring-4 ring-slate-50 border border-slate-200 shadow-inner bg-slate-100 transition-transform group-hover:scale-105 duration-300">
                                <img :src="formData.avatar_url || 'https://ui-avatars.com/api/?name=' + (formData.name || 'U') + '&background=6366f1&color=fff'" 
                                    class="w-full h-full object-cover" 
                                    id="avatar_preview">
                            </div>

                            <label for="avatar_input"
                                class="absolute bottom-0 right-0 bg-indigo-600 p-2 rounded-full text-white shadow-lg border-2 border-white hover:bg-indigo-700 transition-colors cursor-pointer active:scale-90">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                            </label>

                            <input type="file" id="avatar_input" name="avatars" class="hidden" accept="image/*"
                                @change="const file = $event.target.files[0]; if(file) { formData.avatar_url = URL.createObjectURL(file) }">
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Foto Profil</span>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1 -mt-3">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Nama
                                Lengkap</label>
                            <input type="text" name="name" x-model="formData.name" required
                                placeholder="Masukkan nama..."
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Alamat
                                Email</label>
                            <input type="email" name="email" x-model="formData.email" required
                                placeholder="email@sekolah.sch.id"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm">
                        </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Role / Hak Akses</label>
                                <div class="relative group">
                                    <select name="roles" x-model="formData.role" required
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm appearance-none cursor-pointer">
                                        <option value="" disabled selected>Pilih Role...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        <div class="space-y-1.5" x-data="{ showPassword: false }">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1"
                                x-text="editMode ? 'Password (Kosongkan jika tetap)' : 'Password'"></label>
                            
                            <div class="relative group">
                                <input :type="showPassword ? 'text' : 'password'" 
                                    name="password" 
                                    :required="!editMode" 
                                    placeholder="••••••••"
                                    class="w-full mb-2 px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm pr-12">
                                
                                <button type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                                    
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>

                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 014.47-5.517M8.25 4.657A10.515 10.515 0 0112 4c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-1.15 0-2.233-.247-3.225-.694m-2.422-2.422l-4.5 4.5m4.5-4.5l4.5 4.5M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 active:scale-95 transition-all">Batal</button>
                        <button type="submit"
                            class="flex-1 px-6 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm hover:bg-indigo-700 shadow-xl shadow-indigo-100 active:scale-95 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.confirmDelete = function(id) {
                Swal.fire({
                    title: 'Hapus Pengguna?',
                    text: "Akun ini tidak akan bisa login kembali!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-[2rem] border-0 shadow-2xl',
                        confirmButton: 'bg-rose-600 hover:bg-rose-700 text-white px-6 py-2.5 rounded-xl font-bold mx-2 shadow-lg shadow-rose-100 transition-all active:scale-95',
                        cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-2.5 rounded-xl font-bold mx-2 transition-all active:scale-95'
                    }
                }).then((result) => {
                    if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
                })
            }

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}",
                    customClass: {
                        popup: 'rounded-2xl bg-white shadow-xl border border-emerald-50'
                    }
                });
            @endif
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
