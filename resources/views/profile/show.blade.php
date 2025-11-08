@extends('layouts.front')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 mt-10">
    <div class="max-w-5xl mx-auto">
        <!-- Profile Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header Section -->
            <div class="relative bg-gray-900 h-48 md:h-56">
                <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                        <div class="flex items-end space-x-4">
                            <div class="relative -mb-12 md:-mb-16">
                                <div
                                    class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden">
                                    @if ($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                            alt="Profile Picture" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-800 rounded-full flex items-center justify-center">
                                            <span class="text-3xl font-bold text-white">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-28 md:ml-36">
                                <h1 class="text-2xl md:text-3xl font-bold">{{ $user->name }}</h1>
                                <p class="text-gray-200">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 md:mb-4">
                            <a href="{{ route('profile.edit') }}"
                                class="inline-flex items-center gap-2 bg-white bg-opacity-10 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-opacity-20 transition-all duration-200 border border-white border-opacity-30">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="bg-white p-6 md:p-10 pt-20 md:pt-24">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Information -->
                    <div class="p-6 rounded-2xl border border-gray-200 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Full Name</p>
                                <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email Address</p>
                                <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Phone Number</p>
                                <p class="text-gray-900 font-medium">{{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="p-6 rounded-2xl border border-gray-200 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Details</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Address</p>
                                <p class="text-gray-900 font-medium">{{ $user->address ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date of Birth</p>
                                <p class="text-gray-900 font-medium">
                                    {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : 'Not provided' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Gender</p>
                                <p class="text-gray-900 font-medium capitalize">
                                    {{ $user->gender ?? 'Not specified' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
