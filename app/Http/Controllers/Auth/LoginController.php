<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember', false);

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if (!$user->is_active || !$user->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Unauthorized'])->withInput();
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
