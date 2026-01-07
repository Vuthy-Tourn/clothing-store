<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryPageController extends Controller
{
   public function show(Request $request, $slug)
{
    $category = Category::where('slug', $slug)
        ->where('status', 'active')
        ->firstOrFail();
        
    $categories = Category::where('status', 'active')->get();

    // Start building the query with proper relationships
    $query = Product::with(['category', 'variants', 'images' => function($query) {
        $query->where('is_primary', true)->orWhere('sort_order', 0)->limit(1);
    }])
    ->where('category_id', $category->id)
    ->where('status', 'active');

    // Apply all filters
    $query = $this->applyFilters($query, $request);

    // Get products with sorting
    $products = $query->get();

    // Apply sorting
    $products = $this->applySorting($products, $request);

    // Manual pagination
    $paginator = $this->paginateProducts($products, $request);

    // Get available filters for sidebar
    $availableSizes = $this->getAvailableSizes($category->id);
    $availableColors = $this->getAvailableColors($category->id);
    $availableBrands = $this->getAvailableBrands($category->id);
    $priceRange = $this->getPriceRange($category->id);

    return view('frontend.category-page', [
        'category' => $category,
        'categories' => $categories,
        'products' => $paginator,
        'availableSizes' => $availableSizes,
        'availableColors' => $availableColors,
        'availableBrands' => $availableBrands,
        'priceRange' => $priceRange,
        'filters' => $request->all(),
        'gender' => $category->gender ?? null, // ADD THIS LINE
    ]);
}

    public function showByGender(Request $request, $gender)
    {
        // Map URL gender to database gender values
        $genderMap = [
            'men' => 'men',
            'women' => 'women',
            'kids' => 'kids'
        ];
        
        $dbGender = $genderMap[$gender] ?? $gender;
        
        // Get ALL active categories (for navbar)
        $allCategories = Category::where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();
        
        // Get categories for this gender
        $genderCategories = $allCategories->where('gender', $dbGender);
        
        if ($genderCategories->isEmpty()) {
            // Create a dummy category for display
            $category = (object) [
                'name' => ucfirst($gender) . ' Collection',
                'slug' => $gender,
                'gender' => $dbGender,
                'id' => null
            ];
        } else {
            $category = $genderCategories->first();
        }
        
        // Build query for ALL products of this gender (across all categories)
        $categoryIds = $genderCategories->pluck('id')->toArray();
        
        $query = Product::with(['category', 'variants', 'images'])
        ->whereIn('category_id', $categoryIds)
        ->where('status', 'active'); // Only show active products

        // Apply all filters
        $query = $this->applyFilters($query, $request);

        // Filter by category (for gender pages)
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $specificCategory = Category::where('slug', $categorySlug)->first();
            if ($specificCategory) {
                $query->where('category_id', $specificCategory->id);
            }
        }

        // Get products with sorting
        $products = $query->get();

        // Apply sorting
        $products = $this->applySorting($products, $request);

        // Manual pagination
        $paginator = $this->paginateProducts($products, $request);

        // Get available filters for sidebar
        $availableSizes = $this->getAvailableSizesForGender($categoryIds);
        $availableColors = $this->getAvailableColorsForGender($categoryIds);
        $availableBrands = $this->getAvailableBrands(null, $categoryIds);
        $priceRange = $this->getPriceRangeForGender($categoryIds);

        return view('frontend.category-page', [
            'gender' => $gender,
            'genderCategories' => $genderCategories,
            'category' => $category,
            'categories' => $allCategories,
            'products' => $paginator,
            'availableSizes' => $availableSizes,
            'availableColors' => $availableColors,
            'availableBrands' => $availableBrands,
            'priceRange' => $priceRange,
            'filters' => $request->all()
        ]);
    }

    /**
     * Apply all filters to the query
     */
    private function applyFilters($query, $request)
    {
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%');
            });
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        // Filter by size (multiple sizes)
        if ($request->filled('size')) {
            $sizes = is_array($request->size) ? $request->size : [$request->size];
            $query->whereHas('variants', function($q) use ($sizes) {
                $q->whereIn('size', $sizes)
                  ->where('stock', '>', 0);
            });
        }

        // Filter by color (multiple colors)
        if ($request->filled('color')) {
            $colors = is_array($request->color) ? $request->color : [$request->color];
            $query->whereHas('variants', function($q) use ($colors) {
                $q->where(function($subQuery) use ($colors) {
                    foreach ($colors as $color) {
                        $subQuery->orWhere('color', 'like', '%' . $color . '%');
                    }
                })->where('stock', '>', 0);
            });
        }

        // Filter by brand (multiple brands)
        if ($request->filled('brand')) {
            $brands = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand', $brands);
        }

        // Filter by featured
        if ($request->filled('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }

        // Filter by new arrivals
        if ($request->filled('new_arrival') && $request->new_arrival == '1') {
            $query->where('is_new', true);
        }

        // Filter by availability
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'in_stock':
                    $query->whereHas('variants', function($q) {
                        $q->where('stock', '>', 0);
                    });
                    break;
                case 'low_stock':
                    $query->whereHas('variants', function($q) {
                        $q->where('stock', '>', 0)
                          ->where('stock', '<=', 10); // Low stock threshold
                    });
                    break;
                case 'out_of_stock':
                    $query->whereHas('variants', function($q) {
                        $q->where('stock', '<=', 0);
                    });
                    break;
            }
        }

        return $query;
    }

    /**
     * Apply sorting to products
     */
    private function applySorting($products, $request)
    {
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low_high':
                    return $products->sortBy(function($product) {
                        return $product->variants->min('price') ?? PHP_INT_MAX;
                    });
                    
                case 'price_high_low':
                    return $products->sortByDesc(function($product) {
                        return $product->variants->max('price') ?? 0;
                    });
                    
                case 'newest':
                    return $products->sortByDesc('created_at');
                    
                case 'oldest':
                    return $products->sortBy('created_at');
                    
                case 'name_asc':
                    return $products->sortBy('name');
                    
                case 'name_desc':
                    return $products->sortByDesc('name');
                    
                case 'featured':
                    return $products->sortByDesc('is_featured');
                    
                case 'popular':
                    return $products->sortByDesc('view_count');
                    
                case 'rating':
                    return $products->sortByDesc('rating_cache');
                    
                default:
                    return $products->sortByDesc('created_at');
            }
        } else {
            // Default sorting: featured first, then newest
            return $products->sortByDesc('is_featured')
                ->sortByDesc('created_at');
        }
    }

    /**
     * Paginate products
     */
    private function paginateProducts($products, $request)
    {
        $perPage = $request->get('per_page', 12);
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        
        $paginatedProducts = $products->slice($offset, $perPage)->values();
        
        return new LengthAwarePaginator(
            $paginatedProducts,
            $products->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
    }

    /**
     * Get available sizes for the category
     */
    private function getAvailableSizes($categoryId)
    {
        return Product::where('category_id', $categoryId)
            ->where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants' => function($q) {
                $q->select('size')
                  ->where('stock', '>', 0)
                  ->distinct();
            }])
            ->get()
            ->pluck('variants.*.size')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Get available colors for the category
     */
    private function getAvailableColors($categoryId)
    {
        return Product::where('category_id', $categoryId)
            ->where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants' => function($q) {
                $q->select('color')
                  ->where('stock', '>', 0)
                  ->distinct();
            }])
            ->get()
            ->pluck('variants.*.color')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Get price range for the category
     */
    private function getPriceRange($categoryId)
    {
        $minPrice = Product::where('category_id', $categoryId)
            ->where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants' => function($q) {
                $q->select('price')
                  ->where('stock', '>', 0)
                  ->orderBy('price');
            }])
            ->get()
            ->pluck('variants.*.price')
            ->flatten()
            ->min();

        $maxPrice = Product::where('category_id', $categoryId)
            ->where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants' => function($q) {
                $q->select('price')
                  ->where('stock', '>', 0)
                  ->orderByDesc('price');
            }])
            ->get()
            ->pluck('variants.*.price')
            ->flatten()
            ->max();

        return [
            'min' => $minPrice ?? 0,
            'max' => $maxPrice ?? 1000
        ];
    }

    /**
     * Get available brands for the category/categories
     */
    private function getAvailableBrands($categoryId = null, $categoryIds = [])
    {
        $query = Product::where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            });
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        } elseif (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }
        
        return $query->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand')
            ->toArray();
    }

    /**
     * Get available sizes for multiple categories (gender collection)
     */
    private function getAvailableSizesForGender($categoryIds)
    {
        return Product::whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants' => function($q) {
                $q->select('size')
                  ->where('stock', '>', 0)
                  ->distinct();
            }])
            ->get()
            ->pluck('variants.*.size')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Get available colors for multiple categories (gender collection)
     */
    private function getAvailableColorsForGender($categoryIds)
    {
        return Product::whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants' => function($q) {
                $q->select('color')
                  ->where('stock', '>', 0)
                  ->distinct();
            }])
            ->get()
            ->pluck('variants.*.color')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Get price range for multiple categories (gender collection)
     */
    private function getPriceRangeForGender($categoryIds)
    {
        $minPrice = Product::whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants' => function($q) {
                $q->select('price')
                  ->where('stock', '>', 0)
                  ->orderBy('price');
            }])
            ->get()
            ->pluck('variants.*.price')
            ->flatten()
            ->min();

        $maxPrice = Product::whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants' => function($q) {
                $q->select('price')
                  ->where('stock', '>', 0)
                  ->orderByDesc('price');
            }])
            ->get()
            ->pluck('variants.*.price')
            ->flatten()
            ->max();

        return [
            'min' => $minPrice ?? 0,
            'max' => $maxPrice ?? 1000
        ];
    }

     /**
     * Quick view product details (for modal)
     */
    public function quickView($id)
    {
        $product = Product::with(['category', 'variants' => function($q) {
            $q->where('is_active', true);
        }, 'images' => function($q) {
            $q->orderBy('is_primary', 'desc')->orderBy('sort_order');
        }])
        ->where('status', 'active')
        ->findOrFail($id);

        // Increment view count
        $product->increment('view_count');

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'brand' => $product->brand,
                'rating' => $product->rating_cache,
                'review_count' => $product->review_count,
                'is_featured' => $product->is_featured,
                'is_new' => $product->is_new,
                'category' => $product->category->name ?? null,
                'variants' => $product->variants->map(function($variant) {
                    return [
                        'id' => $variant->id,
                        'size' => $variant->size,
                        'color' => $variant->color,
                        'color_code' => $variant->color_code,
                        'price' => $variant->price,
                        'sale_price' => $variant->sale_price,
                        'stock' => $variant->stock,
                        'is_on_sale' => $variant->isOnSale(),
                        'discounted_price' => $variant->getDiscountedPriceAttribute(),
                        'discount_percentage' => $variant->getDiscountPercentageAttribute(),
                    ];
                }),
                'images' => $product->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'path' => asset('storage/' . $image->image_path),
                        'alt' => $image->alt_text,
                        'is_primary' => $image->is_primary,
                    ];
                }),
                'min_price' => $product->min_price,
                'max_price' => $product->max_price,
                'total_stock' => $product->total_stock,
                'available_sizes' => $product->available_sizes,
                'available_colors' => $product->available_colors,
            ]
        ]);
    }

    /**
     * Get products by category for AJAX requests
     */
    public function getProductsByCategory($categoryId)
    {
        $products = Product::with(['images' => function($q) {
            $q->where('is_primary', true)->orWhere('sort_order', 0)->limit(1);
        }, 'variants'])
        ->where('category_id', $categoryId)
        ->where('status', 'active')
        ->whereHas('variants', function($q) {
            $q->where('stock', '>', 0);
        })
        ->take(8)
        ->get()
        ->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->images->first() ? 
                    asset('storage/' . $product->images->first()->image_path) : 
                    asset('images/placeholder.jpg'),
                'min_price' => $product->min_price,
                'max_price' => $product->max_price,
                'is_featured' => $product->is_featured,
                'is_new' => $product->is_new,
                'rating' => $product->rating_cache,
                'review_count' => $product->review_count,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }
}