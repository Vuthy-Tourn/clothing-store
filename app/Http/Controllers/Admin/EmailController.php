<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminBulkEmail;
use Illuminate\Support\Facades\DB;

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
        
        // Get statistics
        $totalActive = NewsletterSubscription::active()->count();
        $totalInactive = NewsletterSubscription::where('is_subscribed', false)->count();
        $totalSubscribers = $totalActive + $totalInactive;
        $last30DaysGrowth = NewsletterSubscription::where('created_at', '>=', now()->subDays(30))
            ->where('is_subscribed', true)
            ->count();
        
        return view('admin.emails.index', compact('subscribers', 'totalActive', 'totalInactive', 'totalSubscribers', 'last30DaysGrowth'));
    }

    /**
     * Send bulk email to all active subscribers
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'preview_text' => 'nullable|string|max:255'
        ]);

        try {
            // Get only active subscribers' emails
            $activeSubscribers = NewsletterSubscription::active()
                ->pluck('email')
                ->toArray();

            if (empty($activeSubscribers)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No active subscribers found.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'No active subscribers found.');
            }

            // Queue emails for better performance
            foreach ($activeSubscribers as $email) {
                Mail::to($email)->queue(new AdminBulkEmail(
                    $request->subject,
                    $request->message,
                    $request->preview_text
                ));
            }

            // Update last sent time for subscribers
            NewsletterSubscription::whereIn('email', $activeSubscribers)
                ->update(['last_sent_at' => now()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Newsletter sent successfully to ' . count($activeSubscribers) . ' subscribers!',
                    'count' => count($activeSubscribers)
                ]);
            }

            return redirect()->back()->with('success', 'Email sent to ' . count($activeSubscribers) . ' active subscribers.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send newsletter: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to send newsletter: ' . $e->getMessage());
        }
    }

    /**
     * Delete a subscriber (soft delete)
     */
    public function destroy(Request $request, NewsletterSubscription $email)
    {
        try {
            DB::beginTransaction();
            
            $email->update([
                'is_subscribed' => false,
                'unsubscribed_at' => now()
            ]);
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subscriber removed successfully.',
                    'email' => $email->email,
                    'id' => $email->id
                ]);
            }

            return redirect()->back()->with('success', 'Subscriber removed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove subscriber.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to remove subscriber.');
        }
    }

    /**
     * Send test email
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'preview_text' => 'nullable|string'
        ]);

        try {
            Mail::to($request->email)->send(new AdminBulkEmail(
                $request->subject . ' [TEST]',
                $request->message,
                $request->preview_text
            ));

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $request->email
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export subscribers list
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $subscribers = NewsletterSubscription::active()
            ->with('user')
            ->select(['email', 'subscribed_at', 'created_at', 'user_id'])
            ->get();

        $filename = 'subscribers_' . date('Y-m-d_H-i-s') . '.' . $format;
        
        $headers = [
            'Content-Type' => $format === 'csv' ? 'text/csv' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        if ($format === 'csv') {
            return $this->exportCsv($subscribers, $filename);
        } else {
            return $this->exportExcel($subscribers, $filename);
        }
    }

    private function exportCsv($subscribers, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Email', 'Name', 'Subscribed Date', 'Status', 'User ID']);
            
            // Add data rows
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->user->name ?? 'N/A',
                    $subscriber->subscribed_at->format('Y-m-d H:i:s'),
                    'Active',
                    $subscriber->user_id ?? 'Guest'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportExcel($subscribers, $filename)
    {
        // For Excel export, you would typically use a package like Laravel Excel
        // For simplicity, we'll just return CSV here
        return $this->exportCsv($subscribers, str_replace('.xlsx', '.csv', $filename));
    }

    /**
     * Bulk delete subscribers
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:newsletter_subscriptions,id'
        ]);

        try {
            DB::beginTransaction();

            $count = NewsletterSubscription::whereIn('id', $request->ids)
                ->update([
                    'is_subscribed' => false,
                    'unsubscribed_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $count . ' subscriber(s) removed successfully.',
                'count' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove subscribers.'
            ], 500);
        }
    }

    /**
     * Add new subscriber
     */
    public function addSubscriber(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
            'name' => 'nullable|string|max:255',
            'send_welcome' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $subscriber = NewsletterSubscription::create([
                'email' => $request->email,
                'is_subscribed' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null
            ]);

            // Optionally send welcome email
            if ($request->boolean('send_welcome')) {
                Mail::to($request->email)->queue(new AdminBulkEmail(
                    'Welcome to Our Newsletter!',
                    'Thank you for subscribing to our newsletter. You will receive updates about our latest products, promotions, and news.',
                    'Welcome to our newsletter family!'
                ));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Subscriber added successfully.',
                'subscriber' => $subscriber
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add subscriber: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reactivate subscriber
     */
    public function reactivate(Request $request, NewsletterSubscription $email)
    {
        try {
            $email->update([
                'is_subscribed' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscriber reactivated successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reactivate subscriber.'
            ], 500);
        }
    }

    /**
     * Get subscriber statistics
     */
    public function getStatistics()
    {
        $totalSubscribers = NewsletterSubscription::count();
        $activeSubscribers = NewsletterSubscription::active()->count();
        $inactiveSubscribers = $totalSubscribers - $activeSubscribers;
        
        // Last 30 days growth
        $last30Days = NewsletterSubscription::where('created_at', '>=', now()->subDays(30))
            ->where('is_subscribed', true)
            ->count();
            
        // Last 7 days growth
        $last7Days = NewsletterSubscription::where('created_at', '>=', now()->subDays(7))
            ->where('is_subscribed', true)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $totalSubscribers,
                'active' => $activeSubscribers,
                'inactive' => $inactiveSubscribers,
                'last_30_days' => $last30Days,
                'last_7_days' => $last7Days
            ]
        ]);
    }
    public function test(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'content_type' => 'required|in:draft,sample'
    ]);

    try {
        $subject = $request->content_type === 'draft' 
            ? ($request->subject ?: 'Test Email')
            : 'Test Newsletter Email';
        
        $message = $request->content_type === 'draft'
            ? ($request->message ?: 'This is a test email')
            : 'This is a sample test email to verify your newsletter system is working correctly.';

        Mail::to($request->email)->send(new AdminBulkEmail($subject, $message));

        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send test email: ' . $e->getMessage()
        ], 500);
    }
}

public function subscribe(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:newsletter_subscriptions,email',
        'name' => 'nullable|string|max:255'
    ]);

    try {
        $subscriber = NewsletterSubscription::create([
            'email' => $request->email,
            'is_subscribed' => true,
            'subscribed_at' => now()
        ]);

        if ($request->send_welcome) {
            Mail::to($request->email)->send(new AdminBulkEmail(
                'Welcome to Our Newsletter!',
                'Thank you for subscribing to our newsletter.'
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscriber added successfully!',
            'subscriber' => $subscriber
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to add subscriber: ' . $e->getMessage()
        ], 500);
    }
}


public function details(NewsletterSubscription $email)
{
    return response()->json([
        'email' => $email->email,
        'name' => $email->user->name ?? null,
        'created_at' => $email->created_at,
        'last_sent_at' => $email->last_sent_at,
        'email_count' => $email->sent_count ?? 0,
        'open_rate' => $email->open_rate ?? 0,
        'click_rate' => $email->click_rate ?? 0
    ]);
}
}