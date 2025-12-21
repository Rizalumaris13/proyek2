<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

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
    $credentials = $request->validate([
        'email' => ['required','email'],
        'password' => ['required','string'],
    ]);

    $remember = $request->boolean('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        
        // ✅ LOG untuk debugging
        \Log::info('Login successful', [
            'user_id' => Auth::id(),
            'email' => $request->email
        ]);
        
        // ✅ SEMUA USER redirect ke dashboard yang SAMA
        return redirect()->route('dashboard');
    }

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
            'password' => ['required','string','min:8','confirmed'],
        ]);

        // buat user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            
        ]);

        // ✅ Jika role guru, buat data di tabel gurus
        if ($data['role'] === 'guru') {
            \App\Models\Guru::create([
                'user_id' => $user->id,
                'mapel' => 'Belum Diatur',
            ]);
        }

        // otomatis login setelah registrasi (opsional)
        Auth::login($user);

        // redirect berdasarkan role
        if ($user->isAdmin()) {
            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil!');
        } elseif ($user->isGuru()) {
            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil!');
        }

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil!');
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