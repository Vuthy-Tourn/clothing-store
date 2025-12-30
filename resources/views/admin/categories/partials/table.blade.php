<div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm" data-aos="fade-up" data-aos-delay="150">
    <!-- Table Header with Search and Filters -->
    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ __('admin.categories.table.all_admin_categories') }}</h2>
                <p class="text-gray-700 text-sm mt-1">{{ __('admin.categories.table.manage_organize') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleSortOrder()"
                    class="bg-white border border-gray-300 text-gray-900 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 group shadow-sm hover:shadow">
                    <i class="fas fa-sort mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                    {{ __('admin.categories.table.arrange_order') }}
                </button>
            </div>
        </div>

        <!-- Search and Filter Controls -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <!-- Search Input -->
            <div class="md:col-span-2">
                <label class="block text-gray-900 font-medium mb-2 text-sm">{{ __('admin.categories.table.search_placeholder') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-500"></i>
                    </div>
                    <input type="text" id="searchInput" placeholder="{{ __('admin.categories.table.search_placeholder') }}"
                        class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl pl-12 pr-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm placeholder:text-gray-400"
                        onkeyup="filterTable()">
                </div>
            </div>

            <!-- Gender Filter -->
            <div>
                <label class="block text-gray-900 font-medium mb-2 text-sm">{{ __('admin.categories.table.filter_gender') }}</label>
                <div class="relative">
                    <select id="genderFilter" onchange="filterTable()"
                        class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 appearance-none transition-all duration-200 shadow-sm cursor-pointer">
                        <option value="">{{ __('admin.categories.table.all_genders') }}</option>
                        <option value="men">{{ __('admin.categories.modal.gender_men') }}</option>
                        <option value="women">{{ __('admin.categories.modal.gender_women') }}</option>
                        <option value="kids">{{ __('admin.categories.modal.gender_kids') }}</option>
                        <option value="unisex">{{ __('admin.categories.modal.gender_unisex') }}</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-500"></i>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-gray-900 font-medium mb-2 text-sm">{{ __('admin.categories.table.filter_status') }}</label>
                <div class="relative">
                    <select id="statusFilter" onchange="filterTable()"
                        class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 appearance-none transition-all duration-200 shadow-sm cursor-pointer">
                        <option value="">{{ __('admin.categories.table.all_status') }}</option>
                        <option value="active">{{ __('admin.categories.modal.status_active') }}</option>
                        <option value="inactive">{{ __('admin.categories.modal.status_inactive') }}</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <!-- Products Range Filter -->
            <div class="md:col-span-2">
                <label class="block text-gray-900 font-medium mb-2 text-sm">{{ __('admin.categories.table.products_range') }}</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">{{ __('admin.categories.table.min') }}</span>
                        </div>
                        <input type="number" id="minProducts" min="0" placeholder="0"
                            class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl pl-14 pr-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm"
                            oninput="filterTable()">
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">{{ __('admin.categories.table.max') }}</span>
                        </div>
                        <input type="number" id="maxProducts" min="0" placeholder="{{ __('admin.categories.table.max') }}"
                            class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl pl-12 pr-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm"
                            oninput="filterTable()">
                    </div>
                </div>
            </div>

            <!-- Sort By and Reset -->
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <label class="block text-gray-900 font-medium mb-2 text-sm">{{ __('admin.categories.table.sort_by') }}</label>
                    <div class="relative">
                        <select id="sortFilter" onchange="sortTable()"
                            class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 appearance-none transition-all duration-200 shadow-sm cursor-pointer">
                            <option value="sort_order">{{ __('admin.categories.sort.display_order') }}</option>
                            <option value="name">{{ __('admin.categories.sort.name_az') }}</option>
                            <option value="name_desc">{{ __('admin.categories.sort.name_za') }}</option>
                            <option value="products_desc">{{ __('admin.categories.sort.most_products') }}</option>
                            <option value="products_asc">{{ __('admin.categories.sort.least_products') }}</option>
                            <option value="created_desc">{{ __('admin.categories.sort.newest_first') }}</option>
                            <option value="created_asc">{{ __('admin.categories.sort.oldest_first') }}</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </div>
                    </div>
                </div>
                <button onclick="resetFilters()"
                    class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200 group shadow-md hover:shadow-lg transform hover:-translate-y-0.5 whitespace-nowrap">
                    <i class="fas fa-redo mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                    {{ __('admin.categories.table.reset') }}
                </button>
            </div>
        </div>

        <!-- Active Filters Display -->
        <div id="filterSummary" class="mt-4 hidden">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-gray-700 text-sm font-medium">{{ __('admin.categories.table.active_filters') }}:</span>
                <div id="activeFilters" class="flex flex-wrap gap-2"></div>
                <button onclick="resetFilters()" class="text-blue-600 hover:text-blue-800 text-sm font-medium ml-2">
                    {{ __('admin.categories.table.clear_all') }}
                </button>
            </div>
        </div>
    </div>

    @if ($categories->isEmpty())
        <!-- Empty State -->
        <div class="p-16 text-center">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mx-auto mb-6 border-4 border-blue-100">
                <i class="fas fa-folder-open text-blue-300 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('admin.categories.no_admin.categories') }}</h3>
            <p class="text-gray-700 mb-6 max-w-md mx-auto">{{ __('admin.categories.no_admin.categories_desc') }}</p>
            <button onclick="CategoryModal.openAdd()"
                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3.5 rounded-xl font-medium transition-all duration-200 group text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                {{ __('admin.categories.create_first') }}
            </button>
        </div>
    @else
        <!-- Sort Order Panel (Initially Hidden) -->
        <div id="sortOrderPanel" class="p-6 border-b border-gray-100 bg-gray-50 hidden">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('admin.categories.sort_panel.title') }}</h3>
                    <p class="text-gray-700 text-sm">{{ __('admin.categories.sort_panel.subtitle') }}</p>
                </div>
                <button onclick="saveSortOrder()"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-5 py-2 rounded-lg font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.categories.sort_panel.save_order') }}
                </button>
            </div>

            <div id="sortableList" class="space-y-2">
                @foreach ($categories->sortBy('sort_order') as $category)
                    <div class="flex items-center gap-4 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl p-4 transition-all duration-200 cursor-move sortable-item shadow-sm hover:shadow"
                        data-id="{{ $category->id }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center shadow">
                            <i class="fas fa-arrows-alt text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                @if ($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                        class="w-10 h-10 rounded-lg object-cover border border-gray-200 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-folder text-gray-600"></i>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                                    <p class="text-gray-700 text-xs">{{ __('admin.categories.sort_panel.products_count', ['count' => $category->products->count()]) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-gray-700 font-medium">
                            <span class="text-sm">{{ __('admin.categories.sort_panel.position', ['position' => $category->sort_order + 1]) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- admin.categories Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">{{ __('admin.categories.table.order') }}</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">{{ __('admin.categories.table.category') }}</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">{{ __('admin.categories.table.gender') }}</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">{{ __('admin.categories.table.slug') }}</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">{{ __('admin.categories.table.description') }}</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">{{ __('admin.categories.table.status') }}</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">{{ __('admin.categories.table.products') }}</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">{{ __('admin.categories.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody" class="divide-y divide-gray-100">
                    @foreach ($categories->sortBy('sort_order') as $index => $category)
                        @php
                            $productCount = $category->products->count();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-200 category-row" 
                            data-name="{{ strtolower($category->name) }}"
                            data-description="{{ strtolower($category->description ?? '') }}"
                            data-slug="{{ strtolower($category->slug) }}"
                            data-gender="{{ $category->gender }}"
                            data-status="{{ $category->status }}"
                            data-products="{{ $productCount }}"
                            data-sort-order="{{ $category->sort_order }}"
                            data-created="{{ $category->created_at->timestamp }}"
                            data-aos="fade-in" data-aos-delay="{{ $index * 50 }}">
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-gray-800 to-gray-900 text-white text-sm font-bold shadow">
                                    {{ $category->sort_order + 1 }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if ($category->image)
                                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                            class="w-12 h-12 rounded-xl object-cover border border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-folder text-gray-600 text-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-gray-900 category-name">{{ $category->name }}</h4>
                                        <p class="text-gray-700 text-xs mt-1 category-date" data-date="{{ $category->created_at->timestamp }}">
                                            {{ $category->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="gender-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm
                                    {{ $category->gender === 'men' 
                                        ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 border border-blue-200'
                                        : ($category->gender === 'women' 
                                            ? 'bg-gradient-to-r from-pink-50 to-pink-100 text-pink-800 border border-pink-200'
                                            : ($category->gender === 'kids' 
                                                ? 'bg-gradient-to-r from-green-50 to-green-100 text-green-800 border border-green-200'
                                                : 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-800 border border-gray-200')) }}">
                                    <i class="fas {{ $category->gender === 'men' 
                                        ? 'fa-mars' 
                                        : ($category->gender === 'women' 
                                            ? 'fa-venus' 
                                            : ($category->gender === 'kids' 
                                                ? 'fa-child' 
                                                : 'fa-venus-mars')) }} mr-1.5 text-[8px]"></i>
                                    {{ ucfirst($category->gender) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <code class="text-gray-700 text-sm bg-gray-100 px-2 py-1 rounded font-mono category-slug">{{ $category->slug }}</code>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-gray-700 text-sm line-clamp-2 category-description">
                                    {{ $category->description ?? __('admin.categories.table.no_description') }}
                                </p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm 
                                    {{ $category->status === 'active' 
                                        ? 'bg-gradient-to-r from-green-50 to-green-100 text-green-800 border border-green-200' 
                                        : 'bg-gradient-to-r from-red-50 to-red-100 text-red-800 border border-red-200' }}">
                                    <i class="fas fa-circle text-[6px] mr-1.5 {{ $category->status === 'active' ? 'animate-pulse text-green-500' : 'text-red-500' }}"></i>
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <span class="text-gray-900 font-bold text-lg products-count">{{ $productCount }}</span>
                                    <span class="text-gray-700 text-sm ml-1">{{ __('admin.categories.table.products') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="CategoryModal.openEdit({{ $category->id }})"
                                        class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-700 hover:text-blue-900 border border-blue-200 flex items-center justify-center transition-all duration-200 group/edit shadow-sm hover:shadow">
                                        <i class="fas fa-edit text-sm group-hover/edit:rotate-12 transition-transform duration-300"></i>
                                    </button>
                                    <button
                                        onclick="confirmDeleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $productCount }})"
                                        class="w-10 h-10 rounded-lg bg-gradient-to-r from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 text-red-700 hover:text-red-900 border border-red-200 flex items-center justify-center transition-all duration-200 group/delete shadow-sm hover:shadow">
                                        <i class="fas fa-trash text-sm group-hover/delete:shake transition-transform duration-300"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- No Results Message (Hidden by default) -->
        <div id="noResultsMessage" class="p-16 text-center hidden">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center mx-auto mb-6 border-4 border-gray-200">
                <i class="fas fa-search text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('admin.categories.no_results.title') }}</h3>
            <p class="text-gray-700 mb-6 max-w-md mx-auto">{{ __('admin.categories.no_results.message') }}</p>
            <button onclick="resetFilters()"
                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3.5 rounded-xl font-medium transition-all duration-200 group text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-redo mr-3 group-hover:rotate-180 transition-transform duration-300"></i>
                {{ __('admin.categories.no_results.reset_filters') }}
            </button>
        </div>

        <!-- Results Count -->
        <div id="resultsCount" class="p-4 border-t border-gray-100 bg-gray-50">
            <p class="text-gray-700 text-sm">
                {{ __('admin.categories.table.showing') }} <span id="visibleCount" class="font-semibold">{{ $categories->count() }}</span> 
                {{ __('admin.categories.table.of') }} <span id="totalCount" class="font-semibold">{{ $categories->count() }}</span>
            </p>
        </div>
    @endif
</div>

<script>
    // Translation strings for JavaScript
    const translations = {
        search: "{{ __('admin.categories.filters.search', ['term' => ':term']) }}",
        gender: "{{ __('admin.categories.filters.gender', ['gender' => ':gender']) }}",
        status: "{{ __('admin.categories.filters.status', ['status' => ':status']) }}",
        products: "{{ __('admin.categories.filters.products', ['range' => ':range']) }}"
    };

    // Real-time filtering functionality
    let filterDebounce;
    
    function filterTable() {
        clearTimeout(filterDebounce);
        
        filterDebounce = setTimeout(() => {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const genderFilter = document.getElementById('genderFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const minProducts = parseInt(document.getElementById('minProducts').value) || 0;
            const maxProducts = parseInt(document.getElementById('maxProducts').value) || Infinity;
            
            const rows = document.querySelectorAll('.category-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const description = row.getAttribute('data-description') || '';
                const slug = row.getAttribute('data-slug') || '';
                const gender = row.getAttribute('data-gender') || '';
                const status = row.getAttribute('data-status') || '';
                const products = parseInt(row.getAttribute('data-products')) || 0;
                
                const matchesSearch = !searchTerm || 
                    name.includes(searchTerm) || 
                    description.includes(searchTerm) || 
                    slug.includes(searchTerm);
                
                const matchesGender = !genderFilter || gender === genderFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesProductRange = products >= minProducts && products <= maxProducts;
                
                if (matchesSearch && matchesGender && matchesStatus && matchesProductRange) {
                    row.classList.remove('hidden');
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(-10px)';
                }
            });
            
            document.getElementById('visibleCount').textContent = visibleCount;
            
            const noResultsMessage = document.getElementById('noResultsMessage');
            const tableBody = document.getElementById('categoriesTableBody');
            
            if (visibleCount === 0 && rows.length > 0) {
                noResultsMessage.classList.remove('hidden');
                tableBody.classList.add('hidden');
            } else {
                noResultsMessage.classList.add('hidden');
                tableBody.classList.remove('hidden');
            }
            
            updateFilterSummary(searchTerm, genderFilter, statusFilter, minProducts, maxProducts);
        }, 50);
    }
    
    function sortTable() {
        const sortOption = document.getElementById('sortFilter').value;
        const tbody = document.getElementById('categoriesTableBody');
        const rows = Array.from(tbody.querySelectorAll('.category-row:not(.hidden)'));
        
        rows.sort((a, b) => {
            switch (sortOption) {
                case 'name':
                    return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
                case 'name_desc':
                    return (b.getAttribute('data-name') || '').localeCompare(a.getAttribute('data-name') || '');
                case 'products_desc':
                    return (parseInt(b.getAttribute('data-products')) || 0) - (parseInt(a.getAttribute('data-products')) || 0);
                case 'products_asc':
                    return (parseInt(a.getAttribute('data-products')) || 0) - (parseInt(b.getAttribute('data-products')) || 0);
                case 'created_desc':
                    return (parseInt(b.getAttribute('data-created')) || 0) - (parseInt(a.getAttribute('data-created')) || 0);
                case 'created_asc':
                    return (parseInt(a.getAttribute('data-created')) || 0) - (parseInt(b.getAttribute('data-created')) || 0);
                default:
                    return (parseInt(a.getAttribute('data-sort-order')) || 0) - (parseInt(b.getAttribute('data-sort-order')) || 0);
            }
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }
    
    function updateFilterSummary(searchTerm, gender, status, minProducts, maxProducts) {
        const filterSummary = document.getElementById('filterSummary');
        const activeFilters = document.getElementById('activeFilters');
        
        activeFilters.innerHTML = '';
        let hasActiveFilters = false;
        const filters = [];
        
        if (searchTerm) {
            filters.push({
                text: translations.search.replace(':term', searchTerm),
                type: 'search'
            });
            hasActiveFilters = true;
        }
        
        if (gender) {
            filters.push({
                text: translations.gender.replace(':gender', gender.charAt(0).toUpperCase() + gender.slice(1)),
                type: 'gender'
            });
            hasActiveFilters = true;
        }
        
        if (status) {
            filters.push({
                text: `Status: ${status.charAt(0).toUpperCase() + status.slice(1)}`,
                type: 'status'
            });
            hasActiveFilters = true;
        }
        
        if (minProducts > 0 || maxProducts < Infinity) {
            let rangeText = 'Products: ';
            if (minProducts > 0) rangeText += `≥ ${minProducts}`;
            if (minProducts > 0 && maxProducts < Infinity) rangeText += ' - ';
            if (maxProducts < Infinity) rangeText += `≤ ${maxProducts}`;
            
            filters.push({
                text: rangeText,
                type: 'products'
            });
            hasActiveFilters = true;
        }
        
        // Display active filters
        filters.forEach(filter => {
            const filterChip = document.createElement('div');
            filterChip.className = 'inline-flex items-center gap-2 bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 px-3 py-1.5 rounded-lg text-xs font-medium border border-blue-200 shadow-sm hover:shadow transition-all duration-200 cursor-pointer';
            filterChip.innerHTML = `
                ${filter.text}
                <button onclick="removeFilter('${filter.type}')" 
                    class="w-4 h-4 rounded-full hover:bg-blue-200 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-[10px]"></i>
                </button>
            `;
            activeFilters.appendChild(filterChip);
        });
        
        // Show/hide filter summary
        if (hasActiveFilters) {
            filterSummary.classList.remove('hidden');
        } else {
            filterSummary.classList.add('hidden');
        }
    }
    
    function removeFilter(filterType) {
        switch (filterType) {
            case 'search':
                document.getElementById('searchInput').value = '';
                break;
            case 'gender':
                document.getElementById('genderFilter').value = '';
                break;
            case 'status':
                document.getElementById('statusFilter').value = '';
                break;
            case 'products':
                document.getElementById('minProducts').value = '';
                document.getElementById('maxProducts').value = '';
                break;
        }
        filterTable();
    }
    
    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('genderFilter').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('sortFilter').value = 'sort_order';
        document.getElementById('minProducts').value = '';
        document.getElementById('maxProducts').value = '';
        
        filterTable();
        sortTable();
    }
    
    // Initialize from URL parameters
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.has('search')) {
            document.getElementById('searchInput').value = urlParams.get('search');
        }
        if (urlParams.has('gender')) {
            document.getElementById('genderFilter').value = urlParams.get('gender');
        }
        if (urlParams.has('status')) {
            document.getElementById('statusFilter').value = urlParams.get('status');
        }
        if (urlParams.has('sort')) {
            document.getElementById('sortFilter').value = urlParams.get('sort');
        }
        if (urlParams.has('min_products')) {
            document.getElementById('minProducts').value = urlParams.get('min_products');
        }
        if (urlParams.has('max_products')) {
            document.getElementById('maxProducts').value = urlParams.get('max_products');
        }
        
        // Apply filters on page load if any parameters exist
        if (urlParams.toString()) {
            filterTable();
            sortTable();
        }
        
        // Add real-time URL updates
        const filterInputs = [
            'searchInput', 'genderFilter', 'statusFilter', 'sortFilter', 
            'minProducts', 'maxProducts'
        ];
        
        filterInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('change', updateURL);
                input.addEventListener('input', updateURL);
            }
        });
    });
    
    function updateURL() {
        const params = new URLSearchParams();
        
        const search = document.getElementById('searchInput').value;
        const gender = document.getElementById('genderFilter').value;
        const status = document.getElementById('statusFilter').value;
        const sort = document.getElementById('sortFilter').value;
        const minProducts = document.getElementById('minProducts').value;
        const maxProducts = document.getElementById('maxProducts').value;
        
        if (search) params.set('search', search);
        if (gender) params.set('gender', gender);
        if (status) params.set('status', status);
        if (sort !== 'sort_order') params.set('sort', sort);
        if (minProducts) params.set('min_products', minProducts);
        if (maxProducts) params.set('max_products', maxProducts);
        
        const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
        window.history.replaceState({}, '', newUrl);
    }
</script>

<style>
    /* Smooth transitions for filtering */
    .category-row {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Filter chips animation */
    #activeFilters > div {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>