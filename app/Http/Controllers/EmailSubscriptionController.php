<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmailSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to subscribe.');
        }

        if (!$user->is_verified) {
            return redirect()->back()->with('error', 'Your email must be verified before subscribing.');
        }

        // ✅ Only allow user's actual verified email
        if ($request->email !== $user->email) {
            return redirect()->back()->with('error', 'Invalid email submission.');
        }

        // ✅ Check if already subscribed (active subscription)
        $existingSubscription = NewsletterSubscription::where('email', $user->email)
            ->where('is_subscribed', true)
            ->first();

        if ($existingSubscription) {
            return redirect()->back()
                ->with('info', 'You are already subscribed.')
                ->with('subscribed', true);
        }

        // ✅ Check if there's a previous subscription that was unsubscribed
        $previousSubscription = NewsletterSubscription::where('email', $user->email)
            ->where('is_subscribed', false)
            ->first();

        DB::beginTransaction();
        try {
            if ($previousSubscription) {
                // Reactivate the subscription
                $previousSubscription->update([
                    'is_subscribed' => true,
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null,
                    'user_id' => $user->id
                ]);
            } else {
                // Create new subscription
                NewsletterSubscription::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'is_subscribed' => true,
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null
                ]);
            }
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Thank you for subscribing!')
                ->with('subscribed', true);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        $subscription = NewsletterSubscription::where('email', $user->email)
            ->where('is_subscribed', true)
            ->first();

        if ($subscription) {
            $subscription->update([
                'is_subscribed' => false,
                'unsubscribed_at' => now()
            ]);
            
            return redirect()->back()->with('success', 'You have been unsubscribed successfully.');
        }

        return redirect()->back()->with('error', 'No active subscription found.');
    }
}