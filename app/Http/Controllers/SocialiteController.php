<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect pengguna ke halaman autentikasi Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback dari Google setelah autentikasi.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Jika user sudah ada, perbarui google_id & avatar jika belum ada
                $user->update([
                    'google_id' => $user->google_id ?? $googleUser->id,
                    'avatar'    => $user->avatar ?? $googleUser->avatar,
                ]);
            } else {
                // Jika user belum terdaftar, buat akun baru otomatis
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar'    => $googleUser->avatar,
                    'role'      => 'buyer', // Default role pembeli
                    'password'  => Hash::make(Str::random(16)), // Password acak aman
                ]);
            }

            // Login-kan user ke sistem
            Auth::login($user, true);

            return redirect()->intended(route('home'))->with('success', 'Berhasil login via Google!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal melakukan login via Google. Silakan coba lagi.');
        }
    }
}