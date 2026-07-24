<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role; // Import Model Role Spatie

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'ADMIN');
            })
            ->leftJoin('model_has_roles', function ($join) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', User::class);
            })
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('users.name', 'like', '%' . $request->search . '%')
                    ->orWhere('users.email', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderBy('roles.name', 'asc')
            ->orderBy('users.created_at', 'desc');

        $perPage = $request->get('perPage', 10);
        $users = $query->paginate($perPage)->withQueryString();

        $roles = Role::where('name', '!=', 'ADMIN')->get();

        return view('pages.users.index', compact('users', 'roles'));
    }

    public function IndexProfile()
    {
        $user = Auth::user();

        return view('pages.users.profile-me', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles'    => 'required', // Validasi role wajib dipilih
            'avatars'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('roles'); // Ambil semua kecuali roles untuk create user
        $data['password'] = Hash::make($request->password);

        // Logika Upload Avatar
        if ($request->hasFile('avatars')) {
            $data['avatars'] = $request->file('avatars')->store('avatars', 'public');
        }

        $user = User::create($data);

        // Assign Role Spatie
        $user->assignRole($request->roles);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatars'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('avatars')) {
            if ($user->avatars && Storage::disk('public')->exists($user->avatars)) {
                Storage::disk('public')->delete($user->avatars);
            }
            $user->avatars = $request->file('avatars')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'roles'    => 'required', // Validasi role wajib dipilih
            'avatars'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update Avatar
        if ($request->hasFile('avatars')) {
            // Hapus foto lama jika ada
            if ($user->avatars && Storage::disk('public')->exists($user->avatars)) {
                Storage::disk('public')->delete($user->avatars);
            }
            $user->avatars = $request->file('avatars')->store('avatars', 'public');
        }

        $user->save();

        // Sinkronisasi Role Spatie (Menghapus role lama, mengganti dengan yang baru)
        $user->syncRoles($request->roles);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Jangan hapus diri sendiri jika user yang login adalah user ini
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        // Hapus file fotonya dari storage
        if ($user->avatars && Storage::disk('public')->exists($user->avatars)) {
            Storage::disk('public')->delete($user->avatars);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus!');
    }


    public function profile()
    {
        $user = auth()->user()->load(['classroom' => function ($q) {
            $q->withCount('students');
        }]);
        return view('wali_kelas.users.index', compact('user'));
    }

    public function updateWakel(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'avatars'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'avatars.max'  => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('avatars')) {
            if ($user->avatars && Storage::disk('public')->exists($user->avatars)) {
                Storage::disk('public')->delete($user->avatars);
            }
            $path = $request->file('avatars')->store('avatars', 'public');
            $user->avatars = $path;
        }
        $user->save();
        return redirect()->route('wali-kelas.users')
            ->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
