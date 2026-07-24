@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        showModal: false,
        showProfile: false,
        editMode: false,
        search: '{{ request('search') }}',
        formData: {
            id: '',
            name: '',
            nisn: '',
            nipd: '',
            qrcode: '',
            classrooms_id: '',
            classroom_name: '',
            jenis_kelamin: '',
            agama: '',
            status: '',
            tempat_lahir: '',
            tanggal_lahir: ''
        }
    }">
        <nav class="flex mb-2 -mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="/wali-kelas/dashboard" class="hover:text-indigo-600 transition">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-indigo-600 font-bold">Siswa Kelas Saya</span>
                </li>
            </ol>
        </nav>

        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Data Siswa</h2>
                <p class="text-slate-500 text-sm italic">Daftar peserta didik perwalian Anda di SMKN 1 Wonosari.</p>
            </div>
            {{-- Keterangan Kelas --}}
            <div class="text-right hidden md:block">
                <span
                    class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-2xl border border-indigo-100 text-xs font-bold uppercase tracking-widest">
                    Kelas: {{ auth()->user()->classroom->classroom ?? 'N/A' }}
                    {{ auth()->user()->classroom->code_classroom ?? 'N/A' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-3 md:p-5 border-b border-slate-50 flex flex-row items-center gap-2 md:gap-4 bg-slate-50/30">

                {{-- Kiri: Per Page (Lebar tetap sesuai isi) --}}
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden sm:block">Show</span>
                    <div class="relative">
                        <select
                            onchange="window.location.href = '?perPage=' + this.value + '&search={{ request('search') }}&status={{ request('status') }}'"
                            class="pl-3 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-[13px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer shadow-sm">
                            @foreach ([5, 10, 25, 50] as $val)
                                <option value="{{ $val }}" {{ request('perPage', 5) == $val ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                        {{-- Icon Panah Dropdown --}}
                        <div class="absolute inset-y-0 right-2.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex-grow flex justify-end">
                    <form action="{{ route('wali-kelas.students') }}" method="GET" class="relative w-full md:max-w-72"
                        x-data="{ searchQuery: '{{ request('search') }}' }">
                        <input type="hidden" name="perPage" value="{{ request('perPage', 5) }}">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" x-model="searchQuery" @input.debounce.500ms="$el.form.submit()"
                            placeholder="Cari nama atau NISN..."
                            class="w-full pl-9 pr-9 py-2.5 bg-white border border-slate-200 rounded-xl text-xs md:text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all shadow-sm">
                        <template x-if="searchQuery.length > 0">
                            <button type="button"
                                @click="searchQuery = ''; window.location.href='{{ route('wali-kelas.students') }}?perPage={{ request('perPage', 5) }}'"
                                class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </template>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase text-slate-400">No</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase text-slate-400">NIPD/NISN</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase text-slate-400">Nama Lengkap</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase text-slate-400">L/P</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase text-slate-400">TTL</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase text-slate-400">Status</th>
                            <th class="px-6 py-4 text-center text-[11px] font-bold uppercase text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($students as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-slate-400 font-medium">
                                    {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-mono text-xs font-bold text-indigo-600">{{ $item->nisn }}</span>
                                        <span class="text-[10px] text-slate-400">NIPD: {{ $item->nipd ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $item->name }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item->jenis_kelamin }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-slate-500">{{ $item->tempat_lahir }},</span>
                                        <span
                                            class="text-slate-500">{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase {{ $item->status == 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            @click="formData = { 
                                                id: '{{ $item->id }}',
                                                name: '{{ $item->name }}', 
                                                nisn: '{{ $item->nisn }}', 
                                                nipd: '{{ $item->nipd }}',
                                                qrcode: '{{ $item->qrcode }}',
                                                classroom_name: '{{ $item->classroom->name_classroom ?? '-' }}',
                                                jenis_kelamin: '{{ $item->jenis_kelamin }}',
                                                tempat_lahir: '{{ $item->tempat_lahir }}',
                                                tanggal_lahir: '{{ $item->tanggal_lahir }}',
                                                agama: '{{ $item->agama }}',
                                                status: '{{ $item->status }}'
                                            }; showProfile = true"
                                            {{-- PANGGIL showProfile --}} title="Lihat Profil"
                                            class="p-2 text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-600 hover:text-white border border-indigo-100 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        @can('EDIT_STUDENTS')
                                        <button type="button"
                                            @click="
                                                editMode = true;
                                                formData = { 
                                                    id: '{{ $item->id }}',
                                                    name: '{{ $item->name }}', 
                                                    nisn: '{{ $item->nisn }}', 
                                                    nipd: '{{ $item->nipd }}',
                                                    qrcode: '{{ $item->qrcode }}',
                                                    classrooms_id: '{{ $item->classrooms_id }}',
                                                    jenis_kelamin: '{{ $item->jenis_kelamin }}',
                                                    agama: '{{ $item->agama }}',
                                                    status: '{{ $item->status }}',
                                                    tempat_lahir: '{{ $item->tempat_lahir }}',
                                                    tanggal_lahir: '{{ $item->tanggal_lahir }}'
                                                }; 
                                                showModal = true;
                                            "
                                            class="p-2 text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-600 hover:text-white border border-amber-100 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Data siswa tidak
                                    ditemukan di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                    Total Siswa: <span class="text-indigo-600">{{ $students->total() }}</span> Orang
                </div>
                {{ $students->links('vendor.pagination.custom') }}
            </div>
        </div>
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

                    <form :action="editMode ? '/wali-kelas/students/' + formData.id : '{{ route('wali-kelas.students') }}'"
                        method="POST" id="studentForm" class="space-y-0">
                        @csrf
                        <template x-if="editMode">@method('PUT')</template>

                        <div class="space-y-5">
                            <div class="flex items-center gap-2 mb-2">
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

        {{-- Modal Detail Siswa --}}
        <div x-show="showProfile" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center">

                {{-- Overlay --}}
                <div x-show="showProfile" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showModal = false"
                    class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm">
                </div>

                {{-- Modal Content --}}
                <div x-show="showProfile" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                    class="relative inline-block w-full max-w-2xl p-6 md:p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl">

                    {{-- Header --}}
                    <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-4">
                            {{-- Slot untuk QR Code (Jika ada data qrcode) --}}
                            {{-- <template x-if="formData.qrcode">
                                <div
                                    class="p-3 bg-indigo-50 rounded-2xl border border-indigo-100 flex flex-col items-center justify-center">
                                    <label class="block text-[10px] font-bold text-indigo-400 uppercase mb-1">Kode QR / ID
                                        Kartu</label>
                                    <p class="text-sm font-mono font-bold text-indigo-700" x-text="formData.qrcode"></p>
                                </div>
                            </template> --}}
                            <div>
                                <h3 class="text-xl font-bold text-slate-900" x-text="formData.name"></h3>
                                <p class="text-xs text-slate-400 font-mono" x-text="formData.id"></p>
                            </div>
                        </div>
                        <button @click="showProfile = false"
                            class="text-slate-400 hover:text-rose-500 p-1 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Body Data --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Identitas Utama --}}
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">NISN / NIPD</label>
                            <p class="text-sm font-semibold text-slate-700">
                                <span x-text="formData.nisn"></span> / <span x-text="formData.nipd ?? '-'"></span>
                            </p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Jenis Kelamin</label>
                            <p class="text-sm font-semibold text-slate-700" x-text="formData.jenis_kelamin"></p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Agama</label>
                            <p class="text-sm font-semibold text-slate-700" x-text="formData.agama"></p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Tempat, Tanggal
                                Lahir</label>
                            <p class="text-sm font-semibold text-slate-700">
                                <span x-text="formData.tempat_lahir"></span>, <span
                                    x-text="formData.tanggal_lahir"></span>
                            </p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Kelas</label>
                            <p class="text-sm font-semibold text-slate-700" x-text="formData.classroom_name"></p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Status Siswa</label>
                            <div class="mt-1">
                                <template x-if="formData.status === 'active'">
                                    <span
                                        class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                </template>
                                <template x-if="formData.status === 'non-active'">
                                    <span
                                        class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">Non-Aktif</span>
                                </template>
                                <template x-if="formData.status === 'lulus'">
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">Lulus</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Action --}}
                    <div class="mt-8 flex gap-3">
                        <button @click="showProfile = false"
                            class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Konfigurasi Dasar Toast (Compact Style)
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

            // --- NOTIFIKASI SUKSES (Tambah/Update) ---
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

            // --- NOTIFIKASI ERROR VALIDASI (Gagal Update/Input) ---
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
