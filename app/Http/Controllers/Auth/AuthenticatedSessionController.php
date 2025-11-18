<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;
        $password = $request->password;

        // Cari user berdasarkan email atau NIP
        $user = \App\Models\User::where('email', $login)
                ->orWhere('nip', $login)
                ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors([
                'login' => 'Email/NIP atau password salah',
            ])->onlyInput('login');
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Redirect berdasarkan role
        switch($user->role) {
            case 'admin': return redirect()->intended('/admin/dashboard');
            case 'pegawai': return redirect()->intended('/pegawai/dashboard');
            case 'sarpras': return redirect()->intended('/sarpras/dashboard');
            case 'bmn': return redirect()->intended('/bmn/dashboard');
            case 'pengadaan': return redirect()->intended('/pengadaan/dashboard');
            case 'adum': return redirect()->intended('/adum/dashboard');
            case (str_starts_with($user->role, 'timker_')): 
                return redirect()->intended('/' . $user->role . '/dashboard');
            case 'ppk': return redirect()->intended('/ppk/dashboard');
            case 'keuangan': return redirect()->intended('/keuangan/dashboard');
            case 'verifikator': return redirect()->intended('/verifikator/dashboard');
            case 'bendahara': return redirect()->intended('/bendahara/dashboard');
            default: return redirect()->intended('/dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
