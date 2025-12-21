<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Show admin profile with all info
     */
    public function show()
    {
        $user = Auth::user();
        
        // Only allow admin access
        if (!in_array($user->account_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access.');
        }
        
        // Calculate age if DOB exists
        if ($user->dob) {
            $user->age = \Carbon\Carbon::parse($user->dob)->age;
        }
        
        // Get user addresses
        $addresses = UserAddress::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get primary (default) shipping address
    $primaryAddress = UserAddress::where('user_id', $user->id)
        ->where('type', 'shipping')
        ->where('is_default', true)
        ->first();
            
        // Calculate profile completeness for admin
        $completeness = $this->calculateAdminProfileCompleteness($user);
        
        // Get admin-specific stats
        $stats = $this->getAdminStats($user);
        
        // Get recent admin activities
        $activities = $this->getAdminActivities($user);
        
        return view('admin.profile.show', compact('user', 'addresses', 'primaryAddress', 'completeness', 'stats', 'activities'));
    }

    /**
     * Update admin profile (AJAX)
     */
  public function update(Request $request)
{
    $user = Auth::user();

    if (!in_array($user->account_type, ['admin', 'staff'])) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'dob' => 'nullable|date|before:today',
        'gender' => 'nullable|in:male,female,other',
        'newsletter_opt_in' => 'sometimes|accepted',
        // Address validation
        'address_line1' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'zip_code' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Update user profile data
    $updateData = [
        'name' => $request->name,
        'phone' => $request->phone,
        'dob' => $request->dob,
        'gender' => $request->gender,
        'newsletter_opt_in' => $request->boolean('newsletter_opt_in'),
    ];

    // Handle profile picture
    if ($request->hasFile('profile_picture')) {
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        $updateData['profile_picture'] = $request->file('profile_picture')->store('admin/profile_pictures', 'public');
    }

    if ($request->has('remove_profile_picture')) {
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        $updateData['profile_picture'] = null;
    }

    User::where('id', $user->id)->update($updateData);

    // Handle address update
    if ($request->filled('address_line1')) {
        $this->updateOrCreateAddress($user->id, $request);
    }

    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully',
        'user' => User::find($user->id),
        'address' => $this->getPrimaryAddress($user->id)
    ]);
}

private function updateOrCreateAddress($userId, $request)
{
    // Check if user has a default shipping address
    $address = UserAddress::where('user_id', $userId)
        ->where('type', 'shipping')
        ->where('is_default', true)
        ->first();

    $addressData = [
        'user_id' => $userId,
        'type' => 'shipping',
        'address_name' => 'Primary Address',
        'full_name' => $request->name ?? Auth::user()->name,
        'phone' => $request->phone ?? Auth::user()->phone,
        'address_line1' => $request->address_line1,
        'address_line2' => $request->address_line2,
        'city' => $request->city,
        'state' => $request->state,
        'zip_code' => $request->zip_code,
        'country' => $request->country ?? 'United States',
        'is_default' => true,
    ];

    if ($address) {
        $address->update($addressData);
    } else {
        UserAddress::create($addressData);
    }
}

private function getPrimaryAddress($userId)
{
    return UserAddress::where('user_id', $userId)
        ->where('type', 'shipping')
        ->where('is_default', true)
        ->first();
}


    /**
     * Update admin password (AJAX)
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        // Only allow admin access
        if (!in_array($user->account_type, ['admin', 'staff'])) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.different' => 'New password must be different from current password.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update password using update() method
            User::where('id', $user->id)->update([
                'password' => Hash::make($request->password)
            ]);

            // Log admin password change
            Log::channel('admin')->warning('Admin password changed', [
                'admin_id' => $user->id,
                'admin_name' => $user->name,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Password update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password. Please try again.'
            ], 500);
        }
    }

    /**
     * Toggle newsletter subscription (AJAX)
     */
    public function toggleNewsletter(Request $request)
    {
        $user = Auth::user();
        
        // Only allow admin access
        if (!in_array($user->account_type, ['admin', 'staff'])) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'newsletter_opt_in' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            User::where('id', $user->id)->update([
                'newsletter_opt_in' => $request->newsletter_opt_in
            ]);

            $status = $request->newsletter_opt_in ? 'subscribed to' : 'unsubscribed from';
            Log::channel('admin')->info("Admin newsletter {$status}");

            return response()->json([
                'success' => true,
                'message' => 'Newsletter preference updated!',
                'newsletter_opt_in' => $request->newsletter_opt_in
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter toggle failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update newsletter preference.'
            ], 500);
        }
    }

    /**
     * Save user address (AJAX)
     */
    public function saveAddress(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->account_type, ['admin', 'staff'])) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:shipping,billing',
            'address_name' => 'nullable|string|max:100',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:500',
            'address_line2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // If setting as default, remove default from other addresses of same type
            if ($request->is_default) {
                UserAddress::where('user_id', $user->id)
                    ->where('type', $request->type)
                    ->update(['is_default' => false]);
            }

            $address = UserAddress::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'address_name' => $request->address_name,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country ?? 'United States',
                'is_default' => $request->is_default ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Address saved successfully!',
                'address' => $address,
            ]);
        } catch (\Exception $e) {
            Log::error('Address save failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save address. Please try again.'
            ], 500);
        }
    }

    /**
     * Update address (AJAX)
     */
    public function updateAddress(Request $request, $id)
    {
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'address_name' => 'nullable|string|max:100',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:500',
            'address_line2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // If setting as default, remove default from other addresses of same type
            if ($request->is_default && !$address->is_default) {
                UserAddress::where('user_id', $user->id)
                    ->where('type', $address->type)
                    ->where('id', '!=', $id)
                    ->update(['is_default' => false]);
            }

            $address->update([
                'address_name' => $request->address_name,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country ?? $address->country,
                'is_default' => $request->is_default ?? $address->is_default,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully!',
                'address' => $address,
            ]);
        } catch (\Exception $e) {
            Log::error('Address update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address.'
            ], 500);
        }
    }

    /**
     * Delete address (AJAX)
     */
    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

        try {
            $address->delete();
            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Address delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address.'
            ], 500);
        }
    }

    /**
     * Set default address (AJAX)
     */
    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

        try {
            // Remove default from other addresses of same type
            UserAddress::where('user_id', $user->id)
                ->where('type', $address->type)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);

            $address->is_default = true;
            $address->save();

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Set default address failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to set default address.'
            ], 500);
        }
    }

    /**
     * Helper Methods
     */
    private function calculateAdminProfileCompleteness($user)
    {
        $fields = [
            'name' => $user->name ? 1 : 0,
            'email' => $user->email ? 1 : 0,
            'phone' => $user->phone ? 1 : 0,
            'profile_picture' => $user->profile_picture ? 1 : 0,
            'dob' => $user->dob ? 1 : 0,
            'gender' => $user->gender ? 1 : 0,
            'address' => $user->address ? 1 : 0,
        ];

        $filled = array_sum($fields);
        $total = count($fields);

        return [
            'percentage' => round(($filled / $total) * 100),
            'filled_fields' => $filled,
            'total_fields' => $total,
            'details' => $fields,
        ];
    }

    private function getAdminStats($user)
    {
        $stats = [
            'account_age' => $user->created_at->diffInDays(now()),
            'last_login' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never',
            'account_type' => ucfirst($user->account_type),
            'user_type' => $user->user_type,
            'status' => $user->is_active ? 'Active' : 'Inactive',
            'verification' => $user->is_verified ? 'Verified' : 'Not Verified',
            'email_verified' => $user->email_verified_at ? 'Yes' : 'No',
            'loyalty_points' => $user->loyalty_points,
            'newsletter_status' => $user->newsletter_opt_in ? 'Subscribed' : 'Not Subscribed',
        ];

        if ($user->account_type === 'admin') {
            $stats['role'] = 'Administrator';
        } elseif ($user->account_type === 'staff') {
            $stats['role'] = 'Staff Member';
        }

        // Calculate days until birthday if DOB exists
        if ($user->dob) {
            $today = now();
            $birthday = \Carbon\Carbon::parse($user->dob)->year($today->year);
            
            if ($birthday->lt($today)) {
                $birthday->addYear();
            }
            
            $stats['days_until_birthday'] = $today->diffInDays($birthday);
        }

        return $stats;
    }

    private function getAdminActivities($user)
    {
        // Sample activities - you can replace with actual log data
        $activities = [
            [
                'action' => 'Profile Updated',
                'description' => 'Updated personal information',
                'time' => now()->subHours(2)->diffForHumans(),
                'ip' => request()->ip(),
                'color' => 'bg-blue-500',
                'icon' => 'fas fa-user-edit'
            ],
            [
                'action' => 'Dashboard Access',
                'description' => 'Accessed admin dashboard',
                'time' => now()->subDays(1)->diffForHumans(),
                'ip' => request()->ip(),
                'color' => 'bg-purple-500',
                'icon' => 'fas fa-chart-pie'
            ],
            [
                'action' => 'System Login',
                'description' => 'Logged into admin panel',
                'time' => now()->subDays(2)->diffForHumans(),
                'ip' => request()->ip(),
                'color' => 'bg-green-500',
                'icon' => 'fas fa-sign-in-alt'
            ]
        ];

        return array_slice($activities, 0, 5);
    }
}