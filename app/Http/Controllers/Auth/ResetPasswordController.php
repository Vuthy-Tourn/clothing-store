<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

   public function reset(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ], [
        'email.required' => __('messages.email_required'),
        'email.email' => __('messages.email_valid'),
        'password.required' => __('messages.password_required'),
        'password.min' => __('messages.password_min'),
        'password.confirmed' => __('messages.password_confirmed'),
    ]);

    // Find the password reset record
    $resetRecord = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$resetRecord) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.invalid_reset_token')
            ], 400);
        }
        return back()->withErrors(['email' => __('messages.invalid_reset_token')])->withInput();
    }

    // Check if token is expired
    $createdAt = Carbon::parse($resetRecord->created_at);
    if ($createdAt->diffInMinutes(Carbon::now()) > 60) {
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.reset_link_expired')
            ], 400);
        }
        return back()->withErrors(['email' => __('messages.reset_link_expired')])->withInput();
    }

    // Verify the token
    if (!Hash::check($request->token, $resetRecord->token)) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.invalid_reset_token')
            ], 400);
        }
        return back()->withErrors(['email' => __('messages.invalid_reset_token')])->withInput();
    }

    // Find the user
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.user_not_found')
            ], 400);
        }
        return back()->withErrors(['email' => __('messages.user_not_found')])->withInput();
    }

    // Update the user's password
    $user->password = Hash::make($request->password);
    $user->save();

    // Delete the reset token
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => __('messages.password_reset_success')
        ]);
    }

    return redirect()->route('login')->with('success', __('messages.password_reset_success'));
}
}