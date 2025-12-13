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
        ->where('status', 'active'); // Only show active products

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('short_description', 'like', '%' . $search . '%')
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

        // Filter by size
        if ($request->filled('size')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('size', $request->size)
                  ->where('stock', '>', 0); // Only show sizes that are in stock
            });
        }

        // Filter by color
        if ($request->filled('color')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('color', 'like', '%' . $request->color . '%')
                  ->where('stock', '>', 0);
            });
        }

        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'in_stock') {
                $query->whereHas('variants', function($q) {
                    $q->where('stock', '>', 0);
                });
            } elseif ($request->availability === 'out_of_stock') {
                $query->whereHas('variants', function($q) {
                    $q->where('stock', '<=', 0);
                });
            }
        }

        // Get products with sorting
        $products = $query->get();

        // Apply sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low_high':
                    $products = $products->sortBy(function($product) {
                        return $product->variants->min('price') ?? PHP_INT_MAX;
                    });
                    break;
                    
                case 'price_high_low':
                    $products = $products->sortByDesc(function($product) {
                        return $product->variants->max('price') ?? 0;
                    });
                    break;
                    
                case 'newest':
                    $products = $products->sortByDesc('created_at');
                    break;
                    
                case 'oldest':
                    $products = $products->sortBy('created_at');
                    break;
                    
                case 'name_asc':
                    $products = $products->sortBy('name');
                    break;
                    
                case 'name_desc':
                    $products = $products->sortByDesc('name');
                    break;
                    
                case 'featured':
                    $products = $products->sortByDesc('is_featured');
                    break;
                    
                case 'popular':
                    $products = $products->sortByDesc('view_count');
                    break;
                    
                case 'rating':
                    $products = $products->sortByDesc('rating_cache');
                    break;
                    
                default:
                    $products = $products->sortByDesc('created_at');
            }
        } else {
            // Default sorting: featured first, then newest
            $products = $products->sortByDesc('is_featured')
                ->sortByDesc('created_at');
        }

        // Manual pagination
        $perPage = $request->get('per_page', 12);
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        
        $paginatedProducts = $products->slice($offset, $perPage)->values();
        
        $paginator = new LengthAwarePaginator(
            $paginatedProducts,
            $products->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        // Get available filters for sidebar
        $availableSizes = $this->getAvailableSizes($category->id);
        $availableColors = $this->getAvailableColors($category->id);
        $priceRange = $this->getPriceRange($category->id);

        return view('frontend.category-page', [
            'category' => $category,
            'categories' => $categories,
            'products' => $paginator,
            'availableSizes' => $availableSizes,
            'availableColors' => $availableColors,
            'priceRange' => $priceRange,
            'filters' => $request->all()
        ]);
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
                'short_description' => $product->short_description,
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
                'short_description' => $product->short_description,
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