<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::latest()->get();
        return view('admin.carousels.index', compact('carousels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|url|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('carousels', 'public');

        Carousel::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $path,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.carousels.index')->with('success', 'Carousel added successfully.');
    }

    // Add this edit method for AJAX
    public function edit(Carousel $carousel)
    {
        return response()->json([
            'id' => $carousel->id,
            'title' => $carousel->title,
            'description' => $carousel->description,
            'button_text' => $carousel->button_text,
            'button_link' => $carousel->button_link,
            'is_active' => $carousel->is_active,
            'image_path' => $carousel->image_path,
        ]);
    }

    public function update(Request $request, Carousel $carousel)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|url|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($carousel->image_path && Storage::disk('public')->exists($carousel->image_path)) {
                Storage::disk('public')->delete($carousel->image_path);
            }
            
            $data['image_path'] = $request->file('image')->store('carousels', 'public');
        }

        $carousel->update($data);

        return redirect()->route('admin.carousels.index')->with('success', 'Carousel updated successfully.');
    }

    public function destroy(Carousel $carousel)
    {
        // Delete image from storage
        if ($carousel->image_path && Storage::disk('public')->exists($carousel->image_path)) {
            Storage::disk('public')->delete($carousel->image_path);
        }
        
        $carousel->delete();
        return redirect()->route('admin.carousels.index')->with('success', 'Carousel deleted successfully.');
    }
}