@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('content')
<div x-data="categoriesManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="text-gray-600">Manage your product categories</p>
        </div>
        <button @click="showCreateForm = true" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Category
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       x-model="filters.search" 
                       @input="filterCategories()"
                       placeholder="Search categories..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select x-model="filters.status" 
                        @change="filterCategories()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                <select x-model="filters.parent" 
                        @change="filterCategories()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    <option value="root">Root Categories</option>
                    <template x-for="category in allCategories" :key="category.id">
                        <option :value="category.id" x-text="category.name"></option>
                    </template>
                </select>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="category in paginatedCategories" :key="category.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <!-- Show image if available -->
                                        <template x-if="category.image">
                                            <img :src="category.image" :alt="category.name" class="h-10 w-10 rounded-lg object-cover">
                                        </template>
                                        <!-- Fallback to gradient with initial -->
                                        <template x-if="!category.image">
                                            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm" x-text="category.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="category.name"></div>
                                        <div class="text-sm text-gray-500" x-text="category.description || 'No description'"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="category.parent_name || 'Root Category'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="{
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full': true,
                                    'bg-green-100 text-green-800': category.status === 'active',
                                    'bg-red-100 text-red-800': category.status === 'inactive'
                                }" x-text="category.status.charAt(0).toUpperCase() + category.status.slice(1)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="category.products_count || 0"></span> products
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="formatDate(category.created_at)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button @click="editCategory(category)" 
                                        class="text-blue-600 hover:text-blue-900">
                                    Edit
                                </button>
                                <button @click="toggleStatus(category)" 
                                        :class="category.status === 'active' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'">
                                    <span x-text="category.status === 'active' ? 'Deactivate' : 'Activate'"></span>
                                </button>
                                <button @click="deleteCategory(category)" 
                                        class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="previousPage()" 
                            :disabled="currentPage === 1"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                        Previous
                    </button>
                    <button @click="nextPage()" 
                            :disabled="currentPage === totalPages"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span x-text="startIndex"></span> to <span x-text="endIndex"></span> of <span x-text="filteredCategories.length"></span> categories
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <button @click="previousPage()" 
                                    :disabled="currentPage === 1"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <template x-for="page in pageNumbers" :key="page">
                                <button @click="goToPage(page)" 
                                        :class="{
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium': true,
                                            'z-10 bg-blue-50 border-blue-500 text-blue-600': page === currentPage,
                                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': page !== currentPage
                                        }"
                                        x-text="page">
                                </button>
                            </template>
                            <button @click="nextPage()" 
                                    :disabled="currentPage === totalPages"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showCreateForm || showEditForm" 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         x-transition>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="showEditForm ? 'Edit Category' : 'Create New Category'"></h3>
                
                <form @submit.prevent="showEditForm ? updateCategory() : createCategory()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" 
                               x-model="categoryForm.name" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea x-model="categoryForm.description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <!-- Category Image Upload -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4">
                                    <label for="category-image" class="cursor-pointer">
                                        <span class="mt-2 block text-sm font-medium text-gray-900">
                                            Drop image here or click to upload
                                        </span>
                                        <input id="category-image" name="category-image" type="file" class="sr-only" accept="image/*" @change="handleImageUpload">
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Preview -->
                        <div x-show="categoryForm.image" class="mt-4">
                            <div class="relative inline-block">
                                <img :src="getImagePreview()" alt="Category image" class="w-20 h-20 object-cover rounded-lg border">
                                <button @click="removeImage()" type="button" 
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                        <select x-model="categoryForm.parent_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Root Category</option>
                            <template x-for="category in allCategories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select x-model="categoryForm.status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" 
                                :disabled="isLoading"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg disabled:opacity-50">
                            <span x-show="!isLoading" x-text="showEditForm ? 'Update Category' : 'Create Category'"></span>
                            <span x-show="isLoading">Processing...</span>
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div x-show="notification.show" 
         x-transition
         :class="{
             'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50': true,
             'bg-green-500 text-white': notification.type === 'success',
             'bg-red-500 text-white': notification.type === 'error'
         }">
        <span x-text="notification.message"></span>
    </div>
</div>

<script>
function categoriesManager() {
    return {
        categories: [],
        allCategories: [],
        filteredCategories: [],
        paginatedCategories: [],
        currentPage: 1,
        perPage: 10,
        totalPages: 1,
        isLoading: false,
        showCreateForm: false,
        showEditForm: false,
        editingCategory: null,
        
        filters: {
            search: '',
            status: '',
            parent: ''
        },
        
        categoryForm: {
            name: '',
            description: '',
            image: '',
            parent_id: '',
            status: 'active'
        },
        
        notification: {
            show: false,
            message: '',
            type: 'success'
        },

        async init() {
            await this.loadCategories();
            this.filterCategories();
        },

        async loadCategories() {
            try {
                this.isLoading = true;
                const response = await fetch(`${window.API_BASE}/categories`);
                if (response.ok) {
                    const data = await response.json();
                    this.categories = data.data || data;
                    this.allCategories = [...this.categories];
                } else {
                    throw new Error('Failed to load categories');
                }
            } catch (error) {
                this.showNotification('Failed to load categories', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        filterCategories() {
            let filtered = [...this.categories];
            
            // Search filter
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                filtered = filtered.filter(category => 
                    category.name.toLowerCase().includes(search) ||
                    (category.description && category.description.toLowerCase().includes(search))
                );
            }
            
            // Status filter
            if (this.filters.status) {
                filtered = filtered.filter(category => category.status === this.filters.status);
            }
            
            // Parent filter
            if (this.filters.parent === 'root') {
                filtered = filtered.filter(category => !category.parent_id);
            } else if (this.filters.parent) {
                filtered = filtered.filter(category => category.parent_id === parseInt(this.filters.parent));
            }
            
            this.filteredCategories = filtered;
            this.totalPages = Math.ceil(filtered.length / this.perPage);
            this.currentPage = 1;
            this.updatePagination();
        },

        updatePagination() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            this.paginatedCategories = this.filteredCategories.slice(start, end);
        },

        get startIndex() {
            return (this.currentPage - 1) * this.perPage + 1;
        },

        get endIndex() {
            return Math.min(this.currentPage * this.perPage, this.filteredCategories.length);
        },

        get pageNumbers() {
            const pages = [];
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, this.currentPage + 2);
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.updatePagination();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.updatePagination();
            }
        },

        goToPage(page) {
            this.currentPage = page;
            this.updatePagination();
        },

        async createCategory() {
            try {
                this.isLoading = true;
                const response = await fetch(`${window.API_BASE}/categories`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.categoryForm)
                });

                if (response.ok) {
                    await this.loadCategories();
                    this.filterCategories();
                    this.closeModal();
                    this.showNotification('Category created successfully!', 'success');
                } else {
                    throw new Error('Failed to create category');
                }
            } catch (error) {
                this.showNotification('Failed to create category', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        editCategory(category) {
            this.editingCategory = category;
            this.categoryForm = {
                name: category.name,
                description: category.description || '',
                image: category.image || '',
                parent_id: category.parent_id || '',
                status: category.status
            };
            this.showEditForm = true;
        },

        async updateCategory() {
            try {
                this.isLoading = true;
                const response = await fetch(`${window.API_BASE}/categories/${this.editingCategory.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.categoryForm)
                });

                if (response.ok) {
                    await this.loadCategories();
                    this.filterCategories();
                    this.closeModal();
                    this.showNotification('Category updated successfully!', 'success');
                } else {
                    throw new Error('Failed to update category');
                }
            } catch (error) {
                this.showNotification('Failed to update category', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async toggleStatus(category) {
            try {
                const newStatus = category.status === 'active' ? 'inactive' : 'active';
                const response = await fetch(`${window.API_BASE}/categories/${category.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ ...category, status: newStatus })
                });

                if (response.ok) {
                    category.status = newStatus;
                    this.showNotification(`Category ${newStatus === 'active' ? 'activated' : 'deactivated'} successfully!`, 'success');
                } else {
                    throw new Error('Failed to update category status');
                }
            } catch (error) {
                this.showNotification('Failed to update category status', 'error');
            }
        },

        async deleteCategory(category) {
            if (!confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`${window.API_BASE}/categories/${category.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    await this.loadCategories();
                    this.filterCategories();
                    this.showNotification('Category deleted successfully!', 'success');
                } else {
                    throw new Error('Failed to delete category');
                }
            } catch (error) {
                this.showNotification('Failed to delete category', 'error');
            }
        },

        closeModal() {
            this.showCreateForm = false;
            this.showEditForm = false;
            this.editingCategory = null;
            this.categoryForm = {
                name: '',
                description: '',
                image: '',
                parent_id: '',
                status: 'active'
            };
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString();
        },

        showNotification(message, type = 'success') {
            this.notification = { show: true, message, type };
            setTimeout(() => {
                this.notification.show = false;
            }, 3000);
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    this.showNotification('Image size must be less than 2MB', 'error');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.categoryForm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.showNotification('Please select a valid image file', 'error');
            }
            
            // Clear the input so the same file can be selected again
            event.target.value = '';
        },

        getImagePreview() {
            return this.categoryForm.image;
        },

        removeImage() {
            this.categoryForm.image = '';
        }
    }
}
</script>
@endsection