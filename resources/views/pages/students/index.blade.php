@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        showModal: false,
        showImportModal: false,
        editMode: false,
        showNaikKelasModal: false,
        search: '',
        selectedStudents: [],
        showBulkModal: false,
        selectAll: false,
        toggleAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                // Ambil semua ID siswa yang ada di tabel saat ini
                this.selectedStudents = Array.from(document.querySelectorAll('.student-checkbox')).map(cb => cb.value);
            } else {
                this.selectedStudents = [];
            }
        },
        formData: {
            id: '',
            name: '',
            nisn: '',
            nipd: '',
            qrcode: '',
            jenis_kelamin: '',
            classrooms_id: '',
            agama: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            status: 'active'
        }
    }">

    <!-- Breadcrumb -->
    <nav class="flex mb-2 -mt-4 md:-mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="/" class="hover:text-indigo-600 transition">Dashboard</a></li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                </svg>
                <span class="text-slate-900">Data Master</span>
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                </svg>
                <span class="text-indigo-600 font-bold">Students</span>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Siswa</h2>
        <p class="text-slate-500 text-sm italic">Otomasi pendataan peserta didik SMKN 1 Wonosari.</p>
    </div>

    <!-- Status Tabs -->
    <div class="relative mb-6">
        <div class="flex items-center w-full overflow-x-auto no-scrollbar custom-scrollbar-hide focus:outline-none">
            <div class="flex flex-nowrap md:flex-wrap items-center gap-2 bg-slate-100/60 p-1.5 rounded-[1.8rem] md:rounded-3xl border border-slate-200/50 min-w-max">
                <a href="{{ route('students.index', array_merge(request()->query(), ['status' => 'active', 'page' => 1])) }}"
                    class="px-5 py-2.5 md:py-2 rounded-[1.4rem] md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-wider transition-all duration-300 {{ request('status') == 'active' || !request('status') ? 'bg-white text-indigo-600 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:bg-slate-200/40' }}">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 {{ request('status') == 'active' || !request('status') ? 'block' : 'hidden' }}"></span>
                        Semua (Aktif)
                    </div>
                </a>
                <a href="{{ route('students.index', array_merge(request()->query(), ['status' => 'non-active', 'page' => 1])) }}"
                    class="px-6 py-2.5 md:py-2 rounded-[1.4rem] md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-wider transition-all duration-300 {{ request('status') == 'non-active' ? 'bg-white text-rose-600 shadow-sm border border-rose-100' : 'text-slate-500 hover:bg-slate-200/40' }}">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 {{ request('status') == 'non-active' ? 'block' : 'hidden' }}"></span>
                        Non-Aktif
                    </div>
                </a>
                <a href="{{ route('students.index', array_merge(request()->query(), ['status' => 'lulus', 'page' => 1])) }}"
                    class="px-6 py-2.5 md:py-2 rounded-[1.4rem] md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-wider transition-all duration-300 {{ request('status') == 'lulus' ? 'bg-white text-amber-600 shadow-sm border border-amber-100' : 'text-slate-500 hover:bg-slate-200/40' }}">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 {{ request('status') == 'lulus' ? 'block' : 'hidden' }}"></span>
                        Lulus
                    </div>
                </a>
                
            </div>
            <button 
            type="button" 
            x-cloak
            x-show="selectedStudents && selectedStudents.length > 0"
            x-transition
            @click="showBulkModal = true" 
            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
    </svg>
    <span>Mapping (<span x-text="selectedStudents.length"></span> Siswa)</span>
</button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Controls Header -->
        <div class="p-5 border-b border-slate-50 flex flex-col lg:flex-row justify-between items-center gap-4 bg-slate-50/30">
            <div class="flex flex-col md:flex-row flex-wrap items-center gap-3 w-full lg:w-auto">

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <!-- Per Page Filter -->
                    <select onchange="updateFilter('perPage', this.value)"
                        class="flex-1 md:flex-none pl-3 pr-2 py-2.5 bg-white border border-slate-200 rounded-2xl text-[13px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm cursor-pointer">
                        @foreach ([5, 10, 25, 50, 100] as $val)
                            <option value="{{ $val }}" {{ request('perPage') == $val ? 'selected' : '' }}>
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Classroom Filter -->
                    <select onchange="updateFilter('classroom_id', this.value)"
                        class="flex-[2] md:flex-none pl-3 pr-8 py-2.5 bg-white border border-slate-200 rounded-2xl text-[13px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm cursor-pointer">
                        <option value="">Semua Kelas</option>
                        @foreach ($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ request('classroom_id') == $cls->id ? 'selected' : '' }}>
                                {{ $cls->classroom }}-{{ $cls->code_classroom }}
                            </option>
                        @endforeach
                    </select>
                    
                </div>

                <!-- Search Input -->
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <form action="{{ route('students.index') }}" method="GET" x-ref="searchForm"
                        x-data="{
                            search: '{{ request('search') }}',
                            resetSearch() {
                                this.search = '';
                                $nextTick(() => { this.$refs.searchForm.submit(); });
                            }
                        }" class="relative flex-1 md:w-64">

                        <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                        <input type="hidden" name="classroom_id" value="{{ request('classroom_id') }}">

                        <input type="text" name="search" x-model="search" x-ref="searchInput"
                            @input.debounce.500ms="$refs.searchForm.submit()" placeholder="Cari nama/NISN..."
                            class="w-full pl-10 pr-10 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm shadow-sm transition-all">

                        <div class="absolute left-3.5 top-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <button type="button" x-show="search.length > 0" @click="resetSearch()"
                            class="absolute right-3 top-2.5 p-1 text-slate-400 hover:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>

                    @if (request('classroom_id'))
                        <button type="button" onclick="updateFilter('classroom_id', '')"
                            class="flex-shrink-0 group flex items-center justify-center w-10 h-10 bg-rose-50 text-rose-500 border border-rose-100 rounded-2xl hover:bg-rose-500 hover:text-white transition-all duration-300 shadow-sm"
                            title="Reset Filter Kelas">
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 w-full lg:w-auto">
                <!-- Tombol Naik Kelas (Akan menampilkan counter jika ada siswa terpilih) -->
                <button @click="showNaikKelasModal = true" type="button" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 p-2 sm:px-4 sm:py-2 text-sm font-medium text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="hidden sm:inline">Naik Kelas Masal</span>
                </button>

                <button @click="showImportModal = true"
                    class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl hover:bg-emerald-100 transition font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="hidden sm:inline">Import</span>
                    <span class="sm:hidden">Import</span>
                </button>

                <button
                    @click="editMode = false; formData = { id: '', name: '', nisn: '', nipd: '', jenis_kelamin: '', classrooms_id: '', agama: '', tempat_lahir: '', tanggal_lahir: '', status: 'active' }; showModal = true"
                    class="flex-[2] lg:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition font-bold text-sm shadow-lg shadow-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah <span class="hidden sm:inline">Siswa</span>
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-50/80">
                    <tr>
                        <!-- CHECKBOX SELECT ALL -->
                        <th class="px-4 py-4 text-center w-10">
                            <input type="checkbox" @click="toggleAll()" :checked="selectAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">No</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">NISN/NIPD</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Nama Siswa</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">L/P</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Kelas</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($students as $item)
                        <tr x-show="'{{ strtolower($item->name . $item->nisn) }}'.includes(search.toLowerCase())" class="hover:bg-slate-50/50 transition">
                            <!-- CHECKBOX PER SISWA -->
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" value="{{ $item->id }}" x-model="selectedStudents" class="student-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-medium" style="width: 5px;">
                                {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs font-bold text-indigo-600">{{ $item->nisn }}</span>
                                    <span class="text-[10px] text-slate-400">NIPD: {{ $item->nipd ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $item->jenis_kelamin }}</td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $item->classroom->classroom ?? 'N/A' }} - {{ $item->classroom->code_classroom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase {{ $item->status == 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button
                                        @click="editMode = true; formData = { 
                                            id: '{{ $item->id }}', 
                                            name: '{{ $item->name }}', 
                                            nisn: '{{ $item->nisn }}', 
                                            nipd: '{{ $item->nipd }}',
                                            qrcode: '{{ $item->qrcode }}',
                                            jenis_kelamin: '{{ $item->jenis_kelamin }}', 
                                            classrooms_id: '{{ $item->classrooms_id }}',
                                            agama: '{{ $item->agama }}',
                                            tempat_lahir: '{{ $item->tempat_lahir }}',
                                            tanggal_lahir: '{{ $item->tanggal_lahir }}',
                                            status: '{{ $item->status }}'
                                        }; showModal = true"
                                        class="flex items-center gap-1 px-3 py-1.5 text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-100 transition border border-amber-100 font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button" onclick="confirmDelete('{{ $item->id }}')"
                                        class="flex items-center gap-1 px-3 py-1.5 text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition border border-rose-100 font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('students.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">Data siswa tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                Showing <span class="text-indigo-600">{{ $students->firstItem() ?? 0 }}</span> to <span class="text-indigo-600">{{ $students->lastItem() ?? 0 }}</span> of <span class="text-slate-700">{{ $students->total() }}</span> Entries
            </div>
            {{ $students->links('vendor.pagination.custom') }}
        </div>
    </div>

        <div x-show="showNaikKelasModal" 
     x-cloak 
     class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm transition-opacity"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="flex min-h-screen items-center justify-center p-4 text-center">
        <!-- Main Card dengan State Alpine.js untuk Konfirmasi -->
        <div class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800"
             x-data="{ showConfirm: false }"
             @click.away="showNaikKelasModal = false; showConfirm = false">
            
            <!-- Header Modal -->
            <div class="flex items-center justify-between border-b pb-3 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Proses Kenaikan Kelas Serentak
                </h3>
                <button @click="showNaikKelasModal = false; showConfirm = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('students.naik-kelas') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                
                <!-- TAMPILAN 1: Ringkasan Informasi Kenaikan Kelas -->
                <div x-show="!showConfirm" class="space-y-4">
                    <div class="rounded-lg bg-blue-50 p-4 text-sm text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                        <p class="font-bold mb-1">🚀 Sistem Kenaikan Kelas Otomatis:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Semua siswa <b>Kelas XII</b> akan diubah statusnya menjadi <b>LULUS / GRADUATED</b>.</li>
                            <li>Semua siswa <b>Kelas XI</b> akan dipindahkan ke <b>Kelas XII</b> sesuai jurusan & kodenya.</li>
                            <li>Semua siswa <b>Kelas X</b> akan dipindahkan ke <b>Kelas XI</b> sesuai jurusan & kodenya.</li>
                        </ul>
                    </div>

                    <div class="rounded-lg bg-amber-50 p-3 text-xs text-amber-800 dark:bg-amber-900/30 dark:text-amber-200">
                        ⚠️ <b>Catatan:</b> "Untuk kelas X ATN dan X ATK Filter Kelas, Lalu Pilih dan centang siswa yang ingin dipindahkan lalu klik tombol Mapping di atas lalu pilih tujuan ke kelas XI ATPH dan XI ATPER dan kelas XI ATR dan XI ATU."
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t pt-4 dark:border-gray-700">
                        <button type="button" @click="showNaikKelasModal = false" class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button type="button" @click="showConfirm = true" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                            Lanjut ke Konfirmasi
                        </button>
                    </div>
                </div>

                <!-- TAMPILAN 2: Pop-up / Layar Konfirmasi Tailwind -->
                <div x-show="showConfirm" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="space-y-4 text-center py-2">
                    
                    <!-- Icon Peringatan Custom -->
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    <div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white">
                            Apakah Anda Yakin?
                        </h4>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Proses ini akan mengubah data kelas untuk <span class="font-semibold text-rose-600 dark:text-rose-400">SELURUH SISWA ACTIVE</span> secara sekaligus. Tindakan ini tidak dapat dibatalkan secara otomatis.
                        </p>
                    </div>

                    <div class="mt-6 flex justify-center gap-3 border-t pt-4 dark:border-gray-700">
                        <button type="button" @click="showConfirm = false" class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Kembali
                        </button>
                        <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-700 shadow-lg shadow-emerald-600/30">
                            Ya, Eksekusi Sekarang
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<div x-show="showBulkModal" 
     x-cloak 
     class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm transition-opacity"
     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    
    <div class="flex min-h-screen items-center justify-center p-4 text-center">
        <div class="w-full max-w-lg transform overflow-hidden rounded-3xl bg-white p-6 text-left align-middle shadow-2xl transition-all border border-slate-100"
             @click.away="showBulkModal = false">
            
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-4 border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Pindah Kelas Siswa Terpilih</h3>
                <button type="button" @click="showBulkModal = false" class="text-slate-400 hover:text-slate-500 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('students.naik-kelas') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                
                <!-- Send Type Flag -->
                <input type="hidden" name="action_type" value="bulk_selected">

                <!-- Loop ID Siswa yang Dicentang -->
                <template x-for="studentId in selectedStudents" :key="studentId">
                    <input type="hidden" name="student_ids[]" :value="studentId">
                </template>

                <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-2xl text-xs text-indigo-700 font-medium flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-indigo-600"></span>
                    <span>Memindahkan <strong x-text="selectedStudents.length"></strong> siswa yang terpilih dari tabel.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kelas Tujuan</label>
                    <select name="to_classroom_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-medium">
                        <option value="">Pilih Kelas Tujuan</option>
                        @foreach ($classrooms as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->classroom }}-{{ $cls->code_classroom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 text-xs text-amber-800 leading-relaxed">
                    <strong>Catatan:</strong> Siswa yang dicentang akan langsung dipindahkan ke kelas tujuan di atas.
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t pt-4 border-slate-100">
                    <button type="button" @click="showBulkModal = false" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 rounded-2xl font-bold text-sm transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 font-bold text-sm transition shadow-lg shadow-indigo-100">
                        Proses Pindah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


        {{-- Modal Create/Edit --}}
        <div x-show="showModal"
            class="fixed inset-0 z-[60] flex items-end md:items-center justify-center p-0 md:p-4 bg-slate-900/60 backdrop-blur-sm"
            x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="showModal = false" x-show="showModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-20 md:translate-y-0 md:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
                x-transition:leave-end="opacity-0 translate-y-20 md:translate-y-0 md:scale-95"
                class="bg-white rounded-t-[2rem] md:rounded-[2.5rem] shadow-2xl w-full max-w-2xl max-h-[92vh] flex flex-col overflow-hidden">

                <div
                    class="px-6 md:px-10 py-6 border-b border-slate-50 flex justify-between items-center bg-white/80 backdrop-blur-md sticky top-0 z-20">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight"
                            x-text="editMode ? 'Perbarui Data Siswa' : 'Tambah Siswa Baru'"></h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Lengkapi informasi detail siswa di bawah ini.
                        </p>
                    </div>
                    <button @click="showModal = false"
                        class="group p-2.5 bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-2xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-6 md:px-10 py-8 space-y-8 scroll-smooth"
                    style="scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent;">

                    <form :action="editMode ? '/students/' + formData.id : '{{ route('students.store') }}'" method="POST"
                        id="studentForm" class="space-y-0">
                        @csrf
                        <template x-if="editMode">@method('PUT')</template>

                        <div class="space-y-5">
                            <div class="flex items-center gap-2 mb-2 -mt-4">
                                <div class="w-1 h-4 bg-indigo-500 rounded-full"></div>
                                <span class="text-[11px] font-black uppercase tracking-widest text-slate-400">Identitas
                                    Utama</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">Nama Lengkap</label>
                                    <input type="text" name="name" x-model="formData.name" required
                                        placeholder="Nama lengkap sesuai ijazah"
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm transition-all placeholder:text-slate-300">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">NISN</label>
                                    <input type="text" name="nisn" x-model="formData.nisn" required
                                        placeholder="10 Digit nomor induk"
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm transition-all placeholder:text-slate-300">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="flex items-center gap-2 mb-2 mt-3">
                                <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                                <span class="text-[11px] font-black uppercase tracking-widest text-slate-400">Informasi
                                    Akademik</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">NIPD</label>
                                    <input type="text" name="nipd" x-model="formData.nipd"
                                        placeholder="Nomor Induk Peserta Didik"
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm transition-all placeholder:text-slate-300">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">Kelas & Jurusan</label>
                                    <div class="relative">
                                        <select name="classrooms_id" x-model="formData.classrooms_id" required
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm appearance-none transition-all">
                                            <option value="" disabled>Pilih kelas siswa</option>
                                            @foreach ($classrooms as $cls)
                                                <option value="{{ $cls->id }}">{{ $cls->classroom }} -
                                                    {{ $cls->code_classroom }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2 ">
                                <label class="block text-xs font-bold text-slate-700 ml-1">QRCODE</label>
                                <input type="text" name="qrcode" x-model="formData.qrcode" placeholder="qrcode....."
                                    class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm transition-all placeholder:text-slate-300">
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="flex items-center gap-2 mb-2 mt-3">
                                <div class="w-1 h-4 bg-amber-500 rounded-full"></div>
                                <span class="text-[11px] font-black uppercase tracking-widest text-slate-400">Data
                                    Personal</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="space-y-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" x-model="formData.jenis_kelamin" required
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none appearance-none focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 transition-all">
                                        <option value="" disabled>Pilih JK</option>
                                        @foreach (App\Models\Student::GENDER as $gender)
                                            <option value="{{ $gender }}">{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">Agama</label>
                                    <select name="agama" x-model="formData.agama" required
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none appearance-none focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 transition-all">
                                        <option value="" disabled>Pilih Agama</option>
                                        @foreach (App\Models\Student::AGAMA as $agama)
                                            <option value="{{ $agama }}">{{ $agama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">Status</label>
                                    <select name="status" x-model="formData.status" required
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none appearance-none focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 transition-all">
                                        @foreach (App\Models\Student::STATUS as $status)
                                            <option value="{{ $status }}">
                                                {{ ucwords(str_replace('-', ' ', $status)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" x-model="formData.tempat_lahir" required
                                        placeholder="Kota Kelahiran"
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm transition-all placeholder:text-slate-300">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 ml-1">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" x-model="formData.tanggal_lahir" required
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-[5px] focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm transition-all">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div
                    class="px-6 md:px-10 py-6 border-t border-slate-50 bg-slate-50/50 backdrop-blur-md flex flex-col-reverse md:flex-row gap-3 md:gap-4 sticky bottom-0 z-20">
                    <button type="button" @click="showModal = false"
                        class="px-8 py-4 bg-white text-slate-600 border border-slate-200 rounded-2xl font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all duration-300">
                        Batal
                    </button>
                    <button type="submit" form="studentForm"
                        class="flex-1 px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 active:translate-y-0 transition-all duration-300">
                        Simpan Data Siswa
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Import --}}
        <div x-show="showImportModal"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md" x-cloak
            {{-- Animasi Backdrop: Memudar halus --}} x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 backdrop-blur-none" x-transition:enter-end="opacity-100 backdrop-blur-md"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 backdrop-blur-md"
            x-transition:leave-end="opacity-0 backdrop-blur-none">

            <div @click.away="showImportModal = false" x-show="showImportModal" {{-- Animasi Modal: Zoom & Fade agar tidak kaku --}}
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden border border-white/50">

                <div class="px-8 pt-8 pb-4 flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Import Data Siswa</h3>
                        <p class="text-slate-500 text-[10px] mt-1 uppercase font-bold tracking-wider">Format: .xlsx atau
                            .csv</p>
                    </div>
                    <button @click="showImportModal = false"
                        class="p-2 bg-slate-50 text-slate-400 hover:text-rose-500 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('students.imports') }}" method="POST" enctype="multipart/form-data"
                    class="px-8 pb-10 space-y-6">
                    @csrf
                    {{-- Bagian Dropzone File --}}
                    <div
                        class="relative border-2 border-dashed border-slate-200 rounded-[2rem] p-8 text-center hover:border-indigo-400 hover:bg-indigo-50/30 transition-all group">
                        <input type="file" name="file" id="fileImport" class="hidden" required
                            onchange="updateFileName(this)">
                        <label for="fileImport" class="cursor-pointer block">
                            <div
                                class="bg-indigo-100/50 w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                <svg class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <span id="fileNameDisplay"
                                class="text-sm font-bold text-slate-600 group-hover:text-indigo-600 transition-colors">Klik
                                untuk pilih file</span>
                            <p class="text-[10px] text-slate-400 mt-1">Maksimal ukuran file 2MB</p>
                        </label>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="showImportModal = false"
                            class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit"
                            class="flex-1 px-6 py-3.5 bg-emerald-600 text-white rounded-2xl font-bold text-sm shadow-xl shadow-emerald-100 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all">Proses
                            Import</button>
                    </div>

                    <div class="text-center bg-slate-50 p-3 rounded-2xl border border-slate-100">
                        <a href="{{ asset('templates/template_siswa.xlsx') }}"
                            class="flex items-center justify-center gap-2 text-[11px] text-indigo-600 font-bold hover:text-indigo-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Template Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mengupdate nama file di modal import
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'Klik untuk pilih file';
            document.getElementById('fileNameDisplay').textContent = fileName;
        }

        document.addEventListener('DOMContentLoaded', function() {

            // 1. Fungsi Global untuk Konfirmasi Hapus
            window.confirmDelete = function(id) {
                Swal.fire({
                    title: 'Hapus Data Siswa?',
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

            // --- NOTIFIKASI SUKSES ---
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

            // --- NOTIFIKASI HAPUS/DELETED ---
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

            // --- NOTIFIKASI ERROR VALIDASI ---
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
    <script>
        function updateFilter(paramName, paramValue) {
            // Ambil URL saat ini
            const url = new URL(window.location.href);

            // Update atau Tambah parameter
            if (paramValue) {
                url.searchParams.set(paramName, paramValue);
            } else {
                url.searchParams.delete(paramName);
            }

            // Reset ke halaman 1 jika filter berubah agar tidak error pagination
            url.searchParams.delete('page');

            // Arahkan ke URL baru
            window.location.href = url.toString();
        }
    </script>
@endsection
