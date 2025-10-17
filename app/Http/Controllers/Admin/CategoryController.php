<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::with('products')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        // Transform the data to match frontend expectations
        $transformedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image' => $category->image,
                'parent_id' => $category->parent_id ?? null,
                'parent_name' => null, // Will be set if needed
                'status' => $category->is_active ? 'active' : 'inactive',
                'products_count' => $category->products_count,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformedCategories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|string', // Base64 image data
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle image upload if provided
        $imagePath = null;
        if (!empty($validatedData['image'])) {
            $imagePath = $this->processImage($validatedData['image']);
        }

        // Transform status to is_active
        $categoryData = [
            'name' => $validatedData['name'],
            'slug' => \Str::slug($validatedData['name']),
            'description' => $validatedData['description'] ?? null,
            'image' => $imagePath,
            'parent_id' => $validatedData['parent_id'] ?? null,
            'is_active' => $validatedData['status'] === 'active',
            'sort_order' => 0,
        ];

        $category = Category::create($categoryData);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image' => $category->image,
                'parent_id' => $category->parent_id,
                'status' => $category->is_active ? 'active' : 'inactive',
                'products_count' => 0,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        $category->load('products');
        $category->loadCount('products');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'parent_id' => $category->parent_id,
                'status' => $category->is_active ? 'active' : 'inactive',
                'products_count' => $category->products_count,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'image' => 'nullable|string', // Base64 image data
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle image upload if provided
        $imagePath = $category->image; // Keep existing image by default
        if (!empty($validatedData['image']) && $validatedData['image'] !== $category->image) {
            $newImagePath = $this->processImage($validatedData['image']);
            if ($newImagePath) {
                $imagePath = $newImagePath;
                // Could delete old image here if needed
            }
        }

        // Transform status to is_active
        $categoryData = [
            'name' => $validatedData['name'],
            'slug' => \Str::slug($validatedData['name']),
            'description' => $validatedData['description'] ?? null,
            'image' => $imagePath,
            'parent_id' => $validatedData['parent_id'] ?? null,
            'is_active' => $validatedData['status'] === 'active',
        ];

        $category->update($categoryData);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'parent_id' => $category->parent_id,
                'status' => $category->is_active ? 'active' : 'inactive',
                'products_count' => $category->products_count ?? 0,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that has products. Please move or delete products first.'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Process base64 image and save to storage.
     */
    private function processImage(string $base64Image): ?string
    {
        try {
            // Extract the base64 data
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                $imageType = strtolower($type[1]); // jpg, png, gif

                // Validate image type
                if (!in_array($imageType, ['jpeg', 'jpg', 'png', 'gif'])) {
                    return null;
                }

                $imageData = base64_decode($imageData);
                
                if ($imageData === false) {
                    return null;
                }

                // Generate unique filename
                $fileName = 'category_' . uniqid() . '.' . ($imageType === 'jpeg' ? 'jpg' : $imageType);
                $filePath = 'categories/' . $fileName;

                // Ensure directory exists
                \Storage::disk('public')->put($filePath, $imageData);
                $fullPath = public_path('storage/' . $filePath);
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }

                // Save the file
                file_put_contents($fullPath, $imageData);

                // Return the URL path
                return asset('storage/app/public/' . $filePath);
            }
        } catch (\Exception $e) {
            \Log::error('Error processing category image: ' . $e->getMessage());
        }

        return null;
    }
}
