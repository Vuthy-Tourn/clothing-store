<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
   public function index(Request $request)
{
    $query = Product::with(['category', 'variants', 'images' => function($query) {
        $query->orderBy('is_primary', 'desc')->orderBy('sort_order');
    }]);
    
    // Search functionality
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('sku', 'like', '%' . $searchTerm . '%')
              ->orWhere('brand', 'like', '%' . $searchTerm . '%')
              ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('category', function($q) use ($searchTerm) {
                  $q->where('name', 'like', '%' . $searchTerm . '%');
              })
              ->orWhereHas('variants', function($q) use ($searchTerm) {
                  $q->where('color', 'like', '%' . $searchTerm . '%')
                    ->orWhere('size', 'like', '%' . $searchTerm . '%');
              });
        });
        
        // Store search term in session for stats
        session(['search_term' => $searchTerm]);
    } else {
        // Clear search term from session
        session()->forget('search_term');
    }
    
    $products = $query->orderBy('created_at', 'desc')->get();
    $categories = Category::orderBy('name')->get();

    return view('admin.products.index', compact('products', 'categories'));
}
    
    public function search(Request $request)
{
    $searchTerm = $request->get('q', '');
    
    // If search is empty, return recent products
    if (empty($searchTerm)) {
        $products = Product::with(['category', 'variants', 'images' => function($query) {
            $query->orderBy('is_primary', 'desc');
        }])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    } else {
        $products = Product::with(['category', 'variants', 'images' => function($query) {
            $query->orderBy('is_primary', 'desc');
        }])
        ->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('sku', 'like', '%' . $searchTerm . '%')
              ->orWhere('brand', 'like', '%' . $searchTerm . '%')
              ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('category', function($q) use ($searchTerm) {
                  $q->where('name', 'like', '%' . $searchTerm . '%');
              })
              ->orWhereHas('variants', function($q) use ($searchTerm) {
                  $q->where('color', 'like', '%' . $searchTerm . '%')
                    ->orWhere('size', 'like', '%' . $searchTerm . '%');
              });
        })
        ->orderBy('created_at', 'desc')
        ->limit(20)
        ->get();
    }

    return response()->json([
        'success' => true,
        'products' => $products->map(function($product) {
            $primaryVariant = $product->variants->first();
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $primaryVariant ? $primaryVariant->price : 0,
                'sale_price' => $primaryVariant ? $primaryVariant->sale_price : null,
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'status' => $product->status,
                'is_featured' => $product->is_featured,
                'is_new' => $product->is_new,
                'stock' => $primaryVariant ? $primaryVariant->stock : 0,
                'image' => optional($product->images->firstWhere('is_primary', true))->image_path 
                          ?? optional($product->images->first())->image_path 
                          ?? 'products/placeholder.jpg',
                'edit_url' => route('admin.products.edit', $product->id),
                'created_at' => $product->created_at->format('M d, Y'),
                'status_badge' => $this->getStatusBadge($product->status),
                'featured_badge' => $product->is_featured ? '<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Featured</span>' : '',
                'new_badge' => $product->is_new ? '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">New</span>' : ''
            ];
        }),
        'count' => $products->count()
    ]);
}

// Helper method for status badges
private function getStatusBadge($status)
{
    $badges = [
        'active' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-gray-100 text-gray-800',
        'draft' => 'bg-yellow-100 text-yellow-800'
    ];
    
    $class = $badges[$status] ?? 'bg-gray-100 text-gray-800';
    
    return '<span class="px-2 py-1 text-xs rounded ' . $class . '">' . ucfirst($status) . '</span>';
}

   public function store(Request $request)
{
    try {
        Log::info('=== PRODUCT STORE REQUEST START ===');
        Log::info('Request Data:', $request->all());
        
        // VALIDATION
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,draft',
            'sku' => 'nullable|string|unique:products,sku',
            'brand' => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
        ]);
        
        Log::info('Validation passed');

        // Generate SKU if not provided
        $sku = $request->sku ?: 'PROD-' . strtoupper(Str::random(8));
        
        // Generate slug from name
        $slug = Str::slug($request->name);
        
        // Make sure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Create product
        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug, // ADD THIS
            'sku' => $sku,
            'category_id' => $validated['category_id'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'],
            'brand' => $validated['brand'] ?? null,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured', false),
            'is_new' => $request->boolean('is_new', false),
            'rating_cache' => 0.00,
            'review_count' => 0,
            'view_count' => 0,
        ]);

        Log::info('Product created:', ['id' => $product->id, 'slug' => $product->slug]);

        // Create default variant if variants array exists
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $index => $variantData) {
                // Generate variant SKU
                $variantSku = $variantData['sku'] ?? $sku . '-V' . ($index + 1);
                
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantSku,
                    'size' => $variantData['size'] ?? 'M',
                    'color' => $variantData['color'] ?? 'Default',
                    'color_code' => $variantData['color_code'] ?? null,
                    'price' => $variantData['price'] ?? 0,
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'cost_price' => $variantData['cost_price'] ?? null,
                    'stock' => $variantData['stock'] ?? 0,
                    'stock_alert' => $variantData['stock_alert'] ?? 10,
                    'weight' => $variantData['weight'] ?? null,
                    'dimensions' => $variantData['dimensions'] ?? null,
                    'is_active' => isset($variantData['is_active']) ? (bool)$variantData['is_active'] : true,
                ]);
                Log::info('Variant created:', ['id' => $variant->id]);
            }
        } else {
            // Create a default variant if none provided
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $sku . '-V1',
                'size' => 'M',
                'color' => 'Default',
                'price' => 0,
                'stock' => 0,
                'stock_alert' => 10,
                'is_active' => true,
            ]);
            Log::info('Default variant created:', ['id' => $variant->id]);
        }

        // Handle image upload
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $index => $imageData) {
                if (isset($imageData['image']) && $imageData['image']->isValid()) {
                    $imagePath = $imageData['image']->store('products/images', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'alt_text' => $imageData['alt_text'] ?? null,
                        'is_primary' => isset($imageData['is_primary']) ? (bool)$imageData['is_primary'] : ($index === 0),
                        'sort_order' => $imageData['sort_order'] ?? $index,
                    ]);
                    
                    Log::info('Image uploaded:', ['path' => $imagePath]);
                }
            }
        } else {
            // Create a placeholder image entry
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/placeholder.jpg',
                'alt_text' => $product->name,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
            Log::info('Placeholder image created');
        }

        Log::info('=== PRODUCT CREATED SUCCESSFULLY ===');
        
        // Always redirect back with success message
        return redirect()->route('admin.products.index')
            ->with('success', 'Product "' . $product->name . '" created successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation Error:', $e->errors());
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        Log::error('Product Creation Error:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        
        return redirect()->back()
            ->with('error', 'Error creating product: ' . $e->getMessage())
            ->withInput();
    }
}

   public function edit($id)
{
    try {
        $product = Product::with(['category', 'variants', 'images' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('sort_order');
        }])->findOrFail($id);

        return response()->json([
            'success' => true,  // ADD THIS
            'data' => [         // WRAP DATA IN 'data' KEY
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category_id' => $product->category_id,
                'short_description' => $product->short_description ?? '',
                'description' => $product->description,
                'brand' => $product->brand ?? '',
                'status' => $product->status,
                'is_featured' => $product->is_featured,
                'is_new' => $product->is_new,
                'variants' => $product->variants->map(function($variant) {
                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'size' => $variant->size,
                        'color' => $variant->color,
                        'color_code' => $variant->color_code ?? '#000000',
                        'price' => $variant->price,
                        'sale_price' => $variant->sale_price ?? '',
                        'cost_price' => $variant->cost_price ?? '',
                        'stock' => $variant->stock,
                        'stock_alert' => $variant->stock_alert,
                        'weight' => $variant->weight ?? '',
                        'dimensions' => $variant->dimensions ?? '',
                        'is_active' => $variant->is_active,
                    ];
                })->toArray(),
                'images' => $product->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'image_path' => $image->image_path,
                        'alt_text' => $image->alt_text ?? '',
                        'is_primary' => $image->is_primary,
                        'sort_order' => $image->sort_order,
                    ];
                })->toArray()
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Edit Product Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load product data'
        ], 500);
    }
}

   public function update(Request $request, $id)
{
    $product = Product::with(['variants', 'images'])->findOrFail($id);

    // ADD DEBUGGING
    Log::info('=== UPDATE REQUEST START ===');
    Log::info('Product ID: ' . $id);
    Log::info('Request Data: ', $request->all());
    Log::info('Files in request: ', array_keys($request->allFiles()));
    
    // Log deleted_images_json specifically
    Log::info('Deleted Images JSON: ' . ($request->deleted_images_json ?? 'NOT SET'));

    $request->validate([
        'name'               => 'required|string|max:255',
        'sku'                => 'nullable|unique:products,sku,' . $id,
        'category_id'        => 'nullable|exists:categories,id',
        'short_description'  => 'nullable|string|max:500',
        'description'        => 'nullable|string',
        'brand'              => 'nullable|string|max:100',
        'status'             => 'required|in:active,inactive,draft',
        'is_featured'        => 'boolean',
        'is_new'             => 'boolean',
        
        'variants'                     => 'required|array|min:1',
        'variants.*.id'                => 'nullable|exists:product_variants,id',
        'variants.*.size'              => 'required|in:XS,S,M,L,XL,XXL,XXXL,FREE',
        'variants.*.color'             => 'required|string|max:50',
        'variants.*.color_code'        => 'nullable|string|max:7',
        'variants.*.price'             => 'required|numeric|min:0',
        'variants.*.sale_price'        => 'nullable|numeric|min:0',
        'variants.*.cost_price'        => 'nullable|numeric|min:0',
        'variants.*.stock'             => 'required|integer|min:0',
        'variants.*.stock_alert'       => 'nullable|integer|min:0',
        'variants.*.weight'            => 'nullable|numeric|min:0',
        'variants.*.dimensions'        => 'nullable|string|max:50',
        'variants.*.is_active'         => 'boolean',
        
        'deleted_variants'             => 'nullable|string',
        
        'primary_image'                => 'nullable|exists:product_images,id',
        
        'new_images'                   => 'nullable|array',
        'new_images.*.image'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'new_images.*.alt_text'        => 'nullable|string|max:255',
        'new_images.*.sort_order'      => 'nullable|integer|min:0',
        
        'deleted_images_json'          => 'nullable|string',
    ]);

    DB::beginTransaction();
    
    try {
        // Update product basic info
        $product->update([
            'name'              => $request->name,
            'slug'              => Str::slug($request->name),
            'sku'               => $request->sku ?: $product->sku,
            'category_id'       => $request->category_id,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'brand'             => $request->brand,
            'status'            => $request->status,
            'is_featured'       => $request->boolean('is_featured'),
            'is_new'            => $request->boolean('is_new'),
        ]);

        Log::info('Product basic info updated');

        // Handle deleted variants
        if ($request->filled('deleted_variants')) {
            $deletedVariantIds = json_decode($request->deleted_variants, true);
            Log::info('Deleted variants array: ' . json_encode($deletedVariantIds));
            if (is_array($deletedVariantIds) && count($deletedVariantIds) > 0) {
                ProductVariant::where('product_id', $product->id)
                    ->whereIn('id', $deletedVariantIds)
                    ->delete();
                Log::info('Deleted variants: ' . json_encode($deletedVariantIds));
            }
        }

        $updatedVariantIds = [];
        
        // Process variants
        foreach ($request->variants as $variantData) {
            if (isset($variantData['id']) && !empty($variantData['id'])) {
                // Update existing variant
                $variant = ProductVariant::find($variantData['id']);
                if ($variant && $variant->product_id == $product->id) {
                    $variant->update([
                        'size'        => $variantData['size'],
                        'color'       => $variantData['color'],
                        'color_code'  => $variantData['color_code'] ?? null,
                        'price'       => $variantData['price'],
                        'sale_price'  => $variantData['sale_price'] ?? null,
                        'cost_price'  => $variantData['cost_price'] ?? null,
                        'stock'       => $variantData['stock'],
                        'stock_alert' => $variantData['stock_alert'] ?? 10,
                        'weight'      => $variantData['weight'] ?? null,
                        'dimensions'  => $variantData['dimensions'] ?? null,
                        'is_active'   => $variantData['is_active'] ?? true,
                    ]);
                    $updatedVariantIds[] = $variant->id;
                }
            } else {
                // Create new variant
                $variantSku = $variantData['sku'] ?? $product->sku . '-' . 
                    strtoupper(substr($variantData['size'], 0, 1)) . '-' . 
                    strtoupper(substr($variantData['color'], 0, 3));
                
                $newVariant = ProductVariant::create([
                    'product_id'  => $product->id,
                    'sku'         => $variantSku,
                    'size'        => $variantData['size'],
                    'color'       => $variantData['color'],
                    'color_code'  => $variantData['color_code'] ?? null,
                    'price'       => $variantData['price'],
                    'sale_price'  => $variantData['sale_price'] ?? null,
                    'cost_price'  => $variantData['cost_price'] ?? null,
                    'stock'       => $variantData['stock'],
                    'stock_alert' => $variantData['stock_alert'] ?? 10,
                    'weight'      => $variantData['weight'] ?? null,
                    'dimensions'  => $variantData['dimensions'] ?? null,
                    'is_active'   => $variantData['is_active'] ?? true,
                ]);
                $updatedVariantIds[] = $newVariant->id;
            }
        }
        
        // Delete variants not in request
        $currentVariantIds = $product->variants->pluck('id')->toArray();
        $variantsToDelete = array_diff($currentVariantIds, $updatedVariantIds);
        if (!empty($variantsToDelete)) {
            ProductVariant::where('product_id', $product->id)
                ->whereIn('id', $variantsToDelete)
                ->delete();
        }

        // Handle primary image
        if ($request->primary_image) {
            ProductImage::where('product_id', $product->id)
                ->update(['is_primary' => false]);
                
            ProductImage::where('id', $request->primary_image)
                ->update(['is_primary' => true]);
            Log::info('Updated primary image to ID: ' . $request->primary_image);
        }

        // Add new images - WITH DEBUGGING
        if ($request->has('new_images') && is_array($request->new_images)) {
            Log::info('Processing new_images array. Count: ' . count($request->new_images));
            
            foreach ($request->new_images as $index => $imageData) {
                Log::info("Processing new image at index {$index}");
                
                // Check if image exists and is valid
                if (isset($imageData['image']) && $imageData['image']->isValid()) {
                    $imagePath = $imageData['image']->store('products/images', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'alt_text'   => $imageData['alt_text'] ?? null,
                        'is_primary' => false,
                        'sort_order' => $imageData['sort_order'] ?? $index,
                    ]);
                    Log::info("New image uploaded: {$imagePath}");
                } else {
                    Log::warning("Image at index {$index} is invalid or missing");
                }
            }
        } else {
            Log::info('No new_images in request');
        }

        // Handle deleted images from JSON - IMPROVED DEBUGGING
        Log::info('Checking deleted_images_json field...');
        if ($request->filled('deleted_images_json')) {
            $deletedImages = json_decode($request->deleted_images_json, true);
            Log::info('Raw deleted_images_json: ' . $request->deleted_images_json);
            Log::info('Parsed deleted images: ' . json_encode($deletedImages));
            Log::info('Is array? ' . (is_array($deletedImages) ? 'YES' : 'NO'));
            Log::info('Array count: ' . (is_array($deletedImages) ? count($deletedImages) : '0'));
            
            if (is_array($deletedImages) && count($deletedImages) > 0) {
                Log::info('Starting to delete ' . count($deletedImages) . ' images');
                
                foreach ($deletedImages as $imageId) {
                    Log::info("Processing image ID: {$imageId}");
                    $image = ProductImage::find($imageId);
                    
                    if ($image) {
                        Log::info("Found image: ID={$image->id}, Product ID={$image->product_id}, Path={$image->image_path}");
                        
                        if ($image->product_id == $product->id) {
                            // Check if this is not a placeholder and file exists
                            if ($image->image_path !== 'products/placeholder.jpg' && 
                                Storage::disk('public')->exists($image->image_path)) {
                                Storage::disk('public')->delete($image->image_path);
                                Log::info("Deleted image file: {$image->image_path}");
                            }
                            
                            // Delete from database
                            $image->delete();
                            Log::info('Deleted image record ID: ' . $imageId);
                        } else {
                            Log::warning("Image ID {$imageId} doesn't belong to product (belongs to product {$image->product_id})");
                        }
                    } else {
                        Log::warning("Image ID {$imageId} not found in database");
                    }
                }
                Log::info('Finished deleting images');
            } else {
                Log::info('No valid images to delete (empty array or not array)');
            }
        } else {
            Log::info('No deleted_images_json field in request');
        }

        // Log remaining images for verification
        $remainingImages = ProductImage::where('product_id', $product->id)->count();
        Log::info('Remaining images after update: ' . $remainingImages);

        DB::commit();
        Log::info('=== PRODUCT UPDATE SUCCESSFUL ===');
        
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Product update error: ' . $e->getMessage());
        Log::error('Error trace: ' . $e->getTraceAsString());
        return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        $product = Product::with(['variants', 'images'])->findOrFail($id);

        DB::beginTransaction();
        
        try {
            // Delete variant images (if any)
            foreach ($product->variants as $variant) {
                // You might have variant-specific images to delete here
            }
            
            // Delete product images from storage and database
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
            
            // Delete variants
            $product->variants()->delete();
            
            // Delete product
            $product->delete();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Product deleted successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        if (!file_exists($path)) return back()->with('error', 'File not found.');

        $data = array_map('str_getcsv', file($path));
        if (count($data) <= 1) return back()->with('error', 'CSV appears to be empty.');

        $header = array_map('trim', $data[0]);

        DB::beginTransaction();
        
        try {
            foreach (array_slice($data, 1) as $row) {
                if (count($row) !== count($header)) continue;
                
                $row = array_combine($header, $row);

                // Generate SKU if not provided
                $sku = $row['sku'] ?? 'PROD-' . strtoupper(Str::random(8));
                
                // Create product
                $product = Product::create([
                    'name'              => $row['name'],
                    'slug'              => Str::slug($row['name']),
                    'sku'               => $sku,
                    'category_id'       => $row['category_id'] ?? null,
                    'short_description' => $row['short_description'] ?? null,
                    'description'       => $row['description'] ?? null,
                    'brand'             => $row['brand'] ?? null,
                    'status'            => $row['status'] ?? 'active',
                    'is_featured'       => isset($row['is_featured']) ? (bool)$row['is_featured'] : false,
                    'is_new'            => isset($row['is_new']) ? (bool)$row['is_new'] : false,
                ]);

                // Parse variants from CSV
                // CSV format could be: size,color,price,stock
                $variantData = json_decode($row['variants'] ?? '[]', true);
                
                if (is_array($variantData)) {
                    foreach ($variantData as $variant) {
                        ProductVariant::create([
                            'product_id'  => $product->id,
                            'sku'         => $sku . '-' . 
                                            strtoupper(substr($variant['size'], 0, 1)) . '-' . 
                                            strtoupper(substr($variant['color'], 0, 3)),
                            'size'        => $variant['size'] ?? 'M',
                            'color'       => $variant['color'] ?? 'Default',
                            'price'       => $variant['price'] ?? 0,
                            'stock'       => $variant['stock'] ?? 0,
                            'stock_alert' => $variant['stock_alert'] ?? 10,
                            'is_active'   => true,
                        ]);
                    }
                }
                
                // Handle image import if specified
                if (!empty($row['image_path'])) {
                    // This would need custom handling based on how you store images
                    // For now, just store the path
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $row['image_path'],
                        'is_primary' => true,
                        'sort_order' => 0,
                    ]);
                }
            }

            DB::commit();
            
            return back()->with('success', 'Products imported successfully with variants!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to import products: ' . $e->getMessage());
        }
    }

    // Additional helper methods
    
    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_featured' => !$product->is_featured]);
        
        return response()->json([
            'success' => true,
            'is_featured' => $product->is_featured
        ]);
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);
        
        return response()->json([
            'success' => true,
            'status' => $newStatus
        ]);
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:0',
            'action'     => 'required|in:add,subtract,set',
        ]);

        $variant = ProductVariant::where('id', $request->variant_id)
            ->where('product_id', $id)
            ->firstOrFail();

        switch ($request->action) {
            case 'add':
                $variant->increment('stock', $request->quantity);
                break;
            case 'subtract':
                $variant->decrement('stock', $request->quantity);
                break;
            case 'set':
                $variant->update(['stock' => $request->quantity]);
                break;
        }

        return response()->json([
            'success' => true,
            'stock' => $variant->stock,
            'message' => 'Stock updated successfully'
        ]);
    }
}