<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Mail\CustomPasswordResetMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_valid'),
            'email.max' => __('messages.email_max'),
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => __('messages.email_not_found'),
            ]);
        }

        if (!$user->is_verified) {
            return back()->withErrors([
                'email' => __('messages.email_not_verified'),
            ]);
        }

        // Generate reset token
        $token = Str::random(64);

        // Store or update in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => bcrypt($token),
                'created_at' => Carbon::now()
            ]
        );

        // Send custom reset email
        Mail::to($user->email)->send(new CustomPasswordResetMail($token, $user));

        // Translated success message
        return back()->with('status', __('messages.email_sent'));
        
        // OR if you want to use 'success' key instead of 'status'
        // return back()->with('success', __('messages.email_sent'));
    }
}