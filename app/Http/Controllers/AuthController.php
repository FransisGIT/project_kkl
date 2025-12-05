<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }

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
                    return redirect()->route('persetujuan-krs.index')->with('success', 'Login berhasil sebagai Admin!');
                case 2: // Dosen
                    return redirect()->route('persetujuan-krs.index')->with('success', 'Login berhasil sebagai Dosen!');
                case 3: // Mahasiswa
                    return redirect()->route('beranda.index')->with('success', 'Login berhasil sebagai Mahasiswa!');
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

    /**
     * Switch user role
     */
    public function switchRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id_role',
        ]);

        $user = Auth::user();
        User::where('id_user', $user->id_user)->update(['id_role' => $request->role_id]);

        // Redirect berdasarkan role baru
        switch ($request->role_id) {
            case 1: // Admin
                return redirect()->route('persetujuan-krs.index')->with('success', 'Berhasil berganti ke Admin!');
            case 2: // Dosen
                return redirect()->route('persetujuan-krs.index')->with('success', 'Berhasil berganti ke Dosen!');
            case 3: // Mahasiswa
                return redirect()->route('beranda.index')->with('success', 'Berhasil berganti ke Mahasiswa!');
            default:
                return back()->with('error', 'Role tidak valid.');
        }
    }
}
