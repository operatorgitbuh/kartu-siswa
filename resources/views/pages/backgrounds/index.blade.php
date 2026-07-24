@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="backgroundManager()">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 -mt-2">
            {{-- Grup Kiri: Breadcrumb & Title --}}
            <div class="space-y-2">
                {{-- Breadcrumbs --}}
                <nav class="flex mb-2 -mt-4 md:-mt-2 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li><a href="/" class="hover:text-indigo-600 transition">Dashboard</a></li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                            </svg>
                            <span class="text-slate-900">Manajemen</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                            </svg>
                            <span class="text-indigo-600 font-bold">Background</span>
                        </li>
                    </ol>
                </nav>

                {{-- Title & Subtitle --}}
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                        Manajemen Background
                    </h2>
                    <p class="text-slate-500 text-sm mt-1 flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                        Otomasi pendataan template background SMKN 1 Wonosari
                    </p>
                </div>
            </div>

            {{-- Grup Kanan: Button --}}
            <div class="flex items-center">
                <button @click="openCreateModal()"
                    class="w-full md:w-auto px-2 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-bold uppercase tracking-widest shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <div class="p-1 bg-white/20 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    Tambah
                </button>
            </div>
        </div>

        {{-- List Template --}}
        <div
            class="bg-white rounded-3xl md:rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            <div class="px-5 md:px-8 py-4 md:py-6 bg-slate-50/50 border-b border-slate-100">
                <h1 class="text-base md:text-lg font-bold text-slate-800 tracking-tight">Daftar Template</h1>
                <p class="text-slate-500 text-[9px] md:text-[10px] uppercase tracking-widest mt-0.5">Kelola visual kartu
                    digital</p>
            </div>

            <div class="overflow-x-auto">
                {{-- Table Mode (Desktop) --}}
                <table class="hidden md:table w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 font-bold text-center w-16">#</th>
                            <th class="px-6 py-4 font-bold text-center">Preview Template</th>
                            <th class="px-6 py-4 font-bold">Informasi Detail</th>
                            <th class="px-6 py-4 font-bold text-center w-32">Status</th>
                            <th class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse ($backgrounds as $bg)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                {{-- Nomor --}}
                                <td class="px-6 py-4 text-center font-medium text-slate-400">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Preview Gambar --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-center -space-x-4">
                                        <div
                                            class="w-20 h-13 rounded-lg border-2 border-white shadow-sm bg-slate-100 overflow-hidden shrink-0 hover:z-10 transition-transform hover:scale-105">
                                            <img src="{{ $bg->background_front ? asset('storage/' . $bg->background_front) : 'https://placehold.co/600x400?text=Depan' }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div
                                            class="w-20 h-13 rounded-lg border-2 border-white shadow-sm bg-slate-100 overflow-hidden shrink-0 hover:z-10 transition-transform hover:scale-105">
                                            <img src="{{ $bg->background_back ? asset('storage/' . $bg->background_back) : 'https://placehold.co/600x400?text=Belakang' }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                </td>

                                {{-- Informasi --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-700 text-base leading-tight">{{ $bg->name }}</span>
                                        <span
                                            class="text-[9px] text-slate-400 font-mono italic mt-1 bg-slate-50 w-fit px-1.5 py-0.5 rounded leading-none">ID:
                                            {{ $bg->id }}</span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex px-3 py-1 {{ $bg->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} rounded-full text-[10px] font-black uppercase tracking-tighter border {{ $bg->is_active ? 'border-emerald-100' : 'border-slate-200' }}">
                                        {{ $bg->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openEditModal({{ json_encode($bg) }}, '{{ asset('storage') }}')"
                                            class="flex items-center gap-1 px-3 py-1.5 text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-100 transition border border-amber-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <span>Edit</span>
                                        </button>

                                        <button type="button" @click="confirmDelete($el.nextElementSibling)"
                                            class="flex items-center gap-1 px-3 py-1.5 text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition border border-rose-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                        <form action="{{ route('backgrounds.destroy', $bg->id) }}" method="POST"
                                            class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-slate-400 italic font-medium">
                                    Belum ada template background yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Mobile Mode (Stacked Cards) --}}
                <div class="md:hidden divide-y divide-slate-100 bg-white">
                    @forelse ($backgrounds as $bg)
                        <div class="p-5 flex flex-col gap-5">
                            <div class="flex items-start gap-4">
                                {{-- Stacked Preview Mobile --}}
                                <div class="flex -space-x-4 shrink-0">
                                    <img src="{{ $bg->background_front ? asset('storage/' . $bg->background_front) : 'https://placehold.co/600x400' }}"
                                        class="w-14 h-9 rounded-lg border-2 border-white shadow-sm object-cover">
                                    <img src="{{ $bg->background_back ? asset('storage/' . $bg->background_back) : 'https://placehold.co/600x400' }}"
                                        class="w-14 h-9 rounded-lg border-2 border-white shadow-sm object-cover">
                                </div>
                                {{-- Info Mobile --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-slate-700 truncate text-base leading-tight">
                                        {{ $bg->name }}</h4>
                                    <span
                                        class="text-[9px] text-slate-400 font-mono uppercase tracking-tighter bg-slate-50 px-1 rounded">ID:
                                        {{ $bg->id }}</span>
                                </div>
                                {{-- Status Badge Mobile --}}
                                <span
                                    class="shrink-0 px-2.5 py-1 {{ $bg->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }} border rounded-full text-[9px] font-black uppercase">
                                    {{ $bg->is_active ? 'Aktif' : 'Off' }}
                                </span>
                            </div>

                            {{-- Button Action Mobile (Identik dengan Desktop) --}}
                            <div class="flex gap-2">
                                <button @click="openEditModal({{ json_encode($bg) }}, '{{ asset('storage') }}')"
                                    class="flex-1 flex justify-center items-center gap-2 py-2.5 text-amber-600 bg-amber-50 rounded-xl border border-amber-100 font-bold text-xs transition active:bg-amber-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    EDIT
                                </button>

                                <button type="button" @click="confirmDelete($el.nextElementSibling)"
                                    class="flex-1 flex justify-center items-center gap-2 py-2.5 text-rose-600 bg-rose-50 rounded-xl border border-rose-100 font-bold text-xs transition active:bg-rose-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    HAPUS
                                </button>
                                <form action="{{ route('backgrounds.destroy', $bg->id) }}" method="POST"
                                    class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-400 text-xs italic font-medium">Belum ada template
                            background.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Modal Form --}}
        <div x-show="openModal" class="fixed inset-0 z-[99] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false" x-show="openModal"
                x-transition:opacity></div>
            <div class="relative min-h-screen flex items-end md:items-center justify-center p-0 md:p-4">
                <div class="relative bg-white w-full max-w-2xl rounded-t-[2.5rem] md:rounded-[2.5rem] shadow-2xl overflow-hidden"
                    x-show="openModal" x-transition:scale.95 x-transition:origin.bottom>
                    <form :action="formAction" method="POST" enctype="multipart/form-data">
                        @csrf
                        <template x-if="isEdit">@method('PUT')</template>

                        <div class="px-6 md:px-8 pt-8 pb-4 border-b border-slate-50">
                            <div class="w-12 h-1.5 bg-slate-100 rounded-full mx-auto mb-4 md:hidden"></div>
                            <h3 class="text-xl font-bold text-slate-800"
                                x-text="isEdit ? 'Edit Template' : 'Template Baru'"></h3>
                        </div>

                        <div class="p-6 md:p-8 space-y-5 md:space-y-6">
                            <div class="flex flex-col md:grid md:grid-cols-3 gap-5 md:gap-6">
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-[10px] text-slate-400 uppercase font-bold mb-1 ml-1">Nama</label>
                                    <input type="text" name="name" x-model="formData.name" placeholder="Nama Template" required
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] text-slate-400 uppercase font-bold mb-1 ml-1">Status</label>
                                    <select name="is_active" x-model="formData.is_active"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm outline-none">
                                        <option value="1">Aktif</option>
                                        <option value="0">Non-Aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 md:gap-6">
                                <div class="space-y-2 text-center">
                                    <span
                                        class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Depan</span>
                                    <div
                                        class="relative aspect-[3/2] md:h-44 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl md:rounded-3xl flex items-center justify-center overflow-hidden">
                                        <img x-show="previews.front" :src="previews.front"
                                            class="w-full h-full object-cover">
                                        <svg x-show="!previews.front" class="w-8 h-8 text-slate-200" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <input type="file" name="background_front"
                                            class="absolute inset-0 opacity-0 cursor-pointer"
                                            @change="handleFileChange($event, 'front')">
                                    </div>
                                </div>
                                <div class="space-y-2 text-center">
                                    <span
                                        class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Belakang</span>
                                    <div
                                        class="relative aspect-[3/2] md:h-44 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl md:rounded-3xl flex items-center justify-center overflow-hidden">
                                        <img x-show="previews.back" :src="previews.back"
                                            class="w-full h-full object-cover">
                                        <svg x-show="!previews.back" class="w-8 h-8 text-slate-200" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <input type="file" name="background_back"
                                            class="absolute inset-0 opacity-0 cursor-pointer"
                                            @change="handleFileChange($event, 'back')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 md:px-8 pb-8 flex flex-col-reverse md:flex-row gap-3">
                            <button type="button" @click="openModal = false"
                                class="py-4 md:py-3 md:flex-1 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-bold uppercase tracking-widest">Batal</button>
                            <button type="submit"
                                class="py-4 md:py-3 md:flex-1 bg-indigo-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-indigo-100"
                                x-text="isEdit ? 'Update' : 'Simpan'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Konfigurasi Dasar Toast (Sesuai style index kamu)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInRight animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight animate__faster'
                }
            });

            // --- CEK SESSION LARAVEL ---
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    iconColor: '#10b981',
                    title: "{{ session('success') }}",
                    customClass: {
                        popup: 'rounded-2xl bg-white/95 backdrop-blur-sm border border-emerald-50 shadow-xl p-2 pl-4 pr-8 w-auto min-w-[280px] relative',
                        title: 'text-slate-600 font-semibold text-xs ml-1',
                        timerProgressBar: 'bg-emerald-500 h-[1.5px]',
                        closeButton: 'absolute top-2 right-2 text-slate-300 hover:text-rose-500 transition-colors focus:shadow-none border-none text-lg p-0'
                    }
                });
            @endif

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    iconColor: '#f43f5e',
                    title: "{{ $errors->first() }}",
                    customClass: {
                        popup: 'rounded-2xl bg-white/95 backdrop-blur-sm border border-rose-50 shadow-xl p-2 pl-4 pr-8 w-auto min-w-[280px] relative',
                        title: 'text-slate-600 font-semibold text-xs ml-1',
                        timerProgressBar: 'bg-rose-500 h-[1.5px]',
                        closeButton: 'absolute top-2 right-2 text-slate-300 hover:text-rose-500 transition-colors focus:shadow-none border-none text-lg p-0'
                    }
                });
            @endif
        });

        // 2. Fungsi Alpine.js untuk Background Manager
        function backgroundManager() {
            return {
                openModal: false,
                isEdit: false,
                formAction: "{{ route('backgrounds.store') }}",
                formData: {
                    id: '',
                    name: '',
                    is_active: 1
                },
                previews: {
                    front: null,
                    back: null
                },

                openCreateModal() {
                    this.isEdit = false;
                    this.formAction = "{{ route('backgrounds.store') }}";
                    this.formData = {
                        id: '',
                        name: '',
                        is_active: 1
                    };
                    this.previews = {
                        front: null,
                        back: null
                    };
                    this.openModal = true;
                },

                openEditModal(data, storageUrl) {
                    this.isEdit = true;
                    this.formAction = `/backgrounds/${data.id}`;
                    this.formData = {
                        id: data.id,
                        name: data.name,
                        is_active: data.is_active
                    };
                    this.previews = {
                        front: data.background_front ? `${storageUrl}/${data.background_front}` : null,
                        back: data.background_back ? `${storageUrl}/${data.background_back}` : null
                    };
                    this.openModal = true;
                },

                handleFileChange(event, side) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews[side] = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                confirmDelete(formElement) {
                    Swal.fire({
                        title: 'Hapus Template?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-[2rem] border-0 shadow-2xl',
                            title: 'text-slate-800 font-bold text-lg',
                            htmlContainer: 'text-slate-500 text-sm',
                            confirmButton: 'bg-rose-600 hover:bg-rose-700 text-white px-6 py-2.5 rounded-xl font-bold mx-2 shadow-lg shadow-rose-100 transition-all active:scale-95',
                            cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-2.5 rounded-xl font-bold mx-2 transition-all active:scale-95'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) formElement.submit();
                    });
                }
            }
        }
    </script>
@endsection
