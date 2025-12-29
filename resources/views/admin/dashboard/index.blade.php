{{-- Complete Dashboard Layout (Fixed) --}}
{{-- File: resources/views/admin/dashboard/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Dashboard - Moeww Admin')

@section('content')
<!-- Header -->
@include('admin.dashboard.partials.header')

<!-- Stats Cards -->
@include('admin.dashboard.partials.stats-card')

<!-- Main Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Sales Chart (2 columns) -->
    @include('admin.dashboard.partials.charts')
    
    <!-- Store Health (1 column) -->
    @include('admin.dashboard.partials.store-health')
</div>

<!-- Secondary Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Products Chart -->
    @include('admin.dashboard.partials.top-products')

    <!-- Order Status Distribution -->
    @include('admin.dashboard.partials.order-status')
</div>

<!-- Performance & Recent Activity Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Performance Metrics (1 column) -->
    @include('admin.dashboard.partials.performance-metrics')

    <!-- Recent Orders (2 columns) -->
    <div class="lg:col-span-2 card p-6" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">{{ __('admin.recent_activity.recent_orders') }}</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-Ocean hover:text-blue-700 font-medium">
                {{ __('admin.recent_activity.view_all') }} <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">{{ __('admin.recent_activity.order') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">{{ __('admin.recent_activity.customer') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">{{ __('admin.recent_activity.amount') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">{{ __('admin.recent_activity.status') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">{{ __('admin.recent_activity.date') }}</th>
                    </tr>
                </thead>
                <tbody id="recentOrdersList">
                    <tr>
                        <td colspan="5" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <a href="{{ route('admin.products.index') }}" class="card p-6 hover:shadow-lg transition-shadow" data-aos="fade-up">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fas fa-box text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">{{ __('admin.quick_actions.products') }}</h3>
                <p class="text-sm text-gray-600">{{ __('admin.quick_actions.manage_products') }}</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.orders.index') }}" class="card p-6 hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="50">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">{{ __('admin.quick_actions.orders') }}</h3>
                <p class="text-sm text-gray-600">{{ __('admin.quick_actions.manage_all_orders') }}</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.categories.index') }}" class="card p-6 hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                <i class="fas fa-tags text-purple-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">{{ __('admin.quick_actions.categories') }}</h3>
                <p class="text-sm text-gray-600">{{ __('admin.quick_actions.manage_categories') }}</p>
            </div>
        </div>
    </a>

    <button onclick="refreshDashboard()" class="card p-6 hover:shadow-lg transition-shadow text-left" data-aos="fade-up" data-aos-delay="150">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                <i class="fas fa-sync-alt text-orange-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">{{ __('admin.quick_actions.refresh_data') }}</h3>
                <p class="text-sm text-gray-600">{{ __('admin.quick_actions.update_dashboard') }}</p>
            </div>
        </div>
    </button>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush