<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'pegawai') {
                return redirect()->intended('/pegawai/dashboard');
            } elseif ($user->role === 'sarpras') {
                return redirect()->intended('/sarpras/dashboard');
            } elseif ($user->role === 'bmn') {
                return redirect()->intended('/bmn/dashboard');
            } elseif ($user->role === 'pengadaan') {
                return redirect()->intended('/pengadaan/dashboard');
            } elseif ($user->role === 'adum') {
                return redirect()->intended('/adum/dashboard');
            } elseif (str_starts_with($user->role, 'timker_')) { // timker1–timker6
                return redirect()->intended('/' . $user->role . '/dashboard'); // misal /timker1/dashboard
            } elseif ($user->role === 'ppk') {
                return redirect()->intended('/ppk/dashboard');
            } elseif ($user->role === 'keuangan') {
                return redirect()->intended('/keuangan/dashboard');
            } elseif ($user->role === 'verifikator') {
                return redirect()->intended('/verifikator/dashboard');
            } elseif ($user->role === 'bendahara') {
                return redirect()->intended('/bendahara/dashboard');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        // Kalau login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
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
