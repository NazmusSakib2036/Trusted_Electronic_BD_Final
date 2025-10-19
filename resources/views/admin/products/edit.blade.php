@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@push('styles')
<style>
    /* Simple and stable image upload styling */
    .image-upload-area {
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .image-preview-container {
        width: 100%;
        height: 96px; /* h-24 */
        overflow: hidden;
        border-radius: 0.5rem;
        background-color: #f9fafb;
    }
    
    .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    /* Prevent layout shifts */
    .upload-section {
        position: relative;
        width: 100%;
    }
    
    /* Simple hover effects */
    .remove-btn {
        transition: opacity 0.2s ease;
    }
    
    .group:hover .remove-btn {
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div x-data="editProduct({{ $productId }})" x-init="init()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Product</h2>
            <p class="text-gray-600">Update product information</p>
        </div>
        <a href="/admin/products" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Products
        </a>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
        <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-600">Loading product...</p>
    </div>

    <!-- Form -->
    <div x-show="!loading" class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form @submit.prevent="updateProduct()">
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                            <input type="text" x-model="form.name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter product name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                            <input type="text" x-model="form.sku" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter product SKU">
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                            <textarea x-model="form.short_description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Brief product description"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                            <textarea x-model="form.description" rows="6"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Detailed product description"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing & Inventory</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">৳</span>
                                <input type="number" step="0.01" x-model="form.price" required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0.00">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">৳</span>
                                <input type="number" step="0.01" x-model="form.sale_price"
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0.00">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                            <input type="number" x-model="form.stock_quantity" required min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                            <input type="number" step="0.01" x-model="form.weight"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0.00">
                        </div>
                    </div>
                </div>

                <!-- Images & Media -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Images & Media</h3>
                    <div class="space-y-4">
                        <!-- Image Upload Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Images</label>
                            
                            <!-- Upload Area -->
                            <div class="border-2 border-dashed border-gray-300 hover:border-blue-400 rounded-lg p-8 text-center bg-gray-50 hover:bg-blue-50 transition-all duration-200">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                
                                <label for="images" class="cursor-pointer">
                                    <span class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Select Images
                                    </span>
                                    <input id="images" name="images" type="file" class="hidden" multiple accept="image/*" @change="handleImageUpload">
                                </label>
                                
                                <p class="mt-3 text-sm text-gray-600">Drop images here or click to browse</p>
                                <p class="mt-1 text-xs text-gray-500">Supports PNG, JPG, GIF up to 2MB each</p>
                            </div>
                            
                            <!-- Image Preview Grid -->
                            <div x-show="form.images && form.images.length > 0" class="mt-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Selected Images (<span x-text="form.images.length"></span>)</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                    <template x-for="(image, index) in form.images" :key="index">
                                        <div class="relative group">
                                            <div class="bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-blue-300 transition-colors duration-200">
                                                <img :src="getImagePreview(image)" 
                                                     alt="Product image" 
                                                     class="w-full h-24 object-cover"
                                                     style="display: block;">
                                            </div>
                                            <button @click="removeImage(index)" 
                                                    type="button" 
                                                    title="Remove image"
                                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                                                x
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- YouTube Video URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL (Optional)</label>
                            <input type="url" x-model="form.youtube_video_url"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            <p class="mt-1 text-xs text-gray-500">Add a YouTube video to showcase your product</p>
                            
                            <!-- YouTube Preview -->
                            <div x-show="form.youtube_video_url && isValidYouTubeUrl(form.youtube_video_url)" class="mt-3">
                                <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden">
                                    <iframe :src="getYouTubeEmbedUrl(form.youtube_video_url)" 
                                            frameborder="0" allowfullscreen 
                                            class="w-full h-48"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category & Status -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Category</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select x-model="form.category_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Category</option>
                                <template x-for="category in categories" :key="category.id">
                                    <option :value="category.id" x-text="category.name"></option>
                                </template>
                            </select>
                        </div>
                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="flex items-center space-x-4 mt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="form.is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="form.is_featured" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Featured</span>
                                </label>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- SEO -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO (Optional)</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" x-model="form.meta_title"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="SEO title for search engines">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea x-model="form.meta_description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="SEO description for search engines"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-center space-x-3">
                <a href="/admin/products" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200">
                    Cancel
                </a>
                <button type="submit" :disabled="saving" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <svg x-show="saving" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="saving ? 'Updating...' : 'Update Product'"></span>
                </button>
            </div>
        </form>
    </div> <br> 
</div><br> <br> <br>
@endsection

@push('scripts')
<script>
function editProduct(productId) {
    return {
        productId: productId,
        categories: [],
        loading: true,
        saving: false,
        form: {
            name: '',
            sku: '',
            description: '',
            short_description: '',
            price: '',
            sale_price: '',
            stock_quantity: 0,
            category_id: '',
            weight: '',
            is_active: true,
            is_featured: false,
            meta_title: '',
            meta_description: '',
            images: [],
            youtube_video_url: ''
        },
        
        async init() {
            await Promise.all([
                this.loadCategories(),
                this.loadProduct()
            ]);
            this.loading = false;
        },
        
        async loadCategories() {
            try {
                const response = await axios.get(`${window.API_BASE}/categories`);
                this.categories = response.data.data;
            } catch (error) {
                console.error('Error loading categories:', error);
                alert('Error loading categories. Please refresh the page.');
            }
        },

        async loadProduct() {
            try {
                const response = await axios.get(`${window.API_BASE}/products/${this.productId}`);
                const product = response.data.data;
                
                // Populate form with existing data
                this.form = {
                    name: product.name || '',
                    sku: product.sku || '',
                    description: product.description || '',
                    short_description: product.short_description || '',
                    price: product.price || '',
                    sale_price: product.sale_price || '',
                    stock_quantity: product.stock_quantity || 0,
                    category_id: product.category_id || '',
                    weight: product.weight || '',
                    is_active: product.is_active || false,
                    is_featured: product.is_featured || false,
                    meta_title: product.meta_title || '',
                    meta_description: product.meta_description || '',
                    images: product.images || [],
                    youtube_video_url: product.youtube_video_url || ''
                };
            } catch (error) {
                console.error('Error loading product:', error);
                alert('Error loading product. Please try again.');
            }
        },

        handleImageUpload(event) {
            const files = Array.from(event.target.files);
            const maxFileSize = 2 * 1024 * 1024; // 2MB
            const maxImages = 10;
            
            if (this.form.images.length + files.length > maxImages) {
                alert(`You can only upload a maximum of ${maxImages} images.`);
                event.target.value = '';
                return;
            }
            
            files.forEach(file => {
                if (!file.type.startsWith('image/')) {
                    alert(`${file.name} is not a valid image file.`);
                    return;
                }
                
                if (file.size > maxFileSize) {
                    alert(`${file.name} is too large. Maximum file size is 2MB.`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.form.images.push(e.target.result);
                };
                reader.onerror = () => {
                    alert(`Error reading ${file.name}. Please try again.`);
                };
                reader.readAsDataURL(file);
            });
            
            // Clear the input so the same file can be selected again
            event.target.value = '';
        },

        removeImage(index) {
            this.form.images.splice(index, 1);
        },

        getImagePreview(image) {
            return image;
        },

        isValidYouTubeUrl(url) {
            const youtubeRegex = /^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/;
            return youtubeRegex.test(url);
        },

        getYouTubeEmbedUrl(url) {
            if (!url) return '';
            
            let videoId = '';
            
            // Extract video ID from different YouTube URL formats
            if (url.includes('youtube.com/watch?v=')) {
                videoId = url.split('youtube.com/watch?v=')[1].split('&')[0];
            } else if (url.includes('youtu.be/')) {
                videoId = url.split('youtu.be/')[1].split('?')[0];
            } else if (url.includes('youtube.com/embed/')) {
                videoId = url.split('youtube.com/embed/')[1].split('?')[0];
            }
            
            return videoId ? `https://www.youtube.com/embed/${videoId}` : '';
        },
        
        async updateProduct() {
            this.saving = true;
            try {
                const response = await axios.put(`${window.API_BASE}/products/${this.productId}`, this.form);
                alert('Product updated successfully!');
                window.location.href = '/admin/products';
            } catch (error) {
                console.error('Error updating product:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    const firstError = Object.values(errors)[0][0];
                    alert('Validation Error: ' + firstError);
                } else {
                    alert('Error updating product. Please try again.');
                }
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endpush