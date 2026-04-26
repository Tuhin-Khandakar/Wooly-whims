<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin - {{ config('app.name', 'Wooly Whims') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if($favicon = \App\Helpers\Setting::get('store_logo'))
            <link rel="icon" type="image/png" href="{{ Storage::url($favicon) }}">
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col sm:flex-row" x-data="{ mobileMenu: false }">
        
        @auth
        <!-- Sidebar for Desktop -->
        <aside class="w-64 bg-[#2D4A1E] text-white flex-shrink-0 hidden sm:flex flex-col border-r border-[#3b5e28]">
            <div class="p-8 border-b border-[#3b5e28] flex items-center gap-3">
                @if(\App\Helpers\Setting::get('store_logo'))
                    <img src="{{ Storage::url(\App\Helpers\Setting::get('store_logo')) }}" class="h-10 w-10 object-contain rounded-lg bg-white/10 p-1">
                @endif
                <div>
                   <h2 class="text-xl font-serif font-bold tracking-tight">Wooly Whims</h2>
                   <p class="text-[10px] uppercase font-black tracking-widest text-green-200 opacity-60">Admin Panel</p>
                </div>
            </div>
            
            <nav class="flex-1 mt-6 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-green-100/70 hover:bg-white/5 hover:text-white' }} font-bold text-sm transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.products.*') ? 'bg-white/10 text-white' : 'text-green-100/70 hover:bg-white/5 hover:text-white' }} font-bold text-sm transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white' : 'text-green-100/70 hover:bg-white/5 hover:text-white' }} font-bold text-sm transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.orders.*') ? 'bg-white/10 text-white' : 'text-green-100/70 hover:bg-white/5 hover:text-white' }} font-bold text-sm transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span>Orders</span>
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.coupons.*') ? 'bg-white/10 text-white' : 'text-green-100/70 hover:bg-white/5 hover:text-white' }} font-bold text-sm transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span>Coupons</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'bg-white/10 text-white' : 'text-green-100/70 hover:bg-white/5 hover:text-white' }} font-bold text-sm transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="12" cy="12" r="3" stroke-width="2"></circle></svg>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 w-full px-4 py-3 rounded-xl font-bold text-sm text-red-200 hover:bg-red-500/10 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Header -->
        <header class="sm:hidden bg-[#2D4A1E] text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                @if(\App\Helpers\Setting::get('store_logo'))
                    <img src="{{ Storage::url(\App\Helpers\Setting::get('store_logo')) }}" class="h-8 w-8 object-contain">
                @endif
                <span class="font-serif font-bold text-lg">Wooly Whims</span>
            </div>
            <button @click="mobileMenu = true" class="p-2 bg-white/10 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
        </header>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-0 z-50 sm:hidden flex">
            <div class="w-80 bg-[#2D4A1E] text-white h-full shadow-2xl p-6 flex flex-col">
                <div class="flex items-center justify-between mb-10">
                    <span class="font-serif font-bold text-2xl italic">Navigation</span>
                    <button @click="mobileMenu = false" class="text-white/60 hover:text-white">&times; Close</button>
                </div>
                <nav class="space-y-4 flex-1">
                    <a href="{{ route('admin.dashboard') }}" class="block px-6 py-4 rounded-2xl bg-white/5 font-bold hover:bg-white/10">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="block px-6 py-4 rounded-2xl bg-white/5 font-bold hover:bg-white/10">Products</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-6 py-4 rounded-2xl bg-white/5 font-bold hover:bg-white/10">Categories</a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-6 py-4 rounded-2xl bg-white/5 font-bold hover:bg-white/10">Orders</a>
                    <a href="{{ route('admin.coupons.index') }}" class="block px-6 py-4 rounded-2xl bg-white/5 font-bold hover:bg-white/10">Coupons</a>
                    <a href="{{ route('admin.settings.index') }}" class="block px-6 py-4 rounded-2xl bg-white/5 font-bold hover:bg-white/10">Settings</a>
                </nav>
            </div>
            <div @click="mobileMenu = false" class="flex-1 bg-black/50 backdrop-blur-sm"></div>
        </div>
        @endauth
        
        <div class="flex-1 flex flex-col min-w-0">
            @auth
            <header class="bg-white shadow-sm border-b border-gray-100 px-8 py-5 flex items-center justify-between hidden sm:flex sticky top-0 z-20">
                <div class="flex items-center gap-4">
                     <h1 class="font-serif font-black text-2xl text-gray-900 leading-none">@yield('header', 'Dashboard')</h1>
                     <span class="h-4 w-px bg-gray-200"></span>
                     <span class="text-xs font-black uppercase tracking-widest text-gray-400">Wooly Whims Atelier</span>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-4 bg-gray-50/50 pl-6 pr-2 py-2 rounded-full border border-gray-100">
                        <span class="text-sm font-black text-gray-700 uppercase tracking-widest">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->profile_picture)
                            <img src="{{ Storage::url(Auth::user()->profile_picture) }}" class="h-10 w-10 rounded-full object-cover border-2 border-brand-sage">
                        @else
                            <div class="h-10 w-10 rounded-full bg-brand-sage text-white flex items-center justify-center font-black">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
            </header>
            @endauth

            <main class="p-4 sm:p-10 flex-1 bg-gray-50/30 overflow-x-hidden">
                @yield('content')
            </main>
        </div>
    </body>
</html>
