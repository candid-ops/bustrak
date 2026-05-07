<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomPasswordResetController extends Controller
{
    // Send reset link email
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['message' => 'We can\'t find a user with that email address.'], 422);
        }

        $token = Password::broker()->createToken($user);
        
        $resetUrl = config('app.url') . '/reset-password/' . $token . '?email=' . urlencode($user->email);

        Mail::send([], [], function ($message) use ($user, $resetUrl) {
            $message->to($user->email)
                    ->subject('Reset Your Password - BusTrak')
                    ->html("
                        <html>
                        <body style='font-family: Arial, sans-serif;'>
                            <h2>Reset Your Password</h2>
                            <p>Click the link below to reset your password:</p>
                            <p><a href='{$resetUrl}' style='background: #1A4DFF; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Password</a></p>
                            <p>Or copy this link: <br>{$resetUrl}</p>
                            <p>This link will expire in 60 minutes.</p>
                            <p>If you didn't request this, please ignore this email.</p>
                            <br>
                            <p>BusTrak Support</p>
                        </body>
                        </html>
                    ");
        });

        return response()->json(['message' => 'Reset link sent to your email!']);
    }

    // Show reset form
    public function showResetForm($token, Request $request)
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('token', 'email'));
    }

    // Handle password reset (POST method)
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Find user by email
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'We cannot find a user with that email address.']);
        }

        // Verify the token
        $tokenData = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token. Please request a new password reset link.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // To this (use 'status' instead of 'success'):
return redirect()->route('login')->with('status', 'Password reset successfully! You can now login with your new password.');
}
}