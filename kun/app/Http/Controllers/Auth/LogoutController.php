<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle logout request
     * Redirects to login page after successful logout
     */
    public function logout(Request $request)
    {
        // Store user info before logout for logging
        $user = Auth::user();
        
        // Logout user
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log logout activity (optional - only if activity package is installed)
        if (function_exists('activity') && $user) {
            try {
                activity()
                    ->causedBy($user)
                    ->log('User logged out');
            } catch (\Exception $e) {
                // Silently fail if activity logging is not available
            }
        }

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
