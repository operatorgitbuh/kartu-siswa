<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Menampilkan halaman profil sekolah.
     */
    public function index()
    {
        // Ambil data pertama. Jika tabel masih kosong, buat instance baru 
        $school = School::first() ?? new School();

        return view('pages.school.index', compact('school'));
    }

    /**
     * Memperbarui atau membuat data identitas sekolah (Single Row).
     */
    public function update(Request $request)
    {
        // Ambil data yang ada atau siapkan model baru jika kosong
        $school = School::first() ?: new School();

        // 1. Validasi Input
        $data = $request->validate([
            'nama_sekolah'        => 'required|string|max:255',
            'npsn_sekolah'        => 'nullable|string|max:50',
            'alamat_sekolah'      => 'nullable|string',
            'pemerintah_provinsi' => 'nullable|string|max:255',
            'instansi_pemerintah' => 'nullable|string|max:255',
            'email_sekolah'       => 'nullable|email|max:255',
            'website_sekolah'     => 'nullable|string|max:255',
            'nama_kepsek'         => 'nullable|string|max:255',
            'nip_kepsek'          => 'nullable|string|max:50',
            'logo_provinsi'       => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_sekolah'        => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'ttd_kepsek'          => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'cap_sekolah'         => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // Daftar field file gambar
        $fileFields = ['logo_provinsi', 'logo_sekolah', 'ttd_kepsek', 'cap_sekolah'];

        foreach ($fileFields as $field) {
            // SITUASI A: User Upload File Baru
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($school->$field && Storage::disk('public')->exists($school->$field)) {
                    Storage::disk('public')->delete($school->$field);
                }
                // Simpan file baru
                $data[$field] = $request->file($field)->store('school_assets', 'public');
            } 
            
            // SITUASI B: User Klik Tombol X (Hapus Foto)
            // Kita cek input hidden 'remove_namafield' yang dikirim dari Blade
            elseif ($request->input("remove_{$field}") == '1') {
                if ($school->$field && Storage::disk('public')->exists($school->$field)) {
                    Storage::disk('public')->delete($school->$field);
                }
                $data[$field] = null; // Set di database jadi NULL
            }

            // SITUASI C: Tidak ada perubahan
            else {
                // Hapus dari array $data supaya tidak menimpa data lama dengan NULL secara tidak sengaja
                unset($data[$field]);
            }
        }

        // 2. Eksekusi simpan ke Database
        $school->fill($data);
        $school->save();

        return back()->with('success', 'Identitas Sekolah berhasil diperbarui!');
    }
}