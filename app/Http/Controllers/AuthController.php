<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('auth.halaman_login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required|min:4',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            switch ($user->id_role) {
                case 1: // Admin
                    return redirect()->route('dashboard.index')->with('success', 'Login berhasil sebagai Admin!');
                case 2: // Dosen
                    return redirect()->route('dashboard.index')->with('success', 'Login berhasil sebagai Dosen!');
                case 3: // Mahasiswa
                    return redirect()->route('dashboard.index')->with('success', 'Login berhasil sebagai Mahasiswa!');
                default:
                    Auth::logout();
                    return back()->withErrors([
                        'name' => 'Role tidak valid.',
                    ]);
            }
        }

        return back()->withErrors([
            'name' => 'Username atau password salah.',
        ])->onlyInput(['name', 'password']);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logout berhasil!');
    }
}
