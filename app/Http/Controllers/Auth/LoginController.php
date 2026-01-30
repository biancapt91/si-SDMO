<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        $data = $request->validate([
            'identifier' => ['required'],
            'password' => ['required'],
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');

        // NIP login: prefer hidden nip (selected from suggestion) otherwise use typed identifier
        $nip = $request->input('nip') ?: $identifier;

        if (Auth::attempt(['nip' => $nip, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['identifier' => 'NIP atau password salah.'])->onlyInput('identifier');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
