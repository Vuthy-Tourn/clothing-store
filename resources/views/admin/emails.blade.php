@extends('admin.layout')

@section('content')
    <div class="mb-8" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-white mb-2 fashion-font">Email Subscribers</h1>
        <p class="text-white text-lg">Send updates, offers, or news to all opted-in users</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Total Subscribers</p>
                    <p class="text-2xl font-bold text-Ocean mt-1">{{ $emails->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center">
                    <i class="fas fa-users text-Ocean text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Active Campaigns</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">12</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Open Rate</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">68%</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-envelope-open text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Newsletter Section -->
    <div class="bg-Pearl border border-Silk rounded-xl shadow-sm mb-8" data-aos="fade-up" data-aos-delay="250">
        <div class="p-6 border-b border-Silk">
            <h2 class="text-xl font-bold text-Ocean fashion-font flex items-center">
                <i class="fas fa-paper-plane mr-2 text-Ocean"></i> Send Newsletter
            </h2>
        </div>

        <form action="{{ route('admin.emails.send') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Email Subject</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors"
                            placeholder="Enter email subject...">
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Preview Text</label>
                        <input type="text" name="preview_text" value="{{ old('preview_text') }}"
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors"
                            placeholder="Brief preview text...">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Email Message</label>
                        <textarea name="message" rows="5" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors resize-none"
                            placeholder="Write your newsletter content...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 pt-6 border-t border-Silk">
                <button type="submit"
                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-8 py-3 rounded-lg font-medium transition-colors flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i> Send to All Subscribers
                </button>
            </div>
        </form>
    </div>

    <!-- Subscribers List -->
    <div class="bg-Pearl border border-Silk rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="300">
        <div class="p-6 border-b border-Silk flex items-center justify-between">
            <h2 class="text-xl font-bold text-Ocean fashion-font flex items-center">
                <i class="fas fa-users mr-2 text-Ocean"></i> Subscribers List
                <span class="ml-2 bg-Ocean text-Pearl text-sm px-2 py-1 rounded-full">{{ $emails->count() }}</span>
            </h2>
            
            <div class="flex items-center space-x-3">
                <button class="bg-Silk text-Ocean hover:bg-Silk/80 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-download mr-2"></i> Export
                </button>
            </div>
        </div>

        <div class="divide-y divide-Silk">
            @forelse ($emails as $email)
            <div class="p-6 flex items-center justify-between hover:bg-Lace/50 transition-colors group" 
                 data-aos="fade-in" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center group-hover:bg-Ocean/20 transition-colors">
                        <i class="fas fa-envelope text-Ocean"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-Ocean">{{ $email->email }}</p>
                        <div class="flex items-center space-x-4 mt-1">
                            <span class="text-Wave text-sm flex items-center">
                                <i class="fas fa-calendar mr-1 text-xs"></i>
                                Subscribed {{ $email->created_at->diffForHumans() }}
                            </span>
                            <span class="text-Wave text-sm flex items-center">
                                <i class="fas fa-clock mr-1 text-xs"></i>
                                {{ $email->created_at->format('M j, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button class="bg-Silk text-Ocean hover:bg-Silk/80 w-10 h-10 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-envelope-open-text"></i>
                    </button>
                    <form action="{{ route('admin.emails.destroy', $email) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-100 text-red-600 hover:bg-red-200 w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
                                onclick="return confirm('Are you sure you want to remove this subscriber?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-24 h-24 rounded-full bg-Lace flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope-open text-Ocean text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-Ocean mb-2">No Subscribers Yet</h3>
                <p class="text-Wave mb-4">Subscribers will appear here when they sign up for your newsletter</p>
                <div class="bg-Lace border border-Silk rounded-lg p-4 max-w-md mx-auto">
                    <p class="text-Wave text-sm">Share your newsletter signup link to start building your audience</p>
                </div>
            </div>
            @endforelse
        </div>

        @if($emails->count() > 0)
        <div class="p-4 border-t border-Silk bg-Lace">
            <div class="flex items-center justify-between text-sm text-Wave">
                <span>Showing {{ $emails->count() }} subscribers</span>
                <div class="flex items-center space-x-4">
                    <button class="hover:text-Ocean transition-colors">Previous</button>
                    <span>Page 1 of 1</span>
                    <button class="hover:text-Ocean transition-colors">Next</button>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection