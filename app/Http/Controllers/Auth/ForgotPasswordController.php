<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Generate token and create reset link
        $token = Str::random(64);
        $resetLink = url(route('password.reset', ['token' => $token]));
        
        // Store token with email in password_resets table
        \DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Store reset link in session for the next view
        return redirect()->back()->with([
            'status' => 'A password reset link has been generated.',
            'resetLink' => $resetLink
        ]);
    }
}
