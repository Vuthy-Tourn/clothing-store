<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpController extends Controller
{
    public function showVerificationForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.verify-otp', compact('email'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ], [
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_valid'),
            'otp.required' => __('messages.otp_required'),
            'otp.digits' => __('messages.otp_digits'),
        ]);

        $user = User::where('email', $request->email)->where('otp_code', $request->otp)->first();

        if (!$user) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.invalid_otp')
                ], 400);
            }
            return back()->withErrors(['otp' => __('messages.invalid_otp')]);
        }

        $user->is_verified = true;
        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->save();

        Auth::login($user);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.verification_success'),
                'redirect' => url('/')
            ]);
        }

        return redirect('/')->with('success', __('messages.verification_success'));
    }

    public function resend(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.email_required')
                ], 400);
            }
            return redirect()->route('login')->withErrors(['email' => __('messages.email_required')]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.user_not_found')
                ], 404);
            }
            return redirect()->route('login')->withErrors(['email' => __('messages.user_not_found')]);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->save();

        // Send OTP email
        Mail::to($user->email)->send(new OtpMail($otp, $user));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.otp_resent')
            ]);
        }

        return redirect()->back()->with('success', __('messages.otp_resent'));
    }   
}