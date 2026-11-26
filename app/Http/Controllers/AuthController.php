<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman formulir login admin.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses percobaan login admin.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $fallback = Route::has('admin.software.index')
                ? route('admin.software.index')
                : '/';

            return redirect()->intended($fallback)->with('status', 'Berhasil masuk sebagai admin.');
        }

        return back()
            ->withErrors(['email' => 'Email atau kata sandi salah.'])
            ->onlyInput('email');
    }

    /**
     * Mengeluarkan admin dari sesi saat ini.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Anda telah keluar.');
    }
}
