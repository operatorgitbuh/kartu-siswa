@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ search: '' }">
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
            <p class="text-slate-500 text-sm italic">Kelas: {{ $classroom->classroom }} - {{ $classroom->name_classroom }}
            </p>
        </div>
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">

                <div class="flex flex-row items-center gap-2 w-full md:w-auto">

                    <div class="flex items-center gap-1.5 shrink-0">
                        <span
                            class="text-[10px] font-bold text-slate-400 uppercase tracking-wider hidden sm:block">Show</span>
                        <div class="relative">
                            <select
                                onchange="window.location.href = '?perPage=' + this.value + '&search={{ request('search') }}&status={{ request('status', 'active') }}'"
                                class="pl-2.5 pr-8 py-2 bg-white border border-slate-200 rounded-xl text-[13px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer shadow-sm transition-all">
                                @foreach ([5, 10, 25, 50, 100] as $val)
                                    <option value="{{ $val }}"
                                        {{ request('perPage', 10) == $val ? 'selected' : '' }}>
                                        {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-2.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <div class="relative">
                            <select
                                onchange="window.location.href = '?status=' + this.value + '&search={{ request('search') }}&perPage={{ request('perPage', 10) }}'"
                                class="pl-3 pr-8 py-2 bg-white border border-slate-200 rounded-xl text-[13px] font-bold {{ request('status', 'active') == 'active' ? 'text-indigo-600' : 'text-rose-600' }} outline-none focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer shadow-sm transition-all">
                                <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>
                                    ACTIVE</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>EXPIRED
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-2.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex-grow">
                        <form action="{{ route('wali-kelas.card-students') }}" method="GET" class="relative w-full"
                            x-data="{ searchQuery: '{{ request('search') }}' }">
                            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                            <input type="hidden" name="status" value="{{ request('status', 'active') }}">

                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>

                            <input type="text" name="search" x-model="searchQuery"
                                @input.debounce.500ms="$el.form.submit()" placeholder="Cari..."
                                class="w-full pl-9 pr-9 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all shadow-sm">

                            <template x-if="searchQuery.length > 0">
                                <button type="button"
                                    @click="searchQuery = ''; window.location.href='{{ route('wali-kelas.card-students') }}?perPage={{ request('perPage', 10) }}&status={{ request('status', 'active') }}'"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </template>
                        </form>
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    @if ($cards->count() > 0)
                        <a href="{{ route('wali-kelas.card-students.download-bulk', ['kelas' => $classroom->level_classroom, 'jurusan' => $classroom->code_classroom]) }}"
                            target="_blank"
                            class="inline-flex items-center justify-center gap-2 w-full md:w-auto px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Cetak Semua</span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">L/P</th>
                            <th class="px-6 py-4">Dibuat</th>
                            <th class="px-6 py-4">Masa Berlaku</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($cards as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-slate-400">
                                    {{ ($cards->currentPage() - 1) * $cards->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-indigo-50 border border-slate-200 overflow-hidden">
                                            @if ($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-indigo-500 font-bold uppercase text-[10px]">
                                                    {{ substr($item->student->name, 0, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-700">{{ $item->student->name }}</span>
                                            <span class="text-[10px] text-slate-400">NISN:
                                                {{ $item->student->nisn }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-slate-600 font-medium">
                                            {{ $item->student->jenis_kelamin }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-slate-600 font-medium">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                        </span>

                                        <span class="text-[10px] text-slate-400 font-normal italic mt-0.5">
                                            by: {{ $item->user->name ?? 'System' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ \Carbon\Carbon::parse($item->exp_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Langsung ambil dari kolom status di database --}}
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                                            {{ $item->status == 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                                            {{ $item->status == 'expired' ? 'bg-rose-50 text-rose-600 border border-rose-100' : '' }}
                                            {{ $item->status == 'non-active' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('wali-kelas.card-students.WakelPDF', $item->id) }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg border border-amber-200 font-bold text-xs hover:bg-amber-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>

                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">Belum ada kartu
                                    yang
                                    terbit untuk kelas ini.</td>
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
    </div>
@endsection
