<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::transliterate(Str::lower($credentials['email']) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey);

            // Pesan sengaja tidak menyebut apakah email terdaftar, supaya
            // halaman login tidak bisa dipakai menebak akun yang ada.
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return $this->homeFor($request->user());
    }

    public function showRegister(): View
    {
        return view('admin.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $throttleKey = 'register|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan pendaftaran. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:190|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Setiap percobaan yang lolos validasi tetap dihitung. Kalau pen、县
        // dikosongkan tiap berhasil, bot bisa membuat akun tanpa batas.
        RateLimiter::hit($throttleKey);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            // Cast 'hashed' pada model yang bertanggung jawab; jangan di-hash
            // dua kali di sini.
            'password' => $data['password'],
            // Pendaftaran ini untuk pengelola panel, bukan untuk pengunjung
            // umum. Tabel users memang hanya dipakai untuk masuk ke panel.
            'is_admin' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('status', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Admin diarahkan ke panel, akun biasa ke landing page.
     */
    private function homeFor(?User $user): RedirectResponse
    {
        return $user && $user->isAdmin()
            ? redirect()->intended(route('admin.posts.index'))
            : redirect()->route('landing');
    }
}
