@forelse($products as $product)
<tr class="hover:bg-gray-50 transition-colors duration-150" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
    <td class="py-4 px-6">
        <span class="text-sm font-mono text-gray-500">#{{ $product->id }}</span>
    </td>
    <td class="py-4 px-6">
        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
            @if($product->primaryImage)
                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                    <i class="fas fa-image text-gray-400"></i>
                </div>
            @endif
        </div>
    </td>
    <td class="py-4 px-6">
        <div>
            <h3 class="font-medium text-gray-900 mb-1 line-clamp-1">{{ $product->name }}</h3>
            <p class="text-sm text-gray-500 line-clamp-1">{{ $product->description }}</p>
        </div>
    </td>
    <td class="py-4 px-6">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
            {{ $product->category ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
            {{ $product->category->name ?? __('admin.products.table.uncategorized') }}
        </span>
    </td>
    <td class="py-4 px-6">
        <div class="text-sm">
            @php
                $minPrice = $product->variants->min('price');
                $maxPrice = $product->variants->max('price');
            @endphp
            @if($minPrice == $maxPrice)
                <span class="font-medium">${{ number_format($minPrice, 2) }}</span>
            @else
                <span class="font-medium">${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}</span>
            @endif
        </div>
    </td>
    <td class="py-4 px-6">
        @php
            $totalStock = $product->variants->sum('stock');
        @endphp
        <div class="flex items-center">
            <span class="font-medium mr-2">{{ $totalStock }}</span>
            <span class="text-sm text-gray-500">
                {{ $product->variants->count() }} {{ __('admin.products.table.variants') }}
            </span>
        </div>
    </td>
    <td class="py-4 px-6 text-center">
        @if($product->status == 'active')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <i class="fas fa-circle text-green-500 mr-1 text-[8px]"></i>
                {{ __('admin.products.table.status_options.active') }}
            </span>
        @elseif($product->status == 'inactive')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                <i class="fas fa-circle text-red-500 mr-1 text-[8px]"></i>
                {{ __('admin.products.table.status_options.inactive') }}
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                <i class="fas fa-circle text-gray-500 mr-1 text-[8px]"></i>
                {{ __('admin.products.table.status_options.draft') }}
            </span>
        @endif
    </td>
    <td class="py-4 px-6 text-right">
        <div class="flex items-center justify-end gap-2">
            <button onclick="ProductModal.openEdit({{ $product->id }})"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 px-3 py-1.5 rounded-lg hover:bg-blue-50 transition-colors duration-200"
                title="{{ __('admin.products.table.edit') }}">
                <i class="fas fa-edit text-sm"></i>
                <span class="text-sm font-medium">{{ __('admin.products.table.edit') }}</span>
            </button>
            <button onclick="DeleteModal.open('product', {{ $product->id }}, '{{ $product->name }}')"
                class="inline-flex items-center gap-2 text-red-600 hover:text-red-800 px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors duration-200"
                title="{{ __('admin.products.table.delete') }}">
                <i class="fas fa-trash text-sm"></i>
                <span class="text-sm font-medium">{{ __('admin.products.table.delete') }}</span>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="py-12 px-6 text-center">
        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
            <i class="fas fa-box-open text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-xl font-medium text-gray-900 mb-2">
            {{ __('admin.products.empty.title') }}
        </h3>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">
            {{ __('admin.products.empty.message') }}
        </p>
        <button onclick="ProductModal.openAdd()"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-3 rounded-xl font-medium transition-all duration-300 shadow-md hover:shadow-lg">
            <i class="fas fa-plus"></i>
            {{ __('admin.products.empty.add_first') }}
        </button>
    </td>
</tr>
@endforelse