@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="schoolForm()" x-cloak>
        {{-- Breadcrumb --}}
        <div class="space-y-2">
            <nav class="flex mb-2 -mt-4 md:-mt-4 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
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
                        <span class="text-indigo-600 font-bold">Schools</span>
                    </li>
                </ol>
            </nav>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    Manajemen Schools
                </h2>
                <p class="text-slate-500 text-sm mt-1 flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    Otomasi pendataan template background SMKN 1 Wonosari
                </p>
            </div>
        </div>
        <form action="{{ route('schools.update', $school->id ?? '1') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                {{-- Header --}}
                <div
                    class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <h1 class="text-lg font-bold text-slate-800 tracking-tight">Identitas Sekolah</h1>
                        <p class="text-slate-500 text-[10px] uppercase tracking-widest mt-0.5">Konfigurasi Profil & Aset
                            Digital</p>
                    </div>
                    <div class="flex gap-3">
                        {{-- Tombol Ubah / Batal --}}
                        <button type="button" @click="isEditing ? confirmCancel() : enableEdit()"
                            class="px-5 py-2 rounded-xl text-xs transition-all flex items-center gap-2 border"
                            :class="isEditing ? 'bg-rose-50 text-rose-600 border-rose-100' :
                                'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                            <span x-text="isEditing ? 'Batal' : 'Ubah Data'"></span>
                        </button>

                        {{-- Tombol Simpan --}}
                        <button type="submit" x-show="isEditing" x-cloak x-transition
                            class="px-6 py-2 bg-indigo-600 text-white rounded-xl text-xs uppercase tracking-widest shadow-md hover:bg-indigo-700 active:scale-95 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-8 md:p-10 space-y-10">

                    {{-- Pesan Error Validasi Global (Opsional) --}}
                    @if ($errors->any())
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-50 rounded-xl border border-red-100">
                            Mohon periksa kembali inputan Anda.
                        </div>
                    @endif

                    {{-- Data Teknis Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-8">

                        {{-- Nama Sekolah --}}
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">Nama
                                Instansi / Sekolah</label>
                            <input type="text" name="nama_sekolah"
                                value="{{ old('nama_sekolah', $school->nama_sekolah) }}"
                                class="w-full px-0 bg-transparent border-b {{ $errors->has('nama_sekolah') ? 'border-red-400' : 'border-slate-100' }} text-sm text-slate-700 focus:border-indigo-500 focus:ring-0 transition-all outline-none"
                                :readonly="!isEditing" placeholder="Contoh: SMK Negeri 1 Wonosari">
                            @error('nama_sekolah')
                                <span class="text-[9px] text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Pemerintah Provinsi --}}
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">Pemerintah
                                Provinsi</label>
                            <input type="text" name="pemerintah_provinsi"
                                value="{{ old('pemerintah_provinsi', $school->pemerintah_provinsi) }}"
                                class="w-full px-0  bg-transparent border-b border-slate-100 text-sm text-slate-600 focus:border-indigo-400 focus:ring-0 outline-none transition-all"
                                :readonly="!isEditing" placeholder="Contoh: Pemerintah Provinsi Gorontalo">
                        </div>

                        {{-- Instansi Pemerintah --}}
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">Instansi
                                Pemerintah</label>
                            <input type="text" name="instansi_pemerintah"
                                value="{{ old('instansi_pemerintah', $school->instansi_pemerintah) }}"
                                class="w-full px-0  bg-transparent border-b border-slate-100 text-sm text-slate-600 focus:border-indigo-400 focus:ring-0 outline-none transition-all"
                                :readonly="!isEditing" placeholder="Contoh: Dinas Pendidikan">
                        </div>

                        {{-- NPSN --}}
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">NPSN
                                Sekolah</label>
                            <input type="text" name="npsn_sekolah"
                                value="{{ old('npsn_sekolah', $school->npsn_sekolah) }}"
                                class="w-full px-0  bg-transparent border-b border-slate-100 text-sm text-slate-600 focus:border-indigo-400 focus:ring-0 outline-none transition-all"
                                :readonly="!isEditing" placeholder="Masukkan 8 Digit NPSN">
                        </div>

                        {{-- Nama Kepsek --}}
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">Nama Kepala
                                Sekolah</label>
                            <input type="text" name="nama_kepsek" value="{{ old('nama_kepsek', $school->nama_kepsek) }}"
                                class="w-full px-0 bg-transparent border-b border-slate-100 text-sm text-slate-600 focus:border-indigo-400 focus:ring-0 outline-none transition-all"
                                :readonly="!isEditing" placeholder="Nama Lengkap Beserta Gelar">
                        </div>

                        {{-- NIP Kepsek --}}
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">NIP Kepala
                                Sekolah</label>
                            <input type="text" name="nip_kepsek" value="{{ old('nip_kepsek', $school->nip_kepsek) }}"
                                class="w-full px-0 bg-transparent border-b border-slate-100 text-sm text-slate-600 focus:border-indigo-400 focus:ring-0 outline-none transition-all"
                                :readonly="!isEditing" placeholder="Masukkan NIP">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">Email
                                Sekolah</label>
                            <input type="email" name="email_sekolah"
                                value="{{ old('email_sekolah', $school->email_sekolah) }}"
                                class="w-full px-0 bg-transparent border-b border-slate-100 text-sm text-slate-600 focus:border-indigo-400 focus:ring-0 outline-none transition-all"
                                :readonly="!isEditing" placeholder="sekolah@kemdikbud.go.id">
                        </div>

                        {{-- Website --}}
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">Website
                                Sekolah</label>
                            <input type="text" name="website_sekolah"
                                value="{{ old('website_sekolah', $school->website_sekolah) }}"
                                class="w-full px-0 bg-transparent border-b border-slate-100 text-sm text-slate-600 focus:border-indigo-400 focus:ring-0 outline-none transition-all"
                                :readonly="!isEditing" placeholder="www.sekolah.sch.id">
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2 lg:col-span-2">
                            <label class="block text-[10px] text-slate-400 uppercase ml-1 mb-1 tracking-widest">Alamat
                                Lengkap Sekolah</label>
                            <input type="text" name="alamat_sekolah"
                                value="{{ old('alamat_sekolah', $school->alamat_sekolah) }}"
                                class="w-full px-0 bg-transparent border-b border-slate-100 text-sm text-slate-600 focus:border-indigo-400 focus:ring-0 outline-none transition-all"
                                :readonly="!isEditing" placeholder="Masukkan Alamat Lengkap">
                        </div>
                    </div>

                    {{-- <hr class="border-slate-100"> --}}

                    {{-- Digital Assets Section --}}
                    <div>
                        <label
                            class="block text-[10px] text-slate-400 uppercase tracking-widest mb-6 text-center md:text-left">Berkas
                            & Aset Digital</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                            @php
                                $assets = [
                                    'logo_provinsi' => 'Logo Provinsi',
                                    'logo_sekolah' => 'Logo Sekolah',
                                    'ttd_kepsek' => 'TTD Kepala Sekolah',
                                    'cap_sekolah' => 'Cap Sekolah',
                                ];
                            @endphp

                            @foreach ($assets as $field => $label)
                                <div class="space-y-2" x-data="{
                                    photoPreview: '{{ $school->$field ? asset('storage/' . $school->$field) : '' }}',
                                    isRemoved: false
                                }">
                                    <span
                                        class="text-[9px] text-slate-400 uppercase flex justify-center tracking-tighter">{{ $label }}</span>

                                    <div class="h-32 rounded-2xl bg-slate-50 border border-dashed border-slate-200 flex items-center justify-center relative overflow-hidden transition-all"
                                        :class="isEditing ? 'hover:border-indigo-400' : ''">

                                        {{-- Preview Image --}}
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="h-full w-full object-contain p-3">
                                        </template>

                                        {{-- Placeholder --}}
                                        <template x-if="!photoPreview">
                                            <div class="text-center">
                                                <svg class="mx-auto w-6 h-6 text-slate-200" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                        stroke-width="1.5" stroke-linecap="round" />
                                                </svg>
                                            </div>
                                        </template>

                                        {{-- Input Hidden untuk Flag Hapus --}}
                                        <input type="hidden" name="remove_{{ $field }}"
                                            :value="isRemoved ? '1' : '0'">

                                        {{-- File Input --}}
                                        <input type="file" name="{{ $field }}"
                                            class="absolute inset-0 opacity-0"
                                            :class="isEditing ? 'cursor-pointer' : 'pointer-events-none'"
                                            :disabled="!isEditing"
                                            @change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    isRemoved = false;
                                                    const reader = new FileReader();
                                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                                    reader.readAsDataURL(file);
                                                }
                                            ">

                                        {{-- Hapus Button --}}
                                        <button type="button" x-show="isEditing && photoPreview"
                                            @click="photoPreview = ''; isRemoved = true; $el.closest('.h-32').querySelector('input[type=file]').value = ''"
                                            class="absolute top-2 right-2 bg-rose-500 text-white p-1 rounded-lg hover:bg-rose-600 transition-colors shadow-lg z-10">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error($field)
                                        <span
                                            class="text-[9px] text-red-500 block text-center mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-10 py-4 bg-slate-50/30 border-t border-slate-50 flex justify-center italic">
                    <p class="text-[9px] text-slate-400 tracking-tight">Status: Konfigurasi Identitas Resmi</p>
                </div>
            </div>
        </form>
    </div>

    <script>
        function schoolForm() {
            return {
                isEditing: false,

                // Fungsi untuk masuk ke mode Edit
                enableEdit() {
                    this.isEditing = true;
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'info',
                        title: 'Mode ubah data aktif'
                    });
                },

                // Fungsi Batal dengan SweetAlert Modal
                confirmCancel() {
                    Swal.fire({
                        title: 'Batalkan Perubahan?',
                        text: "Data yang baru saja diketik tidak akan tersimpan.",
                        icon: 'warning',
                        showCancelButton: true,
                        reverseButtons: true,
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjutkan Edit',
                        // Gunakan customClass agar tombol mengikuti gaya UI kamu
                        customClass: {
                            confirmButton: 'px-6 py-3 mx-2 bg-indigo-600 text-white rounded-xl text-xs uppercase tracking-widest',
                            cancelButton: 'px-6 py-3 mx-2 bg-rose-500 text-white rounded-xl text-xs uppercase tracking-widest'
                        },
                        buttonsStyling: false // WAJIB false kalau mau pakai class Tailwind sendiri
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                },

                confirmSubmit(event) {
                    // Jika tombol submit di dalam form, kita tangkap element formnya
                    const form = event.target;
                    event.preventDefault();

                    Swal.fire({
                        title: 'Simpan Perubahan?',
                        text: "Pastikan data identitas sekolah sudah benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Cek Kembali',
                        customClass: {
                            confirmButton: 'px-6 py-2 mx-2 bg-indigo-600 text-white rounded-xl text-xs uppercase tracking-widest',
                            cancelButton: 'px-6 py-2 mx-2 bg-slate-500 text-white rounded-xl text-xs uppercase tracking-widest'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            }
        }
    </script>
@endsection
