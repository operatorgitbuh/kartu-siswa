<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission; // Pastikan import model Spatie jika menggunakan library ini

class PermissionController extends Controller
{
    public function index()
    {
        // Menggunakan get() atau paginate() sesuai kebutuhan
        $permissions = Permission::latest()->get();
        return view('pages.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return back()->with('success', 'Permission baru berhasil dibuat!');
    }

    /**
     * Method untuk memperbarui data permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            // Abaikan ID saat ini agar tidak terkena validasi unique diri sendiri
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        $permission->update([
            'name' => $request->name,
            // guard_name biasanya tetap 'web', tidak perlu diupdate kecuali diperlukan
        ]);

        return back()->with('success', 'Permission berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return back()->with('success', 'Permission berhasil dihapus!');
    }
}