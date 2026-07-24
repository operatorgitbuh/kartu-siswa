@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        openModal: false,
        editMode: false,
        search: '',
        formData: { id: '', name: '', selectedPermissions: [] },
    
        addRole() {
            this.editMode = false;
            this.formData = { id: '', name: '', selectedPermissions: [] };
            this.openModal = true;
        },
    
        editRole(role) {
            this.editMode = true;
            this.formData = {
                id: role.id,
                name: role.name,
                selectedPermissions: role.permissions.map(p => p.id)
            };
            this.openModal = true;
        }
    }">
        <nav class="flex mb-2 -mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="#" class="hover:text-emerald-600 transition">Dashboard</a></li>
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
                    <span class="text-emerald-600 font-bold">Roles</span>
                </li>
            </ol>
        </nav>

        <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Role</h2>
                <p class="text-slate-500 text-sm italic">Kelola grup otoritas akses pengguna (SMKN 1 Wonosari).</p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div
                class="p-5 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/30">
                <div class="relative w-full md:w-80">
                    <input type="text" x-model="search" placeholder="Cari nama role..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition text-sm shadow-sm">
                    <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <button @click="addRole()"
                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 transition-all font-bold text-sm shadow-xl shadow-emerald-100 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Role Baru
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th
                                class="px-6 py-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 w-10 text-center">
                                No</th>
                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Nama Role
                            </th>
                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Hak Akses
                                Terkait</th>
                            <th
                                class="px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">
                                Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($roles as $role)
                            <tr x-show="'{{ strtolower($role->name) }}'.includes(search.toLowerCase())"
                                class="hover:bg-emerald-50/30 transition-colors group">
                                <td class="px-6 py-6 text-slate-400 font-bold text-center text-xs">{{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-emerald-100">
                                            {{ strtoupper(substr($role->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span
                                                class="block font-black text-slate-700 text-sm uppercase tracking-tight">{{ $role->name }}</span>
                                            <span class="text-[9px] font-mono text-slate-400">UUID:
                                                {{ $role->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-wrap gap-1.5 max-w-sm">
                                        @forelse ($role->permissions as $p)
                                            <span
                                                class="px-2 py-0.5 bg-white border border-slate-200 text-slate-500 text-[10px] font-black rounded-lg uppercase tracking-tighter group-hover:border-emerald-200 group-hover:text-emerald-600 transition-colors shadow-sm">
                                                {{ $p->name }}
                                            </span>
                                        @empty
                                            <span class="text-[10px] text-slate-300 italic">No permissions assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex justify-center items-center gap-2">
                                        <button @click="editRole({{ $role }})"
                                            class="p-2 text-amber-500 hover:bg-amber-50 rounded-xl transition-all border border-transparent hover:border-amber-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button" onclick="confirmDelete('{{ $role->id }}')"
                                            class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <form id="delete-form-{{ $role->id }}"
                                            action="{{ route('roles.destroy', $role->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic font-medium">Belum
                                    ada role yang dikonfigurasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 text-center">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.3em]">End of Roles Registry</p>
            </div>
        </div>

        <div x-show="openModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-hidden" x-cloak>
            <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            <div @click.away="openModal = false" x-show="openModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] w-full max-w-lg overflow-hidden border border-slate-100 z-10">

                <div class="px-8 pt-8 pb-2 text-center relative">
                    <div
                        class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight"
                        x-text="editMode ? 'Edit Konfigurasi Role' : 'Tambah Role Baru'"></h3>
                    <p class="text-xs text-slate-400 mt-1 uppercase font-bold tracking-tighter">Otoritas SMKN 1 Wonosari
                    </p>

                    <button @click="openModal = false"
                        class="absolute top-6 right-8 text-slate-300 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="editMode ? `/roles/${formData.id}` : '/roles'" method="POST" class="px-8 pb-10 space-y-6">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama
                            Identitas Role</label>
                        <input type="text" name="name" x-model="formData.name" required
                            placeholder="Contoh: Admin Akademik"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition text-sm font-bold text-slate-700 uppercase tracking-tight">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest ml-1">Assign
                            Permissions</label>
                        <div
                            class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto p-4 bg-slate-50 rounded-2xl border border-slate-100 custom-scrollbar">
                            @foreach ($permissions as $perm)
                                <label
                                    class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-100 cursor-pointer hover:border-emerald-200 transition group shadow-sm">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                        :checked="formData.selectedPermissions.includes({{ $perm->id }})"
                                        class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                    <span
                                        class="text-[10px] font-black text-slate-500 group-hover:text-emerald-700 transition uppercase tracking-tighter">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <button type="button" @click="openModal = false"
                            class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                        <button type="submit"
                            class="flex-1 px-6 py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase hover:bg-emerald-700 shadow-xl shadow-emerald-100 transition-all active:scale-95">Simpan
                            Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.confirmDelete = function(id) {
                Swal.fire({
                    title: 'Hapus Role?',
                    text: "Semua user dengan role ini akan kehilangan akses spesifik tersebut!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-[2.5rem] border-0 shadow-2xl',
                        confirmButton: 'bg-rose-600 hover:bg-rose-700 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase mx-2 shadow-lg shadow-rose-100 transition-all active:scale-95',
                        cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 px-8 py-3 rounded-2xl font-black text-xs uppercase mx-2 transition-all active:scale-95'
                    }
                }).then((result) => {
                    if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
                })
            }
        });
    </script>
@endsection
