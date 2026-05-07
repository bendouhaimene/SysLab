<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // ✅ نبحث على المستخدم يدوياً
    $user = \App\Models\User::where('username', $request->username)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'username' => 'Invalid username or password.',
        ])->withInput($request->only('username'));
    }

    auth()->login($user, $request->filled('remember'));
    $request->session()->regenerate();
    $user->update(['last_seen' => now()]);

    return $this->redirectByRole($user->role);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(string $role)
    {
        return match($role) {
            'admin'         => redirect()->route('admin.dashboard'),
            'receptionist'  => redirect()->route('reception.search'),
            'biologist'     => redirect()->route('biologist.queue'),
            'doctor'        => redirect()->route('doctor.pending'),
            default         => redirect()->route('login'),
        };
    }
}