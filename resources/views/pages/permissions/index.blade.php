@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        showModal: false,
        editMode: false,
        search: '',
        formData: { id: '', name: '' },

        openAddModal() {
            this.editMode = false;
            this.formData = { id: '', name: '' };
            this.showModal = true;
        },

        openEditModal(id, name) {
            this.editMode = true;
            this.formData = { id: id, name: name };
            this.showModal = true;
        }
    }">

        <nav class="flex mb-2 -mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="#" class="hover:text-emerald-600 transition">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-slate-900">Pengaturan</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-emerald-600 font-bold">Permissions</span>
                </li>
            </ol>
        </nav>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Permission</h2>
            <p class="text-slate-500 text-sm italic">Daftar unit terkecil hak akses sistem (Level: SMKN 1 Wonosari).</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/30">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-80">
                        <input type="text" x-model="search" placeholder="Cari nama permission..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none transition text-sm shadow-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button @click="openAddModal()"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 transition font-bold text-sm shadow-lg shadow-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Permission
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 w-10 text-center">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Nama Permission</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">UUID / ID</th>
                            <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($permissions as $perm)
                            <tr x-show="'{{ strtolower($perm->name) }}'.includes(search.toLowerCase())"
                                class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-slate-400 font-medium text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-[10px]">
                                            PR
                                        </div>
                                        <span class="font-bold text-slate-700 uppercase tracking-wide">{{ $perm->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-mono text-[10px]">{{ $perm->id }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" @click="openEditModal('{{ $perm->id }}', '{{ $perm->name }}')"
                                            class="flex items-center gap-1 px-3 py-1.5 text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-100 transition border border-amber-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </button>

                                        <button type="button" onclick="confirmDelete('{{ $perm->id }}')"
                                            class="flex items-center gap-1 px-3 py-1.5 text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition border border-rose-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                        <form id="delete-form-{{ $perm->id }}"
                                            action="{{ route('permissions.destroy', $perm->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Data permission belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest text-center">Ujung Daftar Permission</p>
            </div>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-hidden" x-cloak>
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            <div @click.away="showModal = false" x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
                class="relative bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] w-full max-w-md overflow-hidden border border-slate-100 z-10">

                <div class="px-8 pt-6 pb-2 relative text-center">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight" x-text="editMode ? 'Edit Permission' : 'Permission Baru'"></h3>
                    <p class="text-xs text-slate-400 mt-1">Gunakan format lowercase dan "-" sebagai pemisah.</p>

                    <button @click="showModal = false" class="absolute top-6 right-8 text-slate-300 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="editMode ? '{{ route('permissions.index') }}/' + formData.id : '{{ route('permissions.store') }}'" method="POST" class="px-8 pb-10 space-y-6">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Nama Permission</label>
                        <input type="text" name="name" x-model="formData.name" required placeholder="Contoh: input-nilai-siswa"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition text-sm">
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 active:scale-95 transition-all">Batal</button>
                        <button type="submit"
                            class="flex-1 px-6 py-4 bg-emerald-600 text-white rounded-2xl font-bold text-sm hover:bg-emerald-700 shadow-xl shadow-emerald-100 active:scale-95 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.confirmDelete = function(id) {
                Swal.fire({
                    title: 'Hapus Permission?',
                    text: "Role yang menggunakan permission ini akan kehilangan hak akses tersebut!",
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
                    customClass: { popup: 'rounded-2xl bg-white shadow-xl border border-emerald-50' }
                });
            @endif
        });
    </script>
@endsection