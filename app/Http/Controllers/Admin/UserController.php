<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
   public function index(Request $request)
{
    $query = User::query();
    
    // Search functionality
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('customer_id', 'like', "%{$search}%");
        });
    }
    
    // Filter by account type
    if ($request->has('account_type') && $request->account_type) {
        $query->where('account_type', $request->account_type);
    }
    
    // Filter by status
    if ($request->has('status')) {
        if ($request->status === 'active') {
            $query->where('is_active', 1);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', 0);
        }
    }
    
    // Filter by verification
    if ($request->has('verification')) {
        if ($request->verification === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($request->verification === 'unverified') {
            $query->whereNull('email_verified_at');
        }
    }
    
    // Sorting
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);
    
    $users = $query->paginate(20)->withQueryString();
    
    $accountTypes = ['customer', 'vendor', 'admin', 'staff'];
    
    // Return JSON for AJAX requests
    if ($request->ajax() || $request->has('ajax')) {
        // Calculate filtered stats
        $filteredStats = [
            'totalUsers' => $query->count(),
            'activeUsers' => $query->clone()->where('is_active', 1)->count(),
            'verifiedUsers' => $query->clone()->whereNotNull('email_verified_at')->count(),
            'newsletterUsers' => $query->clone()->where('newsletter_opt_in', 1)->count(),
        ];
        
        return response()->json([
            'users' => $users,
            'stats' => $filteredStats,
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ]);
    }
    
    return view('admin.users.index', compact('users', 'accountTypes'));
}

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $accountTypes = ['customer', 'vendor', 'admin', 'staff'];
        $genders = ['male', 'female', 'other'];
        
        return view('admin.users.create', compact('accountTypes', 'genders'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'account_type' => 'required|in:customer,vendor,admin,staff',
            'phone' => 'nullable|string|max:20|unique:users',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'nullable|boolean',
            'newsletter_opt_in' => 'nullable|boolean',
                // Address validation
        'address_type' => 'nullable|in:shipping,billing',
        'address_name' => 'nullable|string|max:100',
        'address_line1' => 'nullable|string|max:500',
        'address_line2' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'zip_code' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
        'is_default_address' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate customer ID if not provided
            $customerId = $request->customer_id ?? 'CUST-' . strtoupper(uniqid());

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_type' => $request->account_type,
                'customer_id' => $customerId,
                'phone' => $request->phone,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'is_active' => $request->boolean('is_active'),
                'newsletter_opt_in' => $request->boolean('newsletter_opt_in'),
                'email_verified_at' => $request->boolean('email_verified') ? now() : null,
                'is_verified' => $request->boolean('is_verified'),
            ];

            // Handle profile picture
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('user/profile_pictures', 'public');
                $userData['profile_picture'] = $path;
            }

            $user = User::create($userData);

            // Create default address if provided
            if ($request->filled('address_line1')) {
                UserAddress::create([
                    'user_id' => $user->id,
                    'type' => 'shipping',
                    'address_name' => 'Primary Address',
                    'full_name' => $request->name,
                    'phone' => $request->phone,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip_code,
                    'country' => $request->country ?? 'United States',
                    'is_default' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

   public function show(User $user)
{
    $allAddresses = UserAddress::where('user_id', $user->id)->get();
    
    // For unique addresses
    $uniqueAddresses = $allAddresses->unique(function ($address) {
        return strtolower(trim($address->address_line1 . $address->city . $address->state . $address->zip_code));
    })->values();

      // Count total addresses (including duplicates)
    $totalAddressCount = $allAddresses->count();
    $uniqueAddressCount = $uniqueAddresses->count();
    
    $stats = [
        'orders_count' => 0,
        'total_spent' => 0,
        'address_count' => $allAddresses->count(),
        'unique_address_count' => $uniqueAddresses->count(),
        'account_age' => $user->created_at->diffForHumans(),
    ];
    
    return view('admin.users.show', compact('user', 'allAddresses', 'uniqueAddresses', 'uniqueAddressCount','totalAddressCount','stats'));
}

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $accountTypes = ['customer', 'vendor', 'admin', 'staff'];
        $genders = ['male', 'female', 'other'];
        $primaryAddress = UserAddress::where('user_id', $user->id)
            ->where('type', 'shipping')
            ->where('is_default', true)
            ->first();
        
        return view('admin.users.edit', compact('user', 'accountTypes', 'genders', 'primaryAddress'));
    }

public function update(Request $request, User $user)
{
    Log::info('Update request data:', $request->all());
    Log::info('Current user data:', $user->toArray());

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'account_type' => 'required|in:customer,vendor,admin,staff',
        'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
        'dob' => 'nullable|date|before:today',
        'gender' => 'nullable|in:male,female,other',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'password' => 'nullable|string|min:8|confirmed',
        'loyalty_points' => 'nullable|integer|min:0',
        // Address validation
        'address_type' => 'nullable|in:shipping,billing',
        'address_name' => 'nullable|string|max:100',
        'address_line1' => 'nullable|string|max:500',
        'address_line2' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'zip_code' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
        'is_default_address' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed:', $validator->errors()->toArray());
        
        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        DB::beginTransaction();

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'account_type' => $request->account_type,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'loyalty_points' => $request->loyalty_points ?? $user->loyalty_points,
        ];

        // Handle checkbox fields using boolean method
        $updateData['is_active'] = $request->boolean('is_active');
        $updateData['newsletter_opt_in'] = $request->boolean('newsletter_opt_in');
        $updateData['is_verified'] = $request->boolean('is_verified');

        Log::info('Update data prepared:', $updateData);

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
            Log::info('Password will be updated');
        }

        // Update email verification
        $updateData['email_verified_at'] = $request->boolean('email_verified') ? now() : null;
        Log::info('Email verification status: ' . ($updateData['email_verified_at'] ? 'verified' : 'unverified'));

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            Log::info('Profile picture file detected');
            // Delete old profile picture
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
                Log::info('Old profile picture deleted');
            }
            
            $path = $request->file('profile_picture')->store('user/profile_pictures', 'public');
            $updateData['profile_picture'] = $path;
            Log::info('New profile picture stored at: ' . $path);
        }

        // Handle profile picture removal
        if ($request->boolean('remove_profile_picture') && $user->profile_picture) {
            Log::info('Remove profile picture requested');
            if (Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
                Log::info('Profile picture file deleted from storage');
            }
            $updateData['profile_picture'] = null;
        }

        Log::info('Final update data before save:', $updateData);
        
        // Update the user
        $updated = $user->update($updateData);
        
        Log::info('Update result:', ['success' => $updated, 'updated_count' => $updated]);
        
        if (!$updated) {
            Log::error('User update failed');
            throw new \Exception('Failed to update user record');
        }

        // Refresh the user model to get updated data
        $user->refresh();
        Log::info('User data after refresh:', $user->toArray());

        // Update or create primary address
        if ($request->filled('address_line1')) {
            Log::info('Address data detected, calling updateOrCreateAddress');
            $this->updateOrCreateAddress($user->id, $request);
        }

        DB::commit();
        Log::info('Transaction committed successfully');

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'user' => $user->fresh()
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Update failed with error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Failed to update user: ' . $e->getMessage())
            ->withInput();
    }
}

   /**
     * Remove the specified user.
     */
     public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            // Delete profile picture
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Delete user addresses
            UserAddress::where('user_id', $user->id)->delete();

            // Delete the user
            $user->delete();

            DB::commit();

            // Return JSON for AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully!'
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully!');
                
        } catch (\Exception $e) {
            // DB::rollBack();
            
            // if (request()->ajax() || request()->wantsJson()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Failed to delete user: ' . $e->getMessage()
            //     ], 500);
            // }
            
            // return redirect()->back()
            //     ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status (AJAX)
     */
    public function toggleStatus(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update(['is_active' => $request->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully!',
                'is_active' => $user->is_active,
                'status_text' => $user->is_active ? 'Active' : 'Inactive',
                'status_class' => $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status.'
            ], 500);
        }
    }

    /**
     * Bulk actions for users (AJAX)
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $userIds = $request->users;
            $action = $request->action;

            switch ($action) {
                case 'activate':
                    User::whereIn('id', $userIds)->update(['is_active' => true]);
                    $message = count($userIds) . ' user(s) activated successfully!';
                    break;
                    
                case 'deactivate':
                    User::whereIn('id', $userIds)->update(['is_active' => false]);
                    $message = count($userIds) . ' user(s) deactivated successfully!';
                    break;
                    
                case 'delete':
                    // Delete profile pictures and addresses first
                    $users = User::whereIn('id', $userIds)->get();
                    foreach ($users as $user) {
                        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                            Storage::disk('public')->delete($user->profile_picture);
                        }
                        UserAddress::where('user_id', $user->id)->delete();
                    }
                    User::whereIn('id', $userIds)->delete();
                    $message = count($userIds) . ' user(s) deleted successfully!';
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

/**
 * Helper method to update or create address
 */
private function updateOrCreateAddress($userId, $request)
{
    Log::info('updateOrCreateAddress called with data:', $request->only([
        'address_type', 'address_name', 'address_line1', 'city', 'state', 'zip_code', 'country'
    ]));
    
    if (!$request->filled('address_line1')) {
        Log::info('No address line1 provided, skipping address update');
        return;
    }

    $addressData = [
        'user_id' => $userId,
        'type' => $request->address_type ?? 'shipping',
        'address_name' => $request->address_name ?? 'Primary Address',
        'full_name' => $request->name,
        'phone' => $request->phone,
        'address_line1' => $request->address_line1,
        'address_line2' => $request->address_line2,
        'city' => $request->city,
        'state' => $request->state,
        'zip_code' => $request->zip_code,
        'country' => $request->country ?? 'United States',
        'is_default' => $request->has('is_default_address'),
    ];

    Log::info('Address data prepared:', $addressData);

    // If setting as default, remove default from other addresses of same type
    if ($addressData['is_default']) {
        UserAddress::where('user_id', $userId)
            ->where('type', $addressData['type'])
            ->where('is_default', true)
            ->update(['is_default' => false]);
        Log::info('Cleared existing default addresses of type: ' . $addressData['type']);
    }

    // Try to find any existing address to update, not just default ones
    $existingAddress = UserAddress::where('user_id', $userId)
        ->where('type', $addressData['type'])
        ->first();

    if ($existingAddress) {
        Log::info('Updating existing address ID: ' . $existingAddress->id);
        $existingAddress->update($addressData);
        Log::info('Address updated successfully');
    } else {
        Log::info('Creating new address');
        UserAddress::create($addressData);
        Log::info('New address created successfully');
    }
}   

    /**
 * Get edit form for modal.
 */
public function editForm(User $user)
{
    $accountTypes = ['customer', 'vendor', 'admin', 'staff'];
    $genders = ['male', 'female', 'other'];
    $primaryAddress = UserAddress::where('user_id', $user->id)
        ->where('type', 'shipping')
        ->where('is_default', true)
        ->first();
    
    return view('admin.users.edit-form', compact('user', 'accountTypes', 'genders', 'primaryAddress'));
}
}