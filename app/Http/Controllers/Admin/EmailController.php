<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminBulkEmail;

class EmailController extends Controller
{
    /**
     * Display all active newsletter subscribers
     */
    public function index()
    {
        // Only fetch active subscribers (is_subscribed = true)
        $subscribers = NewsletterSubscription::active()
            ->with('user')
            ->latest('subscribed_at')
            ->get();
        
        return view('admin.emails.index', compact('subscribers'));
    }

    /**
     * Send bulk email to all active subscribers
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Get only active subscribers' emails
        $activeSubscribers = NewsletterSubscription::active()
            ->pluck('email')
            ->toArray();

        if (empty($activeSubscribers)) {
            return redirect()->back()->with('error', 'No active subscribers found.');
        }

        foreach ($activeSubscribers as $email) {
            Mail::to($email)->queue(new AdminBulkEmail($request->subject, $request->message));
        }

        return redirect()->back()->with('success', 'Email sent to ' . count($activeSubscribers) . ' active subscribers.');
    }

    /**
     * Delete a subscriber (soft delete or remove from list)
     */
    public function destroy(NewsletterSubscription $email)
    {
        // Option 1: Soft delete by marking as unsubscribed
        $email->update([
            'is_subscribed' => false,
            'unsubscribed_at' => now()
        ]);
        
        // Option 2: If you want to permanently delete instead:
        // $email->delete();

        return redirect()->back()->with('success', 'Subscriber removed successfully.');
    }

    /**
     * View subscriber statistics
     */
    public function statistics()
    {
        $totalSubscribers = NewsletterSubscription::count();
        $activeSubscribers = NewsletterSubscription::active()->count();
        $inactiveSubscribers = $totalSubscribers - $activeSubscribers;
        
        // Get subscription trends (last 30 days)
        $subscriptionTrends = NewsletterSubscription::selectRaw('
                DATE(subscribed_at) as date,
                COUNT(*) as new_subscriptions,
                SUM(CASE WHEN is_subscribed = false THEN 1 ELSE 0 END) as unsubscriptions
            ')
            ->where('subscribed_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.emails.statistics', compact(
            'totalSubscribers',
            'activeSubscribers',
            'inactiveSubscribers',
            'subscriptionTrends'
        ));
    }

    /**
     * View a single subscriber's details
     */
    public function show(NewsletterSubscription $email)
    {
        return view('admin.emails.show', compact('email'));
    }

    /**
     * Manually add a subscriber (for admin use)
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ]);

        NewsletterSubscription::create([
            'email' => $request->email,
            'is_subscribed' => true,
            'subscribed_at' => now(),
            'user_id' => null, // Or link to user if exists
        ]);

        return redirect()->back()->with('success', 'Subscriber added successfully.');
    }

    /**
     * Reactivate a previously unsubscribed user
     */
    public function reactivate(NewsletterSubscription $email)
    {
        $email->update([
            'is_subscribed' => true,
            'subscribed_at' => now(),
            'unsubscribed_at' => null
        ]);

        return redirect()->back()->with('success', 'Subscriber reactivated successfully.');
    }

    /**
     * Export subscribers list
     */
    public function export(Request $request)
    {
        $subscribers = NewsletterSubscription::active()
            ->select(['email', 'subscribed_at', 'user_id'])
            ->get();

        $filename = 'subscribers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Email', 'Subscribed At', 'User ID', 'Status']);
            
            // Add data rows
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->subscribed_at->format('Y-m-d H:i:s'),
                    $subscriber->user_id ?? 'Guest',
                    'Active'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}