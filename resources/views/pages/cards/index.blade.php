@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        showModal: false,
        editMode: false,
        photoPreview: null,
        search: '',
        studentSearch: '',
        isDropdownOpen: false,
        formData: {
            id: '',
            student_id: '',
            school_id: '{{ auth()->user()?->school_id }}',
            background_id: '',
            exp_date: '',
            status: 'active'
        },
    
        // Set Expired Date Otomatis
        setExpDate(kelas) {
            if (this.editMode) return;
            const now = new Date();
            const currentYear = now.getFullYear();
            let targetYear = currentYear;
            if (kelas == '10' || kelas == 'X') targetYear = currentYear + 2;
            else if (kelas == '11' || kelas == 'XI') targetYear = currentYear + 1;
            else if (kelas == '12' || kelas == 'XII') targetYear = currentYear;
            this.formData.exp_date = `${targetYear}-12-31`;
        },
    
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) this.photoPreview = URL.createObjectURL(file);
        },
    
        resetForm() {
            this.photoPreview = null;
            this.studentSearch = '';
            this.isDropdownOpen = false;
            if (!this.editMode) {
                this.formData.student_id = '';
                this.formData.exp_date = '';
                const fileInput = document.querySelector('input[type=file]');
                if (fileInput) fileInput.value = '';
            }
        }
    }">

        {{-- ... Bagian Breadcrumb dan Header tetap sama ... --}}
        <nav class="flex mb-2 -mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="#" class="hover:text-indigo-600 transition">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-slate-900">Card</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                    <span class="text-indigo-600 font-bold">Card Students</span>
                </li>
            </ol>
        </nav>

        <div class="mb-2">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Kartu</h2>
            <p class="text-slate-500 text-sm italic">Penerbitan dan aktivasi kartu identitas siswa SMKN 1 Wonosari.</p>
        </div>
        <div class="mb-2 mt-3">
            <div class="flex overflow-x-auto pb-2 sm:pb-0 no-scrollbar">
                <div
                    class="flex items-center gap-2 p-1 bg-slate-100 rounded-xl shadow-sm border border-slate-200 min-w-max">

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}"
                        class="px-4 py-2 text-sm font-normal rounded-lg transition-all flex items-center gap-2 {{ request('status', 'active') == 'active' ? 'bg-white text-green-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                        <span class="whitespace-nowrap">Kartu Aktif</span>
                        <span
                            class="px-2 py-0.5 text-[10px] bg-green-100 text-green-700 rounded-full border border-green-200">
                            {{ $countActive }}
                        </span>
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'expired']) }}"
                        class="px-4 py-2 text-sm font-normal rounded-lg transition-all flex items-center gap-2 {{ request('status') == 'expired' ? 'bg-white text-red-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                        <span class="whitespace-nowrap">Kartu Expired</span>
                        <span class="px-2 py-0.5 text-[10px] bg-red-100 text-red-700 rounded-full border border-red-200">
                            {{ $countExpired }}
                        </span>
                    </a>

                </div>
            </div>
        </div>
        {{-- Tabel Utama --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

            <div class="p-4 md:p-5 border-b border-slate-100 bg-slate-50/30">
                <div class="flex flex-col xl:flex-row items-stretch xl:items-center gap-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:flex items-center gap-3 flex-grow">
                        {{-- Per Page Selector --}}
                        <div class="relative col-span-1 xl:w-22">
                            <select onchange="window.location.href = updateQueryString('perPage', this.value)"
                                class="w-full pl-3 pr-8 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-[13px] shadow-sm appearance-none cursor-pointer text-slate-600 font-bold hover:border-indigo-300">
                                @foreach ([5, 10, 20, 30, 50] as $val)
                                    <option value="{{ $val }}" {{ request('perPage') == $val ? 'selected' : '' }}>
                                        {{ $val }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-3.5 pointer-events-none text-slate-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        {{-- Filter Kelas --}}
                        <div class="relative col-span-1 xl:w-28">
                            <select onchange="window.location.href = updateQueryString('kelas', this.value)"
                                class="w-full pl-4 pr-9 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-[13px] shadow-sm appearance-none cursor-pointer text-slate-700 font-bold hover:border-indigo-300">
                                <option value="">Semua</option>
                                @foreach (['X', 'XI', 'XII'] as $k)
                                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                                        Kelas {{ $k }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-3.5 pointer-events-none text-slate-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        {{-- Filter Jurusan --}}
                        <div class="relative col-span-2 md:col-span-1 xl:w-34">
                            <select onchange="window.location.href = updateQueryString('jurusan', this.value)"
                                class="w-full pl-4 pr-9 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-[13px] shadow-sm appearance-none cursor-pointer text-slate-700 font-bold hover:border-indigo-300">
                                <option value="">Semua</option>
                                @foreach ($classrooms as $cls)
                                    <option value="{{ $cls->code_classroom }}"
                                        {{ request('jurusan') == $cls->code_classroom ? 'selected' : '' }}>
                                        {{ $cls->code_classroom }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-3.5 pointer-events-none text-slate-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        {{-- Search Input --}}
                        <div class="relative col-span-2 md:col-span-1 xl:flex-1 xl:max-w-xs" x-data="{ search: '{{ request('search') }}' }">
                            <form action="{{ url('card-students') }}" method="GET" x-ref="searchForm">
                                <input type="hidden" name="status" value="{{ request('status', 'active') }}">
                                <input type="hidden" name="kelas" value="{{ request('kelas') }}">

                                <input type="text" name="search" x-model="search" {{-- Debounce 500ms: otomatis submit setelah berhenti mengetik --}}
                                    @input.debounce.500ms="$refs.searchForm.submit()"
                                    @keydown.enter.prevent="$refs.searchForm.submit()" placeholder="Cari nama..."
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm shadow-sm">

                                <div class="absolute left-3.5 top-3.5 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </form>
                        </div>

                        {{-- Reset Button --}}
                        @if (request()->anyFilled(['perPage', 'kelas', 'jurusan', 'status', 'search']))
                            <div class="col-span-2 md:col-auto">
                                <a href="{{ url()->current() }}"
                                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-2xl hover:bg-rose-100 transition text-[13px] font-bold shadow-sm whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 xl:ml-4">

                        @if (request()->filled('kelas') && request()->filled('jurusan'))
                            <a href="{{ route('card-students.download-bulk', ['kelas' => request('kelas'), 'jurusan' => request('jurusan')]) }}"
                                target="_blank"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 transition font-bold text-sm shadow-lg shadow-emerald-100 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh Semua
                            </a>
                        @endif

                        {{-- Button Terbitkan (Selalu Muncul) --}}
                        <button @click="editMode = false; showModal = true; resetForm()"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition font-bold text-sm shadow-lg shadow-indigo-100 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Terbitkan Kartu
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 w-10">No
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Siswa</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Kelas</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Template
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Create
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Expired
                            </th>
                            <th
                                class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Status</th>
                            <th
                                class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($cards as $item)
                            <tr x-show="@js(strtolower($item->student->name)).includes(search.toLowerCase())"
                                class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-slate-400 font-medium">
                                    {{ ($cards->currentPage() - 1) * $cards->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-bold text-slate-700">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center border border-slate-200">
                                            @if ($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <span
                                                    class="text-indigo-600 text-[10px] font-extrabold uppercase">{{ substr($item->student->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span>{{ $item->student->name }}</span>
                                            <span class="text-[9px] text-slate-400 font-normal ">UUID:
                                                {{ substr($item->id, 0, 8) }}...</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $tingkat = $item->student->classroom->classroom;
                                        $colorClass = match ($tingkat) {
                                            'X' => 'text-emerald-600 bg-emerald-50/50 border-emerald-100',
                                            'XI' => 'text-amber-600 bg-amber-50/50 border-amber-100',
                                            'XII' => 'text-rose-600 bg-rose-50/50 border-rose-100',
                                            default => 'text-indigo-600 bg-indigo-50/50 border-indigo-100',
                                        };
                                    @endphp
                                    <span
                                        class="font-mono text-xs font-bold px-2 py-1 rounded-lg border {{ $colorClass }}">
                                        {{ $item->student->classroom->classroom }}-{{ $item->student->classroom->code_classroom }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50/50 px-2 py-1 rounded-lg border border-indigo-100">
                                        {{ $item->background->name ?? 'Default' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">
                                    {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">
                                    {{ $item->exp_date ? \Carbon\Carbon::parse($item->exp_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        // Cek apakah tanggal sekarang sudah melewati exp_date
                                        $isExpired =
                                            $item->exp_date && \Carbon\Carbon::now()->greaterThan($item->exp_date);
                                        $currentStatus = $isExpired ? 'expired' : $item->status;
                                    @endphp

                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                                        {{ $currentStatus == 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                        {{ $currentStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('card-students.downloadPDF', $item->id) }}"
                                            title="Download Kartu" target="_blank"
                                            class="flex items-center gap-1.5 px-2 py-2 text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-100 transition border border-amber-200 font-bold text-[11px] shadow-sm active:scale-95">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                        <button
                                            @click="
                                            editMode = true; 
                                            formData = { 
                                                id: '{{ $item->id }}', 
                                                student_id: '{{ $item->student_id }}', 
                                                school_id: '{{ $item->school_id }}', 
                                                background_id: '{{ $item->background_id }}', 
                                                exp_date: '{{ $item->exp_date }}', 
                                                status: '{{ $item->status }}' 
                                            }; 
                                            studentSearch = `{{ $item->student->name }}`;
                                            photoPreview = '{{ $item->foto ? asset('storage/' . $item->foto) : null }}';
                                            showModal = true;
                                            "
                                            class="flex items-center gap-1 px-2 py-1.5 text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-100 transition border border-amber-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button type="button" onclick="confirmDelete('{{ $item->id }}')"
                                            class="flex items-center gap-1 px-2 py-1.5 text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition border border-rose-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ url('card-students', $item->id) . '?' . http_build_query(request()->query()) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Belum ada kartu
                                    diterbitkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div
                class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                    Showing <span class="text-indigo-600">{{ $cards->firstItem() ?? 0 }}</span> to <span
                        class="text-indigo-600">{{ $cards->lastItem() ?? 0 }}</span> of <span
                        class="text-slate-700">{{ $cards->total() }}</span> Entries
                </div>
                {{ $cards->links('vendor.pagination.custom') }}
            </div>
        </div>

        {{-- Modal Form --}}
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>

            <div @click.away="showModal = false"
                class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden border border-white/50">
                <div class="px-8 pt-8 pb-4 relative">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight"
                        x-text="editMode ? 'Perbarui Kartu' : 'Terbitkan Kartu Baru'"></h3>
                    <p class="text-sm text-slate-400 mt-1"
                        x-text="editMode ? 'Sesuaikan masa berlaku atau status kartu.' : 'Pilih siswa untuk membuat kartu identitas baru.'">
                    </p>
                    <button @click="showModal = false"
                        class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="editMode ? '/card-students/' + formData.id : '{{ url('card-students') }}'" method="POST"
                    enctype="multipart/form-data" class="px-8 pb-10 space-y-4">
                    @csrf
                    <template x-if="editMode">@method('PUT')</template>

                    <input type="hidden" name="school_id" x-model="formData.school_id">

                    {{-- Search Siswa --}}
                    <div class="space-y-1.5">
                        <label
                            class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Siswa</label>
                        <div class="relative group">
                            <input type="text" x-model="studentSearch" @click="isDropdownOpen = true; $el.select()"
                                @input="isDropdownOpen = true" :readonly="editMode" placeholder="Ketik nama siswa..."
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm shadow-sm pr-12"
                                required>

                            <button type="button" x-show="studentSearch.length > 0 && !editMode"
                                @click="studentSearch = ''; formData.student_id = ''; isDropdownOpen = false"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <input type="hidden" name="student_id" x-model="formData.student_id">

                            {{-- Dropdown Hasil Cari --}}
                            <div x-show="isDropdownOpen && !editMode && studentSearch.length > 0"
                                @click.away="isDropdownOpen = false"
                                class="absolute z-50 w-full mt-2 bg-white border border-slate-100 shadow-2xl rounded-2xl max-h-52 overflow-y-auto p-2"
                                x-transition>

                                @foreach ($students as $student)
                                @if($student->status === 'active')
                                    <div x-show="`{{ strtolower($student->name) }}`.includes(studentSearch.toLowerCase())"
                                        @click="
                                            formData.student_id = '{{ $student->id }}'; 
                                            studentSearch = `{{ $student->name }}`; 
                                            isDropdownOpen = false;
                                            {{-- Kita ambil kolom 'classroom' (misal: X/XI/XII) untuk hitung masa berlaku --}}
                                            setExpDate('{{ $student->classroom->classroom }}'); 
                                        "
                                        class="px-4 py-2.5 hover:bg-indigo-50 rounded-xl cursor-pointer text-sm transition flex justify-between items-center">

                                        <div class="flex flex-col">
                                            <span class="font-semibold text-slate-700">{{ $student->name }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $student->nisn }}</span>
                                        </div>

                                        {{-- Menampilkan nama lengkap kelas dari tabel classrooms --}}
                                        <span
                                            class="text-[10px] bg-slate-100 px-2 py-1 rounded-lg text-slate-500 font-medium">
                                            {{ $student->classroom->classroom }}-{{ $student->classroom->code_classroom }}
                                        </span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Foto
                            Profil</label>
                        <div class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-100 rounded-2xl">
                            <div
                                class="w-14 h-14 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                <template x-if="photoPreview"><img :src="photoPreview"
                                        class="w-full h-full object-cover"></template>
                                <template x-if="!photoPreview"><svg class="w-6 h-6 text-slate-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg></template>
                            </div>
                            <input type="file" name="foto" accept="image/*" @change="handleFileChange"
                                class="block w-full text-[11px] text-slate-500 border border-indigo-300 rounded-xl px-2 py-1.5
                                file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 
                                file:bg-indigo-600 file:text-white file:font-bold
                                hover:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition cursor-pointer bg-white shadow-sm">
                        </div>
                    </div>

                    {{-- Background --}}
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Template
                            Background</label>
                        <select name="background_id" x-model="formData.background_id"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none"
                            required>
                            <option value="">Default SMK</option>
                            @foreach ($backgrounds as $bg)
                                <option value="{{ $bg->id }}">{{ $bg->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Expired & Status --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Expired
                                Date</label>
                            <input type="date" name="exp_date" x-model="formData.exp_date"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Status</label>
                            <select name="status" x-model="formData.status"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm">
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm">Batal</button>
                        <button type="submit"
                            class="flex-1 px-6 py-3.5 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-xl shadow-indigo-200">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.updateQueryString = function(key, value) {
            const url = new URL(window.location.href);
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
            url.searchParams.delete('page'); // Reset ke hal 1 saat ganti filter
            return url.toString();
        };
        document.addEventListener('DOMContentLoaded', function() {
            window.confirmDelete = function(id) {
                Swal.fire({
                    title: 'Hapus Kartu?',
                    text: "Apakah Anda yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'bg-rose-600 text-white px-6 py-2.5 rounded-xl font-bold mx-2',
                        cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
                })
            }

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.style.zIndex = '10000';
                }
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}",
                    customClass: {
                        popup: 'rounded-2xl border border-emerald-50 shadow-xl'
                    }
                });
            @endif
        });
    </script>
@endsection
