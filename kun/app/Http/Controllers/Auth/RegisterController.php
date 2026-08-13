<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'terms' => ['accepted'],
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Assign default role (user)
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        // Log the user in
        Auth::login($user);

        // Send welcome email (optional)
        // Mail::to($user->email)->send(new WelcomeEmail($user));

        // Log registration activity
        activity()
            ->causedBy($user)
            ->log('User registered');

        return redirect('/')->with('success', 'Welcome to Kun, ' . $user->name . '!');
    }

    /**
     * Show email verification notice
     */
    public function showVerificationNotice()
    {
        return view('auth.verify-email');
    }

    /**
     * Handle email verification
     */
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect('/')->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('info', 'Email already verified.');
        }

        $user->markEmailAsVerified();

        return redirect('/')->with('success', 'Email verified successfully!');
    }

    /**
     * Resend email verification
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/')->with('info', 'Email already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}
