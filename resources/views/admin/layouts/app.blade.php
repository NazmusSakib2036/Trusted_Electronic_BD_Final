<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - TrustedElectronics</title>
    
    <!-- Favicon and Logo for Browser Tab -->
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/app/public/products/logo.jpg') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/app/public/products/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/app/public/products/logo.jpg') }}">
    <meta name="msapplication-TileImage" content="{{ asset('storage/app/public/products/logo.jpg') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJ8g/L0Yy0W1R5u/uY5sXyq+Kq5F7eC7y7C/uIq6G9j0o0z/rV4f5l6aG8z0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* General Setup */
        :root {
            --color-primary: #1f2937; /* Gray 900 for sidebar */
            --color-secondary: #ffffff; /* White text/Header/Dropdown BG */
            --color-bg-light: #f9fafb; /* Gray 50 for body/main */
            --color-border-accent: #3b82f6; /* Blue 500 for active border */
            --color-hover-bg: #374151; /* Gray 800 for hover/active sidebar */
            --color-text-main: #111827; /* Gray 900 for main titles */
            --color-text-medium: #6b7280; /* Gray 500 for icons/secondary text */
            --color-text-dark: #4b5563; /* Gray 700 for user name/dropdown link */
            --color-success-bg: #d1fae5;
            --color-success-text: #065f46;
            --color-error-bg: #fee2e2;
            --color-error-text: #991b1b;
        }

        [x-cloak] { display: none !important; }

        /* Crucial Fix: Set HTML and Body to 100% height and remove default margins */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevent body scroll, content area will scroll */
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }

        /* Layout */
        .admin-body {
            background-color: var(--color-bg-light);
            display: flex;
            height: 100vh; /* Use 100vh for full viewport height */
        }
        
        /* -----------------------
           Sidebar Styles 
           ----------------------- */
        .sidebar {
            background-color: var(--color-primary);
            color: var(--color-secondary);
            width: 256px; /* w-64 */
            min-height: 100vh;
            padding: 1rem; /* p-4 */
            transition: all 0.3s ease-in-out;
            flex-shrink: 0;
            /* Mobile/Overlay Setup */
            position: fixed; 
            z-index: 20;
            top: 0;
            bottom: 0;
            height: 100vh; /* Ensure it takes full viewport height */
        }

        .sidebar-hidden {
            display: none;
        }

        @media (min-width: 768px) { /* md:block */
            .sidebar {
                position: relative; /* On desktop, it's part of the flow */
                display: block !important; /* Always show on MD+ unless toggled off */
            }
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            margin-bottom: 2rem; /* mb-8 */
        }
        
        .sidebar-logo h2 {
            margin-left: 0.75rem; /* ml-3 */
            font-size: 1.25rem; /* text-xl */
            font-weight: 700; /* font-bold */
        }
        
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem; /* space-y-2 */
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem; /* p-3 */
            border-radius: 0.5rem; /* rounded-lg */
            transition: background-color 0.2s;
            text-decoration: none; 
            color: var(--color-secondary); 
        }

        .sidebar-link:hover {
            background-color: var(--color-hover-bg);
        }
        
        .sidebar-link-active {
            background-color: var(--color-hover-bg);
            border-right: 4px solid var(--color-border-accent); /* border-r-4 border-blue-500 */
        }

        .sidebar-link svg {
            width: 1.25rem; /* w-5 */
            height: 1.25rem; /* h-5 */
            margin-right: 0.75rem; /* mr-3 */
        }

        /* -----------------------
           Main Content Area & Header 
           ----------------------- */
        .main-container {
            flex: 1; /* flex-1 */
            display: flex;
            flex-direction: column;
            /* Important: Allow main-container to scroll if needed, 
               but main-content-area handles the primary scroll */
            overflow: hidden; 
        }

        .header {
            background-color: var(--color-secondary);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
            border-bottom: 1px solid #e5e7eb; /* border-b border-gray-200 */
            padding: 1rem; /* p-4 */
            z-index: 10;
            position: sticky; 
            top: 0;
            flex-shrink: 0; /* Important: Prevents header from shrinking */
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .menu-toggle {
            padding: 0.5rem; 
            border-radius: 0.375rem; 
            color: var(--color-text-medium);
            cursor: pointer;
            display: block;
            border: none; 
            background: none; 
        }

        .menu-toggle:hover {
            color: #4b5563; 
            background-color: #f3f4f6; 
        }

        @media (min-width: 768px) {
            .menu-toggle {
                display: none; 
            }
        }

        .page-title {
            margin-left: 0.5rem; 
            font-size: 1.5rem; 
            font-weight: 600; 
            color: var(--color-text-main);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem; 
        }

        /* Notifications/User Menu Buttons */
        .icon-button {
            padding: 0.5rem;
            color: var(--color-text-medium);
            cursor: pointer;
            border: none; 
            background: none; 
        }

        .icon-button:hover {
            color: #4b5563;
        }
        
        .user-menu-toggle {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            font-size: 0.875rem; 
            border-radius: 9999px; 
            color: var(--color-text-medium);
            cursor: pointer;
            border: none; 
            background: none; 
            position: relative; 
        }

        .user-menu-toggle:hover {
            color: #4b5563;
        }

        .avatar-placeholder {
            width: 2rem; 
            height: 2rem; 
            background-color: #d1d5db; 
            border-radius: 9999px; 
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary); 
        }

        .avatar-placeholder svg {
            width: 1.25rem; 
            height: 1.25rem; 
            fill: currentColor;
        }

        .user-name {
            margin-left: 0.5rem; 
            color: var(--color-text-dark);
            font-weight: 500; 
            display: none; 
        }

        @media (min-width: 640px) { 
            .user-name {
                display: block;
            }
        }

        .user-dropdown {
            position: absolute;
            right: 0;
            margin-top: 0.5rem;
            width: 12rem; 
            background-color: var(--color-secondary);
            border-radius: 0.375rem; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
            border: 1px solid rgba(0, 0, 0, 0.05); 
            z-index: 50; 
        }
        
        .user-dropdown-content {
            padding: 0.25rem 0; 
        }

        .user-dropdown-email {
            padding: 0.5rem 1rem; 
            font-size: 0.875rem; 
            color: var(--color-text-medium);
            border-bottom: 1px solid #f3f4f6; 
            word-break: break-all; 
        }
        
        .user-dropdown-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: var(--color-text-dark);
            text-decoration: none;
        }

        .user-dropdown-link:hover {
            background-color: #f3f4f6; 
        }
        
        .dropdown-logout-form button {
            width: 100%;
            text-align: left;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: #dc2626; 
            cursor: pointer;
            border: none;
            background: none;
            display: flex; 
            align-items: center;
        }

        .dropdown-logout-form button:hover {
            background-color: #fef2f2; 
            color: #991b1b; 
        }
        
        /* Main Content Area Fix: Allow this area to grow and be the only part that scrolls */
        .main-content-area {
            flex-grow: 1; /* Key change: Ensures it takes all remaining space */
            overflow-x: hidden;
            overflow-y: auto; /* Key change: Allows content to scroll vertically */
            background-color: var(--color-bg-light);
            padding: 1.5rem; /* p-6 */
        }
        
        /* -----------------------
           Alert Messages (Toast) 
           ----------------------- */
        .alert {
            margin-bottom: 1rem; 
            padding: 0.75rem 1rem; 
            border-radius: 0.25rem; 
            position: relative;
        }

        /* ... rest of the alert styles remain the same ... */
        .alert-success {
            background-color: var(--color-success-bg);
            border: 1px solid #34d399; 
            color: var(--color-success-text);
        }

        .alert-error {
            background-color: var(--color-error-bg);
            border: 1px solid #f87171; 
            color: var(--color-error-text);
        }

        .alert-text {
            display: block; 
            margin-right: 2rem; 
        }

        .alert-close-btn {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            padding: 0.75rem 1rem; 
            cursor: pointer;
            border: none; 
            background: none; 
        }

        .alert-close-btn svg {
            fill: currentColor;
            height: 1.5rem; 
            width: 1.5rem; 
        }

        .alert-success .alert-close-btn svg {
            color: #34d399; 
        }
        
        .alert-error .alert-close-btn svg {
            color: #f87171; 
        }
    </style>
</head>
<body class="admin-body" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen w-full px-2"> 
        <div class="sidebar" 
             :class="sidebarOpen ? 'block' : 'sidebar-hidden'">
            
            <div class="sidebar-logo">
                <h2 class="text-xl font-bold">Trusted Electronic BD</h2>
            </div>
            
            <nav class="sidebar-nav">
                <a href="/admin" 
                    class="sidebar-link {{ request()->is('admin') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Dashboard
                </a>
                
                @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->canManageAdmins())
                <a href="{{ route('admin.admins.index') }}" 
                    class="sidebar-link {{ request()->is('admin/admins*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Admin Management
                </a>
                @endif
                
                <a href="/admin/products" 
                    class="sidebar-link {{ request()->is('admin/products*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM6 18v-6h8v6H6z" clip-rule="evenodd"/>
                    </svg>
                    Products
                </a>
                
                <a href="/admin/categories" 
                    class="sidebar-link {{ request()->is('admin/categories*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    Categories
                </a>
                
                <a href="/admin/orders" 
                    class="sidebar-link {{ request()->is('admin/orders*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h2a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                    Orders
                </a>
                
                <a href="/admin/customers" 
                    class="sidebar-link {{ request()->is('admin/customers*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    Customers
                </a>
                
                <a href="/admin/coupons" 
                    class="sidebar-link {{ request()->is('admin/coupons*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0115 5a3 3 0 013 3v6a3 3 0 01-3 3H5a3 3 0 01-3-3V8a3 3 0 013-3zm4 1.5a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-1a.5.5 0 01-.5-.5V7a.5.5 0 01.5-.5h1zm3 0a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-1a.5.5 0 01-.5-.5V7a.5.5 0 01.5-.5h1zM9 10.5a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-1a.5.5 0 01-.5-.5v-1a.5.5 0 01.5-.5h1zm3 0a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-1a.5.5 0 01-.5-.5v-1a.5.5 0 01.5-.5h1z" clip-rule="evenodd"/>
                    </svg>
                    Coupons
                </a>
                
                @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->role === 'super_admin' || Auth::guard('admin')->user()->role === 'admin'))
                <a href="{{ route('admin.sms.index') }}" 
                    class="sidebar-link {{ request()->is('admin/sms*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    SMS Management
                </a>
                @endif
            </nav>
        </div>
        
        <div x-show="sidebarOpen" x-cloak 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-gray-900 bg-opacity-50 z-10 md:hidden">
        </div>

        <div class="main-container">
            <header class="header">
                <div class="header-content">
                    <div class="header-left">
                        <button @click="sidebarOpen = !sidebarOpen" class="menu-toggle">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        <h1 class="page-title">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>
                    
                    <div class="header-right">
                        <button class="icon-button">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5 5-5h-5m-6 10v-2a6 6 0 00-6-6H5a6 6 0 00-6 6v2m0 0v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                            </svg>
                        </button>
                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="user-menu-toggle">
                                <div class="avatar-placeholder">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="user-name">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="user-dropdown">
                                <div class="user-dropdown-content">
                                    <div class="user-dropdown-email">
                                        {{ Auth::guard('admin')->user()->email ?? 'admin@example.com' }}
                                    </div>
                                    <a href="#" class="user-dropdown-link">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <a href="#" class="user-dropdown-link">
                                        <i class="fas fa-cog mr-2"></i>Settings
                                    </a>
                                    <form action="{{ route('admin.logout') }}" method="POST" class="dropdown-logout-form">
                                        @csrf
                                        <button type="submit">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <main class="main-content-area">
                @if(session('success'))
                    <div class="alert alert-success" x-data="{ show: true }" x-show="show">
                        <span class="alert-text">{{ session('success') }}</span>
                        <button type="button" class="alert-close-btn" @click="show = false">
                            <svg role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error" x-data="{ show: true }" x-show="show">
                        <span class="alert-text">{{ session('error') }}</span>
                        <button type="button" class="alert-close-btn" @click="show = false">
                            <svg role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <script>
        // Wait for the page to load and then set up CSRF token
        document.addEventListener('DOMContentLoaded', function() {
            // Set up CSRF token for AJAX requests when axios is available
            if (window.axios && window.axios.defaults) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }
        });
        
        // Base API URL - use relative path that works with both dev server and XAMPP
        window.API_BASE = '{{ url("/api/admin") }}';
        
        // Global helper functions
        window.showToast = function(message, type = 'success') {
            // You can implement a toast notification system here
            console.log(`${type}: ${message}`);
            // A simple DOM implementation:
            const container = document.querySelector('.main-content-area');
            const newAlert = document.createElement('div');
            newAlert.className = `alert alert-${type} fixed top-4 right-4 z-50 shadow-xl`;
            newAlert.innerHTML = `
                <span class="alert-text">${message}</span>
                <button type="button" class="alert-close-btn" onclick="this.parentElement.remove()">
                    <svg role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </button>
            `;
            container.prepend(newAlert);
            setTimeout(() => {
                newAlert.remove();
            }, 5000); // Auto-remove after 5 seconds
        };
        
        window.confirmDelete = function(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        };
    </script>
    
    @stack('scripts')
</body>
</html>