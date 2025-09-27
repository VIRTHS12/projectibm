<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showRegistrationForm()
    {
        return view('register'); // Akan kita buat view-nya
    }

    public function register(Request $request)
    {
        // 1. Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // 'unique:users' memastikan email belum terdaftar
            'password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencocokkan dengan 'password_confirmation'
        ]);

        // 2. Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // WAJIB: Password harus di-hash!
        ]);

        // 3. Langsung login-kan user yang baru daftar
        Auth::login($user);

        // 4. Redirect ke halaman utama (chat)
        return redirect('/');
    }

    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Memproses data login dari form
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // Redirect ke halaman chat setelah berhasil
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
