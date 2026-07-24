@extends('layouts.app')

@section('content')
    <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <nav class="flex mb-3 -mt-6 text-sm font-medium" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="#" class="text-slate-400 hover:text-slate-600 transition">Main</a></li>
                <li class="text-slate-300">/</li>
                <li><span class="text-slate-800">Dashboard Wali Kelas</span></li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1
                    class="text-2xl font-bold text-slate-900 text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-indigo-600">
                    Selamat Datang, {{ Auth::user()->name }}
                </h1>
                <p class="text-slate-500 text-sm sm:text-[16px] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Pantau data siswa dan status E-KARTU di kelas Anda hari ini.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div
                    class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-4 font-bold uppercase text-[10px]">
                    STD
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-500 line-clamp-1">Siswa di Kelas</p>
                <p class="text-lg sm:text-2xl font-bold text-slate-900">{{ $totalStudentsInClass }} <span
                        class="text-xs font-normal text-slate-400">Anak</span></p>
            </div>

            <div x-data @click="$dispatch('open-modal-aktif')"
                class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition cursor-pointer group">
                <div class="flex justify-between items-start">
                    <div
                        class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-4 font-bold uppercase text-[10px]">
                        S-ACT
                    </div>
                    <span class="text-[10px] text-indigo-500 font-bold opacity-0 group-hover:opacity-100 transition">Lihat
                        Detail →</span>
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-500 line-clamp-1">Kartu Aktif</p>
                <p class="text-lg sm:text-2xl font-bold text-slate-900">{{ $activeCardsInClass }} <span
                        class="text-xs font-normal text-slate-400">Kartu</span></p>
            </div>

            <div x-data @click="$dispatch('open-modal-belum')"
                class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition cursor-pointer group">
                <div class="flex justify-between items-start">
                    <div
                        class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center mb-4 font-bold uppercase text-[10px]">
                        B-ACT
                    </div>
                    <span class="text-[10px] text-indigo-500 font-bold opacity-0 group-hover:opacity-100 transition">Lihat
                        Detail →</span>
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-500 line-clamp-1">Belum Buat Kartu</p>
                <p class="text-lg sm:text-2xl font-bold text-slate-900">{{ $uncreatedCardsCount }} <span
                        class="text-xs font-normal text-slate-400">Siswa</span></p>
            </div>

            <div x-data @click="$dispatch('open-modal-expired')"
                class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition cursor-pointer group">
                <div class="flex justify-between items-start">
                    <div
                        class="w-10 h-10 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center mb-4 font-bold uppercase text-[10px]">
                        EXP
                    </div>
                    <span
                        class="bg-indigo-50 text-indigo-600 text-[9px] font-bold px-2 py-1 rounded-md border border-indigo-100 uppercase">
                        {{ Auth::user()->classroom->code_classroom ?? '-' }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm font-medium text-slate-500 line-clamp-1">Kartu Kadaluarsa</p>
                <p class="text-lg sm:text-2xl font-bold text-slate-900">
                    {{ $expiredCardsInClass }} <span class="text-xs font-normal text-slate-400">Siswa</span>
                </p>
            </div>
        </div>

        <div x-data="{ open: false }" @open-modal-aktif.window="open = true" x-show="open"
            class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>

                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl z-50 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Daftar Kartu Aktif</h3>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>

                    <div class="max-h-[500px] overflow-y-auto p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($allStudents->where('card.status', 'active') as $s)
                                <div
                                    class="flex items-center gap-3 p-3 bg-slate-50/50 hover:bg-slate-100 rounded-xl border border-slate-100 transition group">
                                    <img src="{{ $s->card && $s->card->foto ? asset('storage/' . $s->card->foto) : asset('images/default-avatar.png') }}"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm shrink-0">

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-slate-700 truncate capitalize">{{ $s->name }}
                                        </p>

                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span
                                                class="flex items-center gap-1 text-[8px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded-md font-black uppercase tracking-tighter shadow-sm">
                                                <span class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                                                Aktif
                                            </span>

                                            <p class="text-[9px] text-slate-400 font-medium tracking-tight">
                                                s/d {{ \Carbon\Carbon::parse($s->card->exp_date)->format('d/m/y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="shrink-0 opacity-20 group-hover:opacity-100 transition">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 text-center">
                        <p class="text-[10px] text-slate-400 font-medium uppercase">Total
                            {{ $allStudents->where('card.status', 'active')->count() }} Siswa Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" @open-modal-belum.window="open = true" x-show="open"
            class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>

                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl z-50 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-slate-800 text-amber-600">Siswa Belum Ada Kartu</h3>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Silakan lengkapi data
                                siswa berikut</p>
                        </div>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>

                    <div class="max-h-[500px] overflow-y-auto p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($allStudents->where('card', null) as $s)
                                <div
                                    class="flex items-center justify-between p-3 bg-slate-50/50 hover:bg-slate-50 rounded-xl border border-slate-100 transition group">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div
                                            class="w-9 h-9 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ substr($s->name, 0, 1) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-700 truncate">{{ $s->name }}</p>
                                            <p class="text-[10px] text-rose-400 font-medium tracking-tight italic">
                                                Belum mengajukan buat kartu
                                            </p>
                                        </div>
                                    </div>

                                    <span
                                        class="shrink-0 ml-2 text-[8px] bg-slate-100 text-slate-400 px-2 py-1 rounded-md font-black border border-slate-200 uppercase tracking-tighter">
                                        Cek Admin
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 text-center border-t border-slate-100">
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                            Total: {{ $allStudents->where('card', null)->count() }} Siswa Perlu Diproses
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" @open-modal-expired.window="open = true" x-show="open"
            class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>

                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl z-50 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-slate-800 text-rose-600 uppercase tracking-tight">Daftar Kartu
                                Kadaluarsa</h3>
                            <p class="text-[10px] text-slate-400 font-medium uppercase">Masa berlaku kartu telah habis</p>
                        </div>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>

                    <div class="max-h-[500px] overflow-y-auto p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($allStudents->where('card.status', '!=', 'active')->whereNotNull('card') as $s)
                                <div
                                    class="flex items-center gap-3 p-3 bg-rose-50/30 hover:bg-rose-50 rounded-xl border border-rose-100/50 transition">
                                    <img src="{{ $s->card && $s->card->foto ? asset('storage/' . $s->card->foto) : asset('images/default-avatar.png') }}"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm grayscale">

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-slate-700 truncate capitalize">
                                            {{ $s->name }}</p>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[8px] bg-rose-100 text-rose-600 px-1.5 py-0.5 rounded font-black uppercase">Expired</span>
                                            <p class="text-[9px] text-rose-400 font-medium italic">
                                                Habis: {{ \Carbon\Carbon::parse($s->card->exp_date)->format('d/m/y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 text-center border-t border-slate-100">
                        <p class="text-[10px] text-slate-500 font-bold uppercase">
                            Total {{ $expiredCardsInClass }} Kartu Perlu Perpanjangan
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Monitoring Pembuatan Kartu</h3>
                <a href="{{ url('card-students') }}"
                    class="text-xs text-indigo-600 font-bold hover:text-indigo-800 transition">
                    Kelola Kartu
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                Siswa</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">
                                Status Kepemilikan</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                DiUpdate</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                Masa Berlaku</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                Status Kartu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($studentsInClass as $student)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $student->card->foto ? asset('storage/' . $student->card->foto) : asset('images/default-avatar.png') }}"
                                            class="w-8 h-8 rounded-full object-cover border border-slate-200">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-slate-700">{{ $student->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">NISN:
                                                {{ $student->nisn }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-wide">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Tersedia
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-600 font-medium">
                                        {{ \Carbon\Carbon::parse($student->card->updated_at)->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-600 font-medium">
                                        {{ \Carbon\Carbon::parse($student->card->exp_date)->format('d M Y') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($student->card->status === 'active')
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
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-sm italic">
                                    Belum ada kartu yang diterbitkan untuk kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                    Total Siswa: <span class="text-indigo-600">{{ $studentsInClass->total() }}</span> Anak
                </div>
                {{ $studentsInClass->links('vendor.pagination.custom') }}
            </div>
        </div>
    </main>
@endsection
