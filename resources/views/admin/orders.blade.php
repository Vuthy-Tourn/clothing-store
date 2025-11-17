@extends('admin.layout')

@section('content')
    <div class="mb-8" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-white mb-2  ">Order Management</h1>
        <p class="text-white text-lg">Manage and track customer orders</p>
    </div>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-Ocean mt-1">{{ $orders->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-Ocean text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $orders->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Completed</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $orders->where('status', 'completed')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Cancelled</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $orders->where('status', 'cancelled')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-Pearl border border-Silk rounded-xl shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="300">
        <div class="p-6 border-b border-Silk flex items-center justify-between">
            <h2 class="text-xl font-bold text-Ocean  ">All Orders</h2>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" placeholder="Search orders..." 
                           class="border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-Ocean text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-Wave text-sm"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-Lace border-b border-Silk">
                    <tr>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">#</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Order ID</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Customer</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Total Amount</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Status</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Order Date</th>
                        <th class="py-4 px-6 text-right text-Ocean font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-Silk">
                    @forelse ($orders as $index => $order)
                        <tr class="hover:bg-Lace/50 transition-colors" data-aos="fade-in" data-aos-delay="{{ $index * 50 }}">
                            <td class="py-4 px-6 text-Wave font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <span class="font-mono font-semibold text-Ocean">{{ $order->order_id }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-semibold text-Ocean">{{ $order->user->name ?? 'Guest' }}</p>
                                    <p class="text-Wave text-sm mt-1">{{ $order->user->email ?? 'No email' }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-Ocean">₹{{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                       ($order->status === 'completed' ? 'bg-green-100 text-green-700' :
                                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600')) }}">
                                    <span class="w-2 h-2 rounded-full 
                                        {{ $order->status === 'pending' ? 'bg-yellow-500' :
                                           ($order->status === 'completed' ? 'bg-green-500' :
                                           ($order->status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500')) }} mr-2"></span>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-Wave text-sm">
                                <div>{{ $order->created_at->format('d M Y') }}</div>
                                <div class="text-Wave">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.orders.invoice', ['order' => $order->id]) }}"
                                   class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition-colors">
                                    <i class="fas fa-file-invoice mr-2"></i> Invoice
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="w-24 h-24 rounded-full bg-Lace flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-shopping-bag text-Ocean text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-Ocean mb-2">No Orders Found</h3>
                                <p class="text-Wave">Orders will appear here when customers make purchases</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->count() > 0)
        <div class="p-4 border-t border-Silk bg-Lace">
            <div class="flex items-center justify-between text-sm text-Wave">
                <span>Showing {{ $orders->count() }} orders</span>
                <div class="flex items-center space-x-4">
                    <button class="hover:text-Ocean transition-colors flex items-center">
                        <i class="fas fa-chevron-left mr-1"></i> Previous
                    </button>
                    <span>Page 1 of 1</span>
                    <button class="hover:text-Ocean transition-colors flex items-center">
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection