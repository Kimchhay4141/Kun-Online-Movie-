<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Log login activity
            activity()
                ->causedBy(Auth::user())
                ->log('User logged in');

            return redirect()->intended('/')->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle social login (Google, Facebook, etc.)
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle social login callback
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user, true);

            return redirect('/')->with('success', 'Successfully logged in!');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Unable to login with ' . $provider);
        }
    }
}
