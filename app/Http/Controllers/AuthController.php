<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // validasi input
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $remember = $request->boolean('remember');

        // coba autentikasi
        if (Auth::attempt($credentials, $remember)) {
            // hindari session fixation
            $request->session()->regenerate();

            // redirect ke intended atau dashboard
            return redirect()->intended(route('dashboard'));
        }

        // jika gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Tampilkan form register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi (create user)
     */
    public function register(Request $request)
    {
        // validasi input
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255', Rule::unique('users','email')],
            'password' => ['required','string','min:8','confirmed'], // expects password_confirmation
        ]);

        // buat user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // otomatis login setelah registrasi (opsional)
        Auth::login($user);

        // redirect ke halaman yang diinginkan
        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login!');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
