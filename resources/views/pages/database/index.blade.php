@extends('layouts.app')

@section('content')
    <main x-data="{
        openCreateModal: false,
        openRestoreModal: false,
        openUploadRestoreModal: false,
        selectedBackup: '',
        loading: false,

        triggerRestore(filename) {
            this.selectedBackup = filename;
            this.openRestoreModal = true;
        }
    }" x-cloak class="max-w-7xl mx-auto py-4 sm:py-6 px-3 sm:px-6 lg:px-8 flex-1 w-full">

        <!-- Breadcrumb -->
        <nav class="flex mb-4 -mt-3 items-center w-fit" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-1.5 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/"
                        class="text-[10px] sm:text-xs font-semibold text-slate-400 hover:text-indigo-600 flex items-center transition-colors">
                        <i class="fas fa-house text-[9px] mr-1.5 sm:mr-2"></i> Beranda
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-slate-300 text-[7px]"></i>
                    <span class="ml-1.5 sm:ml-3 text-[10px] sm:text-xs font-bold text-slate-900 tracking-wide uppercase">Backup / Restore</span>
                </li>
            </ol>
        </nav>

        <!-- Header Page -->
        <div class="mb-5 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Backup Database</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola dan amankan file cadangan database sistem sekolah.</p>
            </div>
        </div>

        <!-- Card Container Utama -->
        <div class="bg-white border border-slate-200 border-t-4 border-t-indigo-500 rounded-2xl overflow-hidden mb-6 shadow-sm">
            
            <!-- Toolbar Control (Per Page & Action Buttons) -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between gap-2 sm:gap-3">
    
    <!-- KIRI: Select Per Page -->
    <div class="flex items-center text-xs text-slate-500 font-semibold shrink-0">
        <form method="GET" action="{{ route('backup-restore-database.index') }}" class="inline-block">
            @foreach(request()->except(['per_page', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <select name="per_page" onchange="this.form.submit()" 
                class="bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg px-2.5 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all cursor-pointer shadow-sm">
                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>
    </div>

    <!-- KANAN: Tombol Action (Restore & Backup) -->
    <div class="flex items-center gap-2 sm:gap-3">
        <button @click="openUploadRestoreModal = true"
            class="bg-amber-500 hover:bg-amber-600 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-sm shadow-amber-200 flex items-center justify-center whitespace-nowrap">
            <i class="fas fa-upload mr-1.5"></i> Restore
        </button>

        <button @click="openCreateModal = true"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-sm shadow-indigo-200 flex items-center justify-center whitespace-nowrap">
            <i class="fas fa-database mr-1.5"></i> Backup
        </button>
    </div>

</div>

            <!-- 1. TAMPILAN MOBILE (< md): Card Mode -->
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($backups as $backup)
                    <div class="p-4 hover:bg-slate-50/50 transition-colors space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <i class="fas fa-file-code text-indigo-500 text-sm shrink-0"></i>
                                <span class="font-bold text-slate-700 text-xs truncate" title="{{ $backup['name'] }}">
                                    {{ $backup['name'] }}
                                </span>
                            </div>
                            <span class="text-[9px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200 shrink-0">
                                {{ $backup['size'] }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-500 font-medium">
                            <span><i class="far fa-clock mr-1 text-slate-400"></i> {{ $backup['date'] }}</span>
                            <span class="text-slate-400 italic">#{{ $loop->iteration }}</span>
                        </div>

                        <!-- Aksi Mobile -->
                        <div class="grid grid-cols-3 gap-1.5 pt-2 border-t border-slate-100">
                            <a href="{{ route('backup-restore-database.download', $backup['name']) }}"
                                class="inline-flex items-center justify-center px-2 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[10px] font-bold uppercase hover:bg-emerald-500 hover:text-white transition-all">
                                <i class="fas fa-download mr-1 text-[9px]"></i> Unduh
                            </a>

                            <button type="button" @click="triggerRestore('{{ $backup['name'] }}')"
                                class="inline-flex items-center justify-center px-2 py-2 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg text-[10px] font-bold uppercase hover:bg-amber-500 hover:text-white transition-all">
                                <i class="fas fa-undo mr-1 text-[9px]"></i> Restore
                            </button>

                            <form id="delete-form-mobile-{{ $loop->index }}" action="{{ route('backup-restore-database.destroy', $backup['name']) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeleteBackup('mobile-{{ $loop->index }}', '{{ $backup['name'] }}')"
                                    class="w-full inline-flex items-center justify-center px-2 py-2 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg text-[10px] font-bold uppercase hover:bg-rose-500 hover:text-white transition-all">
                                    <i class="fas fa-trash mr-1 text-[9px]"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs italic">
                        Belum ada file backup database. Klik tombol "Buat Backup Baru" di atas.
                    </div>
                @endforelse
            </div>

            <!-- 2. TAMPILAN DESKTOP (>= md): Table Mode -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-500 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                            <th class="px-6 py-3.5 w-16">#</th>
                            <th class="px-6 py-3.5">Nama File</th>
                            <th class="px-6 py-3.5">Ukuran File</th>
                            <th class="px-6 py-3.5">Tanggal Dibuat</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($backups as $backup)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3.5 text-xs text-slate-400 italic">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="font-bold text-slate-700 text-xs flex items-center gap-2">
                                        <i class="fas fa-file-code text-indigo-500"></i>
                                        {{ $backup['name'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                        {{ $backup['size'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-500 font-medium">
                                    {{ $backup['date'] }}
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('backup-restore-database.download', $backup['name']) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[10px] font-bold uppercase hover:bg-emerald-500 hover:text-white transition-all"
                                            title="Unduh File Backup">
                                            <i class="fas fa-download mr-1 text-[9px]"></i> Unduh
                                        </a>

                                        <button type="button" @click="triggerRestore('{{ $backup['name'] }}')"
                                            class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg text-[10px] font-bold uppercase hover:bg-amber-500 hover:text-white transition-all"
                                            title="Restore Database">
                                            <i class="fas fa-undo mr-1 text-[9px]"></i> Restore
                                        </button>

                                        <form id="delete-form-desktop-{{ $loop->index }}" action="{{ route('backup-restore-database.destroy', $backup['name']) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteBackup('desktop-{{ $loop->index }}', '{{ $backup['name'] }}')"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg text-[10px] font-bold uppercase hover:bg-rose-500 hover:text-white transition-all"
                                                title="Hapus Backup">
                                                <i class="fas fa-trash mr-1 text-[9px]"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs italic">
                                    Belum ada file backup database. Klik tombol "Buat Backup Baru" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Card Summary & Pagination -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        Showing {{ $backups->firstItem() ?? 0 }} - {{ $backups->lastItem() ?? 0 }} of {{ $backups->total() }} results
                    </p>
                    <div>{{ $backups->appends(request()->query())->links('vendor.pagination.custom') }}</div>
                </div>
            </div>
        </div>

        <!-- 1. POPUP MODAL CREATE BACKUP -->
        <div x-show="openCreateModal" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
            
            <div @click.away="if(!loading) openCreateModal = false"
                class="bg-white rounded-2xl max-w-md w-full p-5 sm:p-6 shadow-2xl border border-slate-100 relative overflow-hidden">
                
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shrink-0">
                            <i class="fas fa-database text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Buat Backup Database</h3>
                            <p class="text-xs text-slate-400">Proses generasi dump file `.sql` baru</p>
                        </div>
                    </div>
                    <button @click="openCreateModal = false" x-show="!loading" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    Sistem akan menyalin struktur dan seluruh data database terkini. Proses ini mungkin memerlukan beberapa detik tergantung ukuran file.
                </p>

                <form action="{{ route('backup-restore-database.create') }}" method="POST" @submit="loading = true">
                    @csrf
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="openCreateModal = false" x-show="!loading"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        
                        <button type="submit" :disabled="loading"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-sm shadow-indigo-200 flex items-center gap-2">
                            <template x-if="!loading">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-play text-[10px]"></i> Mulai Backup
                                </span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-spinner fa-spin text-[10px]"></i> Memproses...
                                </span>
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. POPUP MODAL RESTORE (DARI TABEL INDEX) -->
        <div x-show="openRestoreModal" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
            
            <div @click.away="if(!loading) openRestoreModal = false"
                class="bg-white rounded-2xl max-w-md w-full p-5 sm:p-6 shadow-2xl border border-slate-100 relative overflow-hidden">
                
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0">
                            <i class="fas fa-undo text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Konfirmasi Restore</h3>
                            <p class="text-xs text-slate-400">Pulihkan kondisi database dari cadangan</p>
                        </div>
                    </div>
                    <button @click="openRestoreModal = false" x-show="!loading" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 mb-4 text-xs text-amber-800 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <strong class="font-bold block mb-0.5">Peringatan Penting!</strong>
                        Proses ini akan <u>menimpa seluruh data saat ini</u> dengan data dari file backup terpilih.
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">File Backup Terpilih</label>
                        <div class="p-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 flex items-center gap-2 overflow-hidden">
                            <i class="fas fa-file-code text-indigo-500 shrink-0"></i>
                            <span x-text="selectedBackup" class="truncate"></span>
                        </div>
                    </div>
                </div>

                <form :action="'{{ url('backup-restore-database/restore') }}/' + selectedBackup" method="POST" @submit="loading = true">
                    @csrf
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="openRestoreModal = false" x-show="!loading"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        
                        <button type="submit" :disabled="loading"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 transition-all shadow-md flex items-center justify-center gap-2 min-w-[130px]"
                            style="background-color: #d97706 !important; color: #ffffff !important;">
                            <span x-show="!loading" class="flex items-center gap-1.5">
                                <i class="fas fa-undo text-[10px]"></i> Ya, Restore
                            </span>

                            <span x-show="loading" class="flex items-center gap-1.5">
                                <i class="fas fa-spinner fa-spin text-[10px]"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. POPUP MODAL RESTORE DARI UPLOAD FILE -->
        <div x-show="openUploadRestoreModal" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
            
            <div @click.away="if(!loading) openUploadRestoreModal = false"
                class="bg-white rounded-2xl max-w-md w-full p-5 sm:p-6 shadow-2xl border border-slate-100 relative overflow-hidden">
                
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0">
                            <i class="fas fa-upload text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Restore File Lokal</h3>
                            <p class="text-xs text-slate-400">Upload file backup `.sql` atau `.gz`</p>
                        </div>
                    </div>
                    <button @click="openUploadRestoreModal = false" x-show="!loading" type="button" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 mb-4 text-xs text-amber-800 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <strong class="font-bold block mb-0.5">Perhatian!</strong>
                        Seluruh isi database saat ini akan <strong>tertimpa</strong> oleh isi file backup yang diunggah.
                    </div>
                </div>

                <form action="{{ route('backup-restore-database.restore-file') }}" method="POST" enctype="multipart/form-data" @submit="loading = true">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Pilih File Backup (.sql / .gz)</label>
                        <input type="file" name="backup_file" accept=".sql,.gz" required
                            class="block w-full text-xs text-slate-500
                                file:mr-3 file:py-2 file:px-3 file:rounded-xl
                                file:border-0 file:text-xs file:font-bold
                                file:bg-amber-50 file:text-amber-700
                                hover:file:bg-amber-100
                                border border-slate-200 rounded-xl cursor-pointer bg-slate-50/50 p-1">
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="openUploadRestoreModal = false" x-show="!loading"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        
                        <button type="submit" :disabled="loading"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 transition-all shadow-md flex items-center justify-center gap-2 min-w-[130px]"
                            style="background-color: #d97706 !important; color: #ffffff !important;">
                            <span x-show="!loading">Upload & Restore</span>
                            <span x-show="loading">Memproses File...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <!-- Script SweetAlert2 & Toast -->
    <script>
        function confirmDeleteBackup(idKey, filename) {
            Swal.fire({
                title: 'Hapus File Backup?',
                text: "Yakin ingin menghapus file " + filename + "? Data yang dihapus tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl shadow-xl border border-slate-100',
                    confirmButton: 'px-5 py-2.5 rounded-xl bg-red-500 text-white mx-1 font-bold text-xs',
                    cancelButton: 'px-5 py-2.5 rounded-xl bg-slate-200 text-slate-700 mx-1 font-bold text-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + idKey).submit();
                }
            });
        }

        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 10000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl border border-slate-100 shadow-xl bg-white/90 backdrop-blur-sm',
                    title: 'text-xs font-normal text-slate-700 font-sans',
                    closeButton: 'text-slate-400 hover:text-red-500 transition-colors focus:shadow-none border-none'
                }
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    iconColor: '#10b981',
                    title: "{{ session('success') }}"
                });
                @php session()->forget('success'); @endphp
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    iconColor: '#f43f5e',
                    title: "{{ session('error') }}"
                });
                @php session()->forget('error'); @endphp
            @endif
        });
    </script>
@endsection