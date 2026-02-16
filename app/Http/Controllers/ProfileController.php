<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->get()
            ->unique(function ($address) {
                return $address->full_name . '|' . 
                       $address->phone . '|' . 
                       $address->address_line1 . '|' . 
                       $address->city . '|' . 
                       $address->state . '|' . 
                       $address->zip_code . '|' . 
                       $address->country;
            })
            ->values();
        $defaultAddress = $user->addresses()->where('is_default', true)->first();
        
        return view('profile.show', compact('user', 'addresses', 'defaultAddress'));
    }

    public function edit()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->latest()->get();
        
        return view('profile.edit', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Only update fields that individual users are allowed to modify
        $user->name = $request->name;
        
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }
        
        if ($request->filled('dob')) {
            $user->dob = Carbon::parse($request->dob)->format('Y-m-d');
        }
        
        if ($request->filled('gender')) {
            $user->gender = $request->gender;
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
                Storage::delete('public/' . $user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route('profile.show')
            ->with('success', __('messages.profile_updated'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('password_success', __('messages.password_updated'));
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required|current_password',
        ]);

        $user->email = $request->email;
        $user->email_verified_at = null; // Require email verification
        $user->save();

        // You would need to implement email verification here
        // $user->sendEmailVerificationNotification();

        return back()->with('success', __('messages.email_updated'));
    }

    public function updateNewsletter(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'newsletter_opt_in' => 'required|boolean',
        ]);

        $user->newsletter_opt_in = $request->newsletter_opt_in;
        $user->save();

        $status = $request->newsletter_opt_in ? __('messages.subscribed') : __('messages.unsubscribed');
        return back()->with('success', __('messages.newsletter_updated', ['status' => $status]));
    }

    // Address Management Methods
    public function addAddress(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'address_name' => 'nullable|string|max:100',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:500',
            'address_line2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'type' => 'required|in:shipping,billing',
            'is_default' => 'boolean',
        ]);

        // If setting as default, remove default from other addresses
        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create([
            'address_name' => $request->address_name,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country ?? 'United States',
            'type' => $request->type,
            'is_default' => $request->is_default ?? false,
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.address_added'),
                'address' => $address
            ]);
        }

        return back()->with('success', __('messages.address_added'));
    }

    public function getAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->find($id);
        
        if (!$address) {
            return response()->json(['error' => __('messages.address_not_found')], 404);
        }
        
        return response()->json([
            'address' => $address,
            'success' => true
        ]);
    }

    public function updateAddress(Request $request, $id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        
        $request->validate([
            'address_name' => 'nullable|string|max:100',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:500',
            'address_line2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'type' => 'required|in:shipping,billing',
            'is_default' => 'boolean',
        ]);

        // If setting as default, remove default from other addresses
        if ($request->is_default) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
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
            'country' => $request->country ?? 'United States',
            'type' => $request->type,
            'is_default' => $request->is_default ?? $address->is_default,
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.address_updated'),
                'address' => $address
            ]);
        }

        return back()->with('success', __('messages.address_updated'));
    }

    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        
        // Don't allow deletion if it's the only address
        if ($user->addresses()->count() <= 1) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.cannot_delete_only_address')
                ], 422);
            }
            return back()->with('error', __('messages.cannot_delete_only_address'));
        }
        
        // If deleting default address, set another as default
        if ($address->is_default) {
            $newDefault = $user->addresses()->where('id', '!=', $id)->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }
        
        $address->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.address_deleted')
            ]);
        }
        
        return back()->with('success', __('messages.address_deleted'));
    }

    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        
        // Remove default from all addresses
        $user->addresses()->update(['is_default' => false]);
        
        // Set this address as default
        $address->update(['is_default' => true]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.default_address_updated')
            ]);
        }
        
        return back()->with('success', __('messages.default_address_updated'));
    }
}