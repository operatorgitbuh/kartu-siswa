<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        // Kita butuh data permission untuk pilihan checkbox di modal tambah/edit role
        $permissions = Permission::all();

        return view('pages.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            if ($request->has('permissions')) {
                // Kita ambil objek permission-nya, lalu sync
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
        });

        return back()->with('success', 'Role berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'array'
        ]);

        DB::transaction(function () use ($request, $role) {
            $role->update(['name' => $request->name]);

            // Gunakan get() agar mengirimkan collection of models, bukan ID mentah
            $permissions = Permission::whereIn('id', $request->permissions ?? [])->get();
            $role->syncPermissions($permissions);
        });

        return back()->with('success', 'Role berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return back()->with('success', 'Role berhasil dihapus!');
    }
}
