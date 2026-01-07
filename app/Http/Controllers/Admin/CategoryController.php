<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
   public function index(Request $request)
{
    $query = Category::with(['products']);

    // Search filter
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('slug', 'LIKE', "%{$search}%");
        });
    }

    // Gender filter
    if ($request->has('gender') && !empty($request->gender)) {
        $query->where('gender', $request->gender);
    }

    // Status filter
    if ($request->has('status') && !empty($request->status)) {
        $query->where('status', $request->status);
    }

    // Sort by
    if ($request->has('sort')) {
        switch ($request->sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'products_desc':
                $query->withCount('products')->orderBy('products_count', 'desc');
                break;
            case 'products_asc':
                $query->withCount('products')->orderBy('products_count');
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('created_at');
                break;
            default:
                $query->orderBy('sort_order');
        }
    } else {
        $query->orderBy('sort_order');
    }

    // Products count filter (if using server-side with products_count)
    if ($request->has('min_products') && !empty($request->min_products)) {
        $query->has('products', '>=', $request->min_products);
    }

    if ($request->has('max_products') && !empty($request->max_products)) {
        $query->has('products', '<=', $request->max_products);
    }

    $categories = $query->get();

    // For AJAX requests, return JSON
    if ($request->ajax()) {
        return response()->json([
            'categories' => view('admin.categories.partials.table', compact('categories'))->render(),
            'stats' => [
                'total' => $categories->count(),
                'active' => $categories->where('status', 'active')->count(),
                // Add other stats as needed
            ]
        ]);
    }

    return view('admin.categories.index', compact('categories'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'gender'   => 'required|in:men,women,unisex,kids',
            'image'    => 'nullable|image|max:2048', // Changed to nullable
            'status'   => 'required|in:active,inactive',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $slug = Str::slug($request->name);
            $slug = $this->generateUniqueSlug($slug);

            $categoryData = [
                'name'        => $request->name,
                'gender'      => $request->gender,
                'slug'        => $request->slug,
                'description' => $request->description,
                'parent_id'   => $request->parent_id,
                'status'      => $request->status,
                'sort_order'  => $request->sort_order ?? Category::count(),
            ];

            // Handle image upload only if provided
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('categories', 'public');
                $categoryData['image'] = $path;
            }

            Category::create($categoryData);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'redirect' => route('admin.categories.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'gender'      => 'required|in:men,women,unisex,kids',
        'image'       => 'nullable|image|max:2048',
        'status'      => 'required|in:active,inactive',
        'parent_id'   => 'nullable|exists:categories,id',
        'sort_order'  => 'nullable|integer|min:0',
        'description' => 'nullable|string|max:1000',
    ]);

    try {
        // Update image if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $category->image = $request->file('image')->store('categories', 'public');
        }

        // Generate slug only if name changed
        if ($category->name !== $request->name) {
            $slug = Str::slug($request->name);
            $category->slug = $this->generateUniqueSlug($slug, $category->id);
        }

        $category->name        = $request->name;
        $category->slug        = $request->slug;
        $category->gender      = $request->gender;
        $category->description = $request->description;
        $category->parent_id   = $request->parent_id;
        $category->status      = $request->status;
        $category->sort_order  = $request->sort_order ?? $category->sort_order;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'redirect' => route('admin.categories.index')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating category: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Prevent deleting category if it has products
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with products. Please move products first.');
        }

        // Prevent deleting category if it has children
        if ($category->children()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with subcategories. Please delete subcategories first.');
        }

        // Delete image file
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function edit($id)
    {
        $category = Category::with('parent')->findOrFail($id);
        return response()->json($category);
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');
        
        foreach ($order as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Generate unique slug for category
     */
    private function generateUniqueSlug($slug, $exceptId = null)
    {
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)
            ->when($exceptId, function ($query) use ($exceptId) {
                return $query->where('id', '!=', $exceptId);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Bulk actions for categories
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if ($action === 'delete') {
            // Check if any category has products before deleting
            $categoriesWithProducts = Category::whereIn('id', $ids)
                ->whereHas('products')
                ->count();

            if ($categoriesWithProducts > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some categories have products. Cannot delete.'
                ]);
            }

            // Delete categories
            Category::whereIn('id', $ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Categories deleted successfully.'
            ]);
        } elseif ($action === 'activate') {
            Category::whereIn('id', $ids)->update(['status' => 'active']);
            
            return response()->json([
                'success' => true,
                'message' => 'Categories activated successfully.'
            ]);
        } elseif ($action === 'deactivate') {
            Category::whereIn('id', $ids)->update(['status' => 'inactive']);
            
            return response()->json([
                'success' => true,
                'message' => 'Categories deactivated successfully.'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid action.']);
    }
}