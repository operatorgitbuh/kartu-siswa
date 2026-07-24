<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi, Pak.',
            'email.email'    => 'Format email tidak valid.',
            'password.required' => 'Kata sandi tidak boleh kosong.',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // 2. Logika Pengalihan Berdasarkan Role
            $user = Auth::user();

            // Jika menggunakan Spatie: $user->hasRole('ADMIN')
            // Jika menggunakan kolom manual: $user->role === 'ADMIN'
            if ($user->hasRole('ADMIN')) {
                return redirect()->intended('/dashboard')
                    ->with('success', 'Selamat datang Admin, ' . $user->name . '!');
            }

            if ($user->hasRole('WALI_KELAS')) {
                return redirect()->intended('/wali-kelas/dashboard')
                    ->with('success', 'Selamat datang Wali Kelas, ' . $user->name . '!');
            }

            // Default jika role tidak terdaftar
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Bapak telah berhasil keluar dari sistem.');
    }
}
