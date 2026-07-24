@extends('layouts.app')
@section('content')
    <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <nav class="flex mb-3 -mt-6 text-sm font-medium" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="#" class="text-slate-400 hover:text-slate-600 transition">Main</a></li>
                <li class="text-slate-300">/</li>
                <li><span class="text-slate-800">Overview Dashboard</span></li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1
                    class="text-2xl font-bold text-slate-900 text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-indigo-600">
                    Selamat Datang, {{ Auth::user()->name }}
                </h1>
                <p class="text-slate-500 text-sm sm:text-[16px] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Pantau status pencetakan dan masa berlaku Kartu Pelajar hari ini.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div
                    class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-4 font-bold uppercase text-[10px]">
                    STD
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-500 line-clamp-1">Total Siswa</p>
                <p class="text-lg sm:text-2xl font-bold text-slate-900">{{ $totalStudents }} <span
                        class="text-xs font-normal text-slate-400">Anak</span></p>
            </div>

            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div
                    class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-4 font-bold uppercase text-[10px]">
                    CLS
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-500 line-clamp-1">Ruang Kelas</p>
                <p class="text-lg sm:text-2xl font-bold text-slate-900">{{ $totalClassrooms }} <span
                        class="text-xs font-normal text-slate-400">Kelas</span></p>
            </div>

            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div
                    class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center mb-4 font-bold uppercase text-[10px]">
                    USR
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-500 line-clamp-1">Staff & Guru</p>
                <p class="text-lg sm:text-2xl font-bold text-slate-900">{{ $totalUsers }} <span
                        class="text-xs font-normal text-slate-400">User</span></p>
            </div>

            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div
                        class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-4 font-bold uppercase text-[10px]">
                        TPL
                    </div>
                    <span
                        class="bg-emerald-50 text-emerald-600 text-[9px] font-bold px-2 py-1 rounded-md border border-emerald-100">
                        ACTIVE
                    </span>
                </div>

                <p class="text-xs sm:text-sm font-medium text-slate-500 line-clamp-1">Total Template Kartu</p>

                <p class="text-lg sm:text-2xl font-bold text-slate-900">
                    {{ $totalBackgrounds }} <span class="text-xs font-normal text-slate-400">Desain</span>
                </p>
            </div>
        </div>
        

        <div class="mt-8 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Riwayat Kartu Pelajar</h3>
                <a href="{{ url('card-students') }}"
                    class="text-xs text-indigo-600 font-bold hover:text-indigo-800 transition">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                Siswa
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                Kelas
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                Template
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                Masa Berlaku
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentCards as $card)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $card->foto ? asset('storage/' . $card->foto) : asset('images/default-avatar.png') }}"
                                            class="w-8 h-8 rounded-full object-cover border border-slate-200">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-semibold text-slate-700">{{ $card->student->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">NISN:
                                                {{ $card->student->nisn }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-slate-600">
                                            {{ $card->student->classroom->classroom }} -
                                            {{ $card->student->classroom->code_classroom }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            SMKN 1 Wonosari
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="inline-flex items-center text-xs font-medium text-slate-600">
                                            <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                            </svg>
                                            {{ $card->background->name ?? 'Default' }}
                                        </span>
                                        <span
                                            class="text-[10px] text-indigo-500 font-bold uppercase">{{ $card->school->nama_sekolah }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-600 font-medium">
                                        {{ $card->exp_date ? \Carbon\Carbon::parse($card->exp_date)->format('d F Y') : '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($card->status === 'active')
                                        <div
                                            class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Active
                                        </div>
                                    @else
                                        <div
                                            class="flex items-center gap-1.5 text-[10px] font-bold text-rose-500 uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Expired
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-sm italic">
                                    Belum ada riwayat pencetakan kartu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                    Showing <span class="text-indigo-600">{{ $recentCards->firstItem() ?? 0 }}</span> to <span
                        class="text-indigo-600">{{ $recentCards->lastItem() ?? 0 }}</span> of <span
                        class="text-slate-700">{{ $recentCards->total() }}</span> Entries
                </div>
                {{ $recentCards->links('vendor.pagination.custom') }}
            </div>
        </div>
    </main>
@endsection
