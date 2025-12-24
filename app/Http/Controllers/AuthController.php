<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
                case 4: // Keuangan
                    return redirect()->route('dispensasi.index')->with('success', 'Login berhasil sebagai Keuangan!');
                case 5: // Wakil Rektor 2
                    return redirect()->route('dispensasi.index')->with('success', 'Login berhasil sebagai Wakil Rektor 2!');
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
        // clear any role override stored in session
        $request->session()->forget('role_id_override');
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
        $oldRole = $user->id_role;
        // Don't persist role change to database. Store override in session so
        // the user's identity (name, id_user) remains the same but role
        // context can be switched for the session only.
        $request->session()->put('role_id_override', (int) $request->role_id);
        Log::info('User switch role (session only)', [
            'id_user' => $user->id_user,
            'old_role' => $oldRole,
            'new_role' => $request->role_id,
            'by' => $user->name,
            'at' => now()->toDateTimeString(),
        ]);

        // Apply override to current request user instance so subsequent logic
        // in this request uses the overridden role.
        $user->id_role = (int) $request->role_id;
        Auth::setUser($user);

        // Redirect berdasarkan role baru
        switch ($request->role_id) {
            case 1: // Admin
                return redirect()->route('persetujuan-krs.index')->with('success', 'Berhasil berganti ke Admin!');
            case 2: // Dosen
                return redirect()->route('persetujuan-krs.index')->with('success', 'Berhasil berganti ke Dosen!');
            case 3: // Mahasiswa
                return redirect()->route('beranda.index')->with('success', 'Berhasil berganti ke Mahasiswa!');
            case 4: // Keuangan
                return redirect()->route('dispensasi.index')->with('success', 'Berhasil berganti ke Keuangan!');
            case 5: // Wakil Rektor 2
                return redirect()->route('dispensasi.index')->with('success', 'Berhasil berganti ke Wakil Rektor 2!');
            default:
                return back()->with('error', 'Role tidak valid.');
        }
    }
}
