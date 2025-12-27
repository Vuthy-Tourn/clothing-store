<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ProductDisplayController extends Controller
{
    public function index(Request $request)
    {
        // Use eager loading for relationships
        $query = Product::with(['category', 'variants', 'images'])
            ->where('status', 'active');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price range filter
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

        // Size filter
        if ($request->filled('size')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('size', $request->size)
                  ->where('stock', '>', 0);
            });
        }

        // Color filter
        if ($request->filled('color')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('color', $request->color)
                  ->where('stock', '>', 0);
            });
        }

        // Featured or new products
        if ($request->filled('type')) {
            if ($request->type === 'featured') {
                $query->where('is_featured', true);
            } elseif ($request->type === 'new') {
                $query->where('is_new', true);
            }
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    // Sort by minimum variant price
                    $query->orderByRaw('(
                        SELECT MIN(price) FROM product_variants 
                        WHERE product_id = products.id AND stock > 0
                    ) ASC');
                    break;
                case 'price_high':
                    // Sort by maximum variant price
                    $query->orderByRaw('(
                        SELECT MAX(price) FROM product_variants 
                        WHERE product_id = products.id AND stock > 0
                    ) DESC');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 30);
        $products = $query->paginate($perPage);

        $categories = Category::where('status', 'active')->get();

        return view('frontend.all-products', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function showAll()
    {
        $products = Product::with(['category', 'variants', 'images'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(30);
            
        $categories = Category::where('status', 'active')->get();

        return view('frontend.all-products', compact('products', 'categories'));
    }

    public function view($slug)
    {
        // Find by slug using route model binding or manual query
        $product = Product::with(['category', 'variants' => function($query) {
            $query->where('is_active', true)
                ->orderByRaw("FIELD(size, 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE')");
        }, 'images' => function($query) {
            $query->orderBy('is_primary', 'desc')
                ->orderBy('sort_order', 'asc');
        }])
        ->where('slug', $slug)
        ->where('status', 'active')
        ->firstOrFail();

        // Check if reviews table exists and load reviews if it does
        if (Schema::hasTable('product_reviews')) {
            $product->load(['reviews' => function($query) {
                $query->where('is_approved', true)
                    ->with('user:id,name,profile_picture')
                    ->latest()
                    ->take(5);
            }]);
        }

        // Increment view count
        $product->increment('view_count');

        // Get related products
        $relatedProducts = Product::with(['images' => function($query) {
            $query->orderBy('is_primary', 'desc')->limit(1);
        }])
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('status', 'active')
        ->take(4)
        ->get();

        return view('frontend.view-product', compact('product', 'relatedProducts'));
    }
    // New arrival products
    public function newArrivals()
    {
        $arrivals = Product::with(['images' => function($query) {
            $query->orderBy('is_primary', 'desc')->limit(1);
        }, 'variants'])
        ->where('is_new', true)
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();

        return view('frontend.new-arrivals', compact('arrivals'));
    }

    // Featured products
    public function featured()
    {
        $featured = Product::with(['images' => function($query) {
            $query->orderBy('is_primary', 'desc')->limit(1);
        }, 'variants'])
        ->where('is_featured', true)
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();

        return view('frontend.featured-products', compact('featured'));
    }

    // Category products
    public function category($categorySlug)
    {
        $category = Category::where('slug', $categorySlug)
            ->where('status', 'active')
            ->firstOrFail();

        $products = Product::with(['images' => function($query) {
            $query->orderBy('is_primary', 'desc')->limit(1);
        }, 'variants'])
        ->where('category_id', $category->id)
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->paginate(30);

        return view('frontend.category-products', compact('products', 'category'));
    }

    // Search products
public function search(Request $request)
{
    $request->validate([
        'q' => 'required|string|min:2|max:100',
    ]);

    $searchQuery = $request->q;
    $query = Product::with(['images' => function($query) {
        $query->orderBy('is_primary', 'desc')->limit(1);
    }, 'variants', 'category'])
    ->where('status', 'active');

    // Handle quick filters differently - don't make them too restrictive
    $searchTerm = strtolower($searchQuery);
    
    // Build search conditions
    $query->where(function($q) use ($searchQuery, $searchTerm) {
        $q->where('name', 'like', '%' . $searchQuery . '%')
          ->orWhere('description', 'like', '%' . $searchQuery . '%')
          ->orWhereHas('category', function($categoryQuery) use ($searchQuery) {
              $categoryQuery->where('name', 'like', '%' . $searchQuery . '%');
          });
        
        // Add additional search terms based on quick filters
        if (str_contains($searchTerm, 'new')) {
            $q->orWhere('is_new', true);
        }
        if (str_contains($searchTerm, 'featured')) {
            $q->orWhere('is_featured', true);
        }
    });

    // Apply gender filters as a separate condition (not OR, but AND)
    // This makes the search more flexible
    if (str_contains($searchTerm, 'men')) {
        $query->whereHas('category', function($q) {
            $q->where('gender', 'men');
        });
    } elseif (str_contains($searchTerm, 'women')) {
        $query->whereHas('category', function($q) {
            $q->where('gender', 'women');
        });
    } elseif (str_contains($searchTerm, 'kids')) {
        $query->whereHas('category', function($q) {
            $q->where('gender', 'unisex');
        });
    }

    // For AJAX requests (search modal)
    if ($request->ajax() || $request->wantsJson()) {
        try {
            $total = $query->count();
            
            // If no results with the current filters, try a broader search
            if ($total === 0) {
                // Try a broader search without the gender filter
                $fallbackQuery = Product::with(['images' => function($query) {
                    $query->orderBy('is_primary', 'desc')->limit(1);
                }, 'variants', 'category'])
                ->where('status', 'active')
                ->where(function($q) use ($searchQuery) {
                    $q->where('name', 'like', '%' . $searchQuery . '%')
                      ->orWhere('description', 'like', '%' . $searchQuery . '%')
                      ->orWhereHas('category', function($categoryQuery) use ($searchQuery) {
                          $categoryQuery->where('name', 'like', '%' . $searchQuery . '%');
                      });
                });
                
                $total = $fallbackQuery->count();
                $limit = $request->get('limit', 6);
                $products = $fallbackQuery->take($limit)->get();
            } else {
                $limit = $request->get('limit', 6);
                $products = $query->take($limit)->get();
            }
            
            // If still no results, check if it's a category name search
            if ($total === 0) {
                $categoryQuery = Product::with(['images' => function($query) {
                    $query->orderBy('is_primary', 'desc')->limit(1);
                }, 'variants', 'category'])
                ->where('status', 'active')
                ->whereHas('category', function($q) use ($searchQuery) {
                    $q->where('name', 'like', '%' . $searchQuery . '%');
                });
                
                $total = $categoryQuery->count();
                $limit = $request->get('limit', 6);
                $products = $categoryQuery->take($limit)->get();
            }
            
            // Transform products for JSON response
            $productsData = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description,
                    'rating_cache' => $product->rating_cache,
                    'review_count' => $product->review_count,
                    'is_new' => $product->is_new,
                    'is_featured' => $product->is_featured,
                    'images' => $product->images->map(function($image) {
                        return [
                            'image_path' => $image->image_path,
                            'is_primary' => $image->is_primary
                        ];
                    }),
                    'variants' => $product->variants->map(function($variant) {
                        return [
                            'price' => $variant->price,
                            'discount_price' => $variant->discount_price,
                            'stock' => $variant->stock,
                            'size' => $variant->size,
                            'color' => $variant->color
                        ];
                    }),
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                        'gender' => $product->category->gender
                    ] : null
                ];
            });
            
            return response()->json([
                'success' => true,
                'products' => $productsData,
                'total' => $total,
                'searchQuery' => $searchQuery,
                'hasResults' => $total > 0,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error performing search',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Regular pagination for full page view
    $products = $query->paginate(24);

    return view('frontend.search-results', [
        'products' => $products,
        'searchQuery' => $searchQuery,
    ]);
}

    public function submitReview(Request $request, $slug)
{
    $product = Product::where('slug', $slug)->firstOrFail();
    
    // Validate the request
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'nullable|string|max:255',
        'comment' => 'required|string|min:10|max:2000',
    ]);
    
    // Check if user already reviewed
    $existingReview = ProductReview::where('user_id', Auth::id())
        ->where('product_id', $product->id)
        ->first();
    
    if ($existingReview) {
        return redirect()->back()->with('error', 'You have already reviewed this product.');
    }
    
    // Create review
    $review = ProductReview::create([
        'product_id' => $product->id,
        'user_id' => Auth::id(),
        'rating' => $validated['rating'],
        'title' => $validated['title'] ?? null,
        'comment' => $validated['comment'],
        'is_approved' => true, // Or set to false for admin approval
    ]);
    
    // Update product rating cache
    $this->updateProductRating($product);
    
    return redirect()->back()->with('success', 'Thank you for your review!');
}

private function updateProductRating(Product $product)
{
    $reviews = $product->reviews()->where('is_approved', true);
    
    $avgRating = $reviews->avg('rating');
    $reviewCount = $reviews->count();
    
    $product->update([
        'rating_cache' => round($avgRating, 1),
        'review_count' => $reviewCount,
    ]);
}
}