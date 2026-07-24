@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        showModal: false,
        showImportModal: false,
        editMode: false,
        search: '',
        formData: { id: '', classroom: '', code_classroom: '', name_classroom: '', user_id: '' },
        showListModal: false,
        currentClassName: '',
        studentsList: []
    }">

        <nav class="flex mb-2 -mt-4 md:-mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="#" class="hover:text-indigo-600 transition">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-slate-900">Data Master</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-indigo-600 font-bold">Classroom</span>
                </li>
            </ol>
        </nav>

        <div class="mb-2">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Kelas</h2>
            <p class="text-slate-500 text-sm italic">Otomasi pendataan ruang kelas SMKN 1 Wonosari.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div
                class="p-5 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/30">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative group">
                        <select onchange="window.location.href = '?perPage=' + this.value"
                            class="pl-3 pr-8 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-[13px] shadow-sm appearance-none cursor-pointer text-slate-600 font-bold hover:border-indigo-300">
                            @foreach ([5, 10, 20, 30, 50] as $val)
                                <option value="{{ $val }}"
                                    {{ request('perPage') == $val || (!request('perPage') && $val == 10) ? 'selected' : '' }}>
                                    {{ $val }}
                                </option>
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
                        <input type="text" x-model="search" placeholder="Cari data kelas..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm shadow-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button @click="showImportModal = true"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl hover:bg-emerald-100 transition font-bold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Import
                    </button>

                    <button
                        @click="editMode = false; formData = { id: '', classroom: '', code_classroom: '', name_classroom: '', user_id: '' }; showModal = true"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition font-bold text-sm shadow-lg shadow-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 w-10">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Tingkat</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Kode</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Nama Kelas
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">
                                Siswa</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Wali Kelas
                            </th>
                            <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($classrooms as $item)
                            <tr x-show="'{{ strtolower($item->classroom . $item->name_classroom . $item->code_classroom . ($item->user->name ?? '')) }}'.includes(search.toLowerCase())"
                                class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-slate-400 font-medium">
                                    {{ ($classrooms->currentPage() - 1) * $classrooms->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-bold text-slate-700">{{ $item->classroom }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50/50 px-2 py-1 rounded-lg border border-indigo-100">
                                        {{ $item->code_classroom }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $item->name_classroom }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                        @click="
                                                showListModal = true; 
                                                currentClassName = '{{ $item->classroom }} {{ $item->name_classroom }}';
                                                studentsList = {{ $item->students->map(function ($s) {
                                                        return [
                                                            'name' => $s->name,
                                                            'hasCard' => $s->card ? true : false,
                                                            'nisn' => $s->nisn,
                                                        ];
                                                    })->toJson() }};
                                            "
                                        class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 transition cursor-pointer">
                                        {{ $item->students_count }} Siswa
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full {{ $item->user_id ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                        <span>{{ $item->user->name ?? 'Kosong' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button
                                            @click="editMode = true; formData = { id: '{{ $item->id }}', classroom: '{{ $item->classroom }}', code_classroom: '{{ $item->code_classroom }}', name_classroom: '{{ $item->name_classroom }}', user_id: '{{ $item->user_id }}' }; showModal = true"
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
                                            action="{{ route('classrooms.destroy', $item->id) }}" method="POST"
                                            class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Data kelas belum
                                    tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                    Showing <span class="text-indigo-600">{{ $classrooms->firstItem() ?? 0 }}</span>
                    to <span class="text-indigo-600">{{ $classrooms->lastItem() ?? 0 }}</span>
                    of <span class="text-slate-700">{{ $classrooms->total() }}</span> Entries
                </div>

                {{ $classrooms->links('vendor.pagination.custom') }}
            </div>
        </div>
        <div x-show="showListModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[99] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>

            <div @click.away="showListModal = false"
                class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl overflow-hidden border border-slate-100 flex flex-col">

                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                            <span x-text="'Daftar Siswa ' + currentClassName"></span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 ml-4">Monitoring status kepemilikan kartu identitas siswa</p>
                    </div>
                    <button @click="showListModal = false"
                        class="p-2 bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto max-h-[60vh] custom-scrollbar bg-slate-50/30">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div class="space-y-4">
                            <div class="flex items-center justify-between px-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-widest">Belum Ada Kartu
                                    </h4>
                                </div>
                                <span class="bg-rose-100 text-rose-700 text-xs px-3 py-1 rounded-full font-black"
                                    x-text="studentsList.filter(s => !s.hasCard).length"></span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="student in studentsList.filter(s => !s.hasCard)" :key="student.nisn">
                                    <div
                                        class="group flex items-center gap-4 p-4 rounded-3xl border border-white bg-white shadow-sm hover:shadow-md hover:border-rose-100 transition-all duration-300">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[13px] font-bold text-slate-800 truncate"
                                                x-text="student.name"></p>
                                            <p class="text-[11px] text-slate-400 font-medium"
                                                x-text="'NISN: ' + student.nisn"></p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span
                                                class="text-[9px] px-2 py-1 bg-rose-50 text-rose-600 rounded-lg font-bold uppercase border border-rose-100">PROSES</span>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="studentsList.filter(s => !s.hasCard).length === 0">
                                    <div
                                        class="text-center py-10 bg-white/50 rounded-3xl border border-dashed border-slate-200">
                                        <p class="text-sm text-slate-400 font-medium italic">Semua siswa sudah memiliki
                                            kartu ✨</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between px-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-widest">Sudah Ada Kartu
                                    </h4>
                                </div>
                                <span class="bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full font-black"
                                    x-text="studentsList.filter(s => s.hasCard).length"></span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="student in studentsList.filter(s => s.hasCard)" :key="student.nisn">
                                    <div
                                        class="group flex items-center gap-4 p-4 rounded-3xl border border-white bg-white shadow-sm hover:shadow-md hover:border-emerald-100 transition-all duration-300">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[13px] font-bold text-slate-800 truncate"
                                                x-text="student.name"></p>
                                            <p class="text-[11px] text-slate-400 font-medium"
                                                x-text="'NISN: ' + student.nisn"></p>
                                        </div>
                                        <div class="flex-shrink-0 text-emerald-500">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.24.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="studentsList.filter(s => s.hasCard).length === 0">
                                    <div
                                        class="text-center py-10 bg-white/50 rounded-3xl border border-dashed border-slate-200">
                                        <p class="text-sm text-slate-400 font-medium italic">Belum ada data kartu di kelas
                                            ini.</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="p-8 bg-white border-t border-slate-50 flex justify-between items-center">
                    <div class="text-[11px] text-slate-400 font-medium">
                        Sistem Monitoring Kartu - SMKN 1 Wonosari
                    </div>
                    <button @click="showListModal = false"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-10 py-3 rounded-2xl font-bold transition-all active:scale-95 shadow-lg shadow-slate-200 text-sm tracking-wide">
                        Tutup Review
                    </button>
                </div>
            </div>
        </div>
        <div x-show="showModal"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

            <div @click.away="showModal = false" x-show="showModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8"
                class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden border border-white/50">

                <div class="px-8 pt-8 pb-4 relative">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight"
                        x-text="editMode ? 'Perbarui Kelas' : 'Kelas Baru'"></h3>
                    <p class="text-sm text-slate-400 mt-1"
                        x-text="editMode ? 'Sesuaikan informasi detail ruang kelas.' : 'Tambahkan ruang kelas baru ke database.'">
                    </p>

                    <button @click="showModal = false"
                        class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="editMode ? '/classrooms/' + formData.id : '{{ route('classrooms.store') }}'" method="POST"
                    class="px-8 pb-10 space-y-5">
                    @csrf
                    <template x-if="editMode">@method('PUT')</template>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">
                                Tingkat / Kelas
                            </label>
                            <div class="relative group">
                                <select name="classroom" x-model="formData.classroom" required
                                    class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none appearance-none transition-all duration-200 text-sm">
                                    <option value="" disabled selected>Pilih Tingkat...</option>
                                    <option value="X">Tingkat X</option>
                                    <option value="XI">Tingkat XI</option>
                                    <option value="XII">Tingkat XII</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 group-hover:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Kode
                                Unik</label>
                            <input type="text" name="code_classroom" x-model="formData.code_classroom" required
                                placeholder="AKL, ATN, ATK"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all duration-200 text-sm">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">
                            Kompetensi Keahlian / Jurusan
                        </label>
                        <div class="relative group">
                            <select name="name_classroom" x-model="formData.name_classroom" required
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none appearance-none transition-all duration-200 text-sm cursor-pointer">
                                <option value="" disabled selected>Pilih Jurusan...</option>

                                @foreach ($listJurusan as $jurusan)
                                    <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                                @endforeach
                            </select>

                            <div
                                class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 group-hover:text-indigo-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center mb-1 ml-1">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                Wali Kelas
                            </label>

                            <button type="button" x-show="formData.user_id" @click="formData.user_id = ''"
                                class="text-[9px] text-rose-500 hover:text-rose-700 font-bold uppercase tracking-tighter transition-colors">
                                × Kosongkan Wali Kelas
                            </button>
                        </div>

                        <div class="relative group">
                            <select name="user_id" x-model="formData.user_id"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none appearance-none transition-all duration-200 text-sm">

                                <option value="">-- Tanpa Wali Kelas / Kosong --</option>

                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            <div
                                class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 group-hover:text-indigo-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 active:scale-95 transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3.5 bg-indigo-600 text-white rounded-2xl font-bold text-sm hover:bg-indigo-700 shadow-xl shadow-indigo-200 active:scale-95 transition-all duration-200">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showImportModal"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

            <div @click.away="showImportModal = false" x-show="showImportModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8"
                class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden border border-white/50">

                <div class="px-8 pt-8 pb-4">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Import Data Kelas</h3>
                    <p class="text-sm text-slate-400 mt-1">Unggah file Excel (.xlsx) untuk menambah data massal.</p>
                </div>

                <form action="{{ route('classrooms.imports') }}" method="POST" enctype="multipart/form-data"
                    class="px-8 pb-10 space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Pilih File
                            Excel</label>
                        <div
                            class="relative group border-2 border-dashed border-slate-200 rounded-[2rem] p-8 transition hover:border-emerald-400 bg-slate-50/50 text-center">
                            <input type="file" name="file" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                            <div class="space-y-3">
                                <div
                                    class="mx-auto w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <div class="text-sm text-slate-600">
                                    <span class="font-bold text-emerald-600">Klik untuk upload</span> atau drag & drop
                                    <p class="text-[11px] text-slate-400 mt-1">Format: .xlsx, .xls (Maks. 5MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-2xl p-4 flex gap-3 border border-amber-100">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-[11px] text-amber-700 leading-relaxed">
                            Gunakan format template yang sesuai agar sistem dapat membaca data dengan benar.
                            <a href="#" class="font-bold underline decoration-amber-300">Download Template</a>
                        </p>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <button type="button" @click="showImportModal = false"
                            class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 active:scale-95 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3.5 bg-emerald-600 text-white rounded-2xl font-bold text-sm hover:bg-emerald-700 shadow-xl shadow-emerald-100 active:scale-95 transition-all">
                            Proses Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Fungsi Global untuk Konfirmasi Hapus
            window.confirmDelete = function(id) {
                Swal.fire({
                    title: 'Hapus Data Kelas?',
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
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                })
            }

            // 2. Konfigurasi Dasar Toast (Compact Style)
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

            // --- NOTIFIKASI SUKSES (HIJAU) ---
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

            // --- NOTIFIKASI HAPUS/DELETED (MERAH) ---
            @if (session('deleted'))
                Toast.fire({
                    icon: 'error',
                    iconColor: '#f43f5e',
                    title: "{{ session('deleted') }}",
                    customClass: {
                        popup: 'rounded-2xl bg-white/95 backdrop-blur-sm border border-rose-50 shadow-xl p-2 pl-4 pr-8 w-auto min-w-[280px] relative',
                        title: 'text-slate-600 font-semibold text-xs ml-1',
                        timerProgressBar: 'bg-rose-500 h-[1.5px]',
                        closeButton: 'absolute top-2 right-2 text-slate-300 hover:text-rose-500 transition-colors focus:shadow-none border-none text-lg p-0'
                    }
                });
            @endif

            // --- NOTIFIKASI ERROR VALIDASI/GANDA (MERAH) ---
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
    </script>
@endsection
