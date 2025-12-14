@if(isset($products))
    @forelse($products as $index => $product)
        <tr class="hover:bg-gray-50 transition-colors" data-aos="fade-in"
            data-aos-delay="{{ $index * 50 }}">
            <!-- Your existing table row code -->
            <td class="py-4 px-6 text-gray-600 font-medium">{{ $index + 1 }}</td>
            <td class="py-4 px-6">
                @if ($product->primaryImage)
                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                        class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                @elseif($product->images->count() > 0)
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                        class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                @else
                    <div
                        class="w-12 h-12 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                @endif
            </td>
            <td class="py-4 px-6">
                <div>
                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                    <p class="text-gray-600 text-sm mt-1 line-clamp-1">
                        {{ Str::limit($product->short_description ?: $product->description, 50) }}</p>
                </div>
            </td>
            <td class="py-4 px-6">
                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $product->category->name ?? 'N/A' }}
                </span>
            </td>
            <td class="py-4 px-6">
                @if ($product->variants->count() > 0)
                    <div class="text-sm">
                        <span
                            class="text-gray-900 font-medium">${{ number_format($product->min_price, 2) }}</span>
                        <span class="text-gray-500 mx-1">-</span>
                        <span
                            class="text-gray-900 font-medium">${{ number_format($product->max_price, 2) }}</span>
                    </div>
                @else
                    <span class="text-gray-500 text-sm">No variants</span>
                @endif
            </td>
            <td class="py-4 px-6">
                @if ($product->variants->count() > 0)
                    <div class="text-sm">
                        <span class="text-gray-900 font-medium">{{ $product->total_stock }}</span>
                        <span class="text-gray-500 text-xs ml-1">in stock</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $product->variants->count() }} variant(s)
                    </div>
                @else
                    <span class="text-gray-500 text-sm">No stock</span>
                @endif
            </td>
            <td class="py-4 px-6 text-center">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-50 text-green-700' : ($product->status === 'inactive' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-50 text-yellow-700') }}">
                    <span
                        class="w-2 h-2 rounded-full {{ $product->status === 'active' ? 'bg-green-500' : ($product->status === 'inactive' ? 'bg-gray-500' : 'bg-yellow-500') }} mr-2"></span>
                    {{ ucfirst($product->status) }}
                </span>
            </td>
            <td class="py-4 px-6 text-right">
                <div class="flex items-center justify-end space-x-2">
                    <button onclick="ProductModal.openEdit({{ $product->id }})"
                        class="btn-primary px-3 py-2 rounded-lg text-sm font-medium flex items-center">
                        <i class="fas fa-edit mr-1 text-xs"></i> Edit
                    </button>
                    <button
                        onclick="DeleteModal.open('product', {{ $product->id }}, '{{ $product->name }}')"
                        class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium flex items-center">
                        <i class="fas fa-trash mr-1 text-xs"></i> Delete
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="py-12 text-center">
                <div
                    class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No Products Found</h3>
                <p class="text-gray-600 mb-4">Get started by adding your first product</p>
                <button onclick="ProductModal.openAdd()"
                    class="btn-primary px-6 py-3 rounded-lg font-medium">
                    <i class="fas fa-plus mr-2"></i> Add First Product
                </button>
            </td>
        </tr>
    @endforelse
@else
    <tr>
        <td colspan="9" class="py-12 text-center text-gray-500">
            <i class="fas fa-spinner fa-spin mr-2"></i> Loading products...
        </td>
    </tr>
@endif