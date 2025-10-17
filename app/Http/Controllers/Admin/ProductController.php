<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'short_description' => 'nullable|string',
                'sku' => 'required|string|unique:products',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock_quantity' => 'nullable|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'images' => 'nullable|array',
                'images.*' => 'nullable|string', // Base64 encoded images or URLs
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'weight' => 'nullable|numeric',
                'dimensions' => 'nullable|array',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
                'youtube_video_url' => 'nullable|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        // Process images if provided
        $processedImages = [];
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $imageData) {
                if (!empty($imageData)) {
                    $processedImages[] = $this->processImage($imageData);
                }
            }
        }

        try {
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'short_description' => $request->short_description,
                'sku' => $request->sku,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'stock_quantity' => $request->stock_quantity ?? 0,
                'category_id' => $request->category_id,
                'images' => $processedImages,
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured', false),
                'weight' => $request->weight,
                'dimensions' => $request->dimensions,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'youtube_video_url' => $request->youtube_video_url
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load('category')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load('category');
        
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'short_description' => 'nullable|string',
                'sku' => 'required|string|unique:products,sku,' . $product->id,
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock_quantity' => 'nullable|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'images' => 'nullable|array',
                'images.*' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'weight' => 'nullable|numeric',
                'dimensions' => 'nullable|array',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
                'youtube_video_url' => 'nullable|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        // Process images if provided
        $processedImages = $product->images; // Keep existing images by default
        if ($request->has('images') && is_array($request->images)) {
            $processedImages = [];
            foreach ($request->images as $imageData) {
                if (!empty($imageData)) {
                    $processedImages[] = $this->processImage($imageData);
                }
            }
        }

        try {
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'short_description' => $request->short_description,
                'sku' => $request->sku,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'stock_quantity' => $request->stock_quantity ?? $product->stock_quantity,
                'category_id' => $request->category_id,
                'images' => $processedImages,
                'is_active' => $request->boolean('is_active', $product->is_active),
                'is_featured' => $request->boolean('is_featured', $product->is_featured),
                'weight' => $request->weight,
                'dimensions' => $request->dimensions ?? $product->dimensions,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'youtube_video_url' => $request->youtube_video_url
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load('category')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * Get products by category
     */
    public function byCategory(Category $category): JsonResponse
    {
        $products = $category->products()->with('category')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Toggle product featured status
     */
    public function toggleFeatured(Product $product): JsonResponse
    {
        $product->update(['is_featured' => !$product->is_featured]);

        return response()->json([
            'success' => true,
            'message' => 'Product featured status updated',
            'data' => $product
        ]);
    }

    /**
     * Process and save product image
     */
    private function processImage($imageData): string
    {
        // If it's already a URL, return as is
        if (filter_var($imageData, FILTER_VALIDATE_URL)) {
            return $imageData;
        }

        // If it's base64 encoded image data
        if (strpos($imageData, 'data:image/') === 0) {
            // Extract the image data
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $imageData = base64_decode($imageData);
            
            // Generate unique filename
            $filename = 'product_' . time() . '_' . uniqid() . '.jpg';
            $path = 'products/' . $filename;
            
            // Save to storage/app/public/products
             \Storage::disk('public')->put($path, $imageData);
            
            // Return the public URL
            return asset('storage/app/public/' . $path);
        }

        // If it's just a string, treat as filename/URL
        return $imageData;
    }

    /**
     * Upload multiple product images
     */
    public function uploadImages(Request $request): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $filename, 'public');
            $uploadedImages[] = '/storage/' . $path;
        }

        return response()->json([
            'success' => true,
            'message' => 'Images uploaded successfully',
            'data' => $uploadedImages
        ]);
    }
}
