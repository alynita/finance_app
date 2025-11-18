<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;
        $password = $request->password;

        // Cari user berdasarkan email atau NIP
        $user = User::where('email', $login)->orWhere('nip', $login)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            return redirect()->intended('/home');
        }

        return back()->with('error', 'Email/NIP atau password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
