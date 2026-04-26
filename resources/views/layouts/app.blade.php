<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        @if($favicon = \App\Helpers\Setting::get('store_logo'))
            <link rel="icon" type="image/png" href="{{ Storage::url($favicon) }}">
        @endif
        <!-- SEO -->
        <title>@yield('title', \App\Helpers\Setting::get('store_name', 'Wooly Whims'))</title>
        <meta name="description" content="@yield('meta_description', 'Handcrafted elegance for your cozy lifestyle. Fine wool products designed with care.')">
        
        <!-- Open Graph -->
        <meta property="og:title" content="@yield('title', \App\Helpers\Setting::get('store_name', 'Wooly Whims'))">
        <meta property="og:description" content="@yield('meta_description', 'Handcrafted elegance for your cozy lifestyle.')">
        <meta property="og:image" content="@yield('og_image', asset('favicon.ico'))">
        <meta property="og:type" content="website">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@400;500;600;700;800;900&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-800 bg-brand-cream selection:bg-brand-sage selection:text-white" x-data="{ mobileMenu: false }">
        
        <!-- Global Toast Component -->
        <div x-data="{ 
                show: false, 
                message: '', 
                type: 'success',
                triggerToast(msg, type = 'success') {
                    this.message = msg;
                    this.type = type;
                    this.show = true;
                    setTimeout(() => this.show = false, 2000);
                }
             }" 
             @toast.window="triggerToast($event.detail.message, $event.detail.type)"
             x-init="
                @if(session('success')) triggerToast('{{ session('success') }}', 'success'); @endif
                @if(session('error')) triggerToast('{{ session('error') }}', 'error'); @endif
             "
             class="fixed top-8 right-8 z-[100] pointer-events-none">
            
            <div x-show="show" 
                 x-transition:enter="transition ease-out duration-300 transform translate-x-10 opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform translate-x-10 opacity-0"
                 class="bg-white border-l-4 p-5 rounded-2xl shadow-xl flex items-center gap-4 pointer-events-auto min-w-[300px]"
                 :class="type === 'success' ? 'border-brand-sage' : 'border-red-500'">
                
                <div class="p-2 rounded-full" :class="type === 'success' ? 'bg-brand-sage/10 text-brand-sage' : 'bg-red-50 text-red-500'">
                    <template x-if="type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"></path></svg>
                    </template>
                    <template x-if="type === 'error'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round"></path></svg>
                    </template>
                </div>
                <p class="text-sm font-bold text-gray-800" x-text="message"></p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="bg-brand-cream/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center gap-3">
                            @if($logo = \App\Helpers\Setting::get('store_logo'))
                                <img src="{{ Storage::url($logo) }}" class="h-10 w-auto">
                            @endif
                            <span class="font-serif text-2xl font-bold tracking-tight text-brand-sage-dark">{{ \App\Helpers\Setting::get('store_name', 'Wooly Whims') }}</span>
                        </a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-10">
                        <a href="{{ route('home') }}" class="text-sm uppercase tracking-widest font-bold hover:text-brand-sage transition {{ request()->routeIs('home') ? 'text-brand-sage' : 'text-gray-500' }}">Home</a>
                        <a href="{{ route('shop') }}" class="text-sm uppercase tracking-widest font-bold hover:text-brand-sage transition {{ request()->routeIs('shop') ? 'text-brand-sage' : 'text-gray-500' }}">The Shop</a>
                        <a href="{{ route('cart.index') }}" class="relative group">
                             <div class="flex items-center text-sm uppercase tracking-widest font-bold text-gray-500 group-hover:text-brand-sage transition">
                                 <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"></path></svg>
                                 Bag (<span id="cart-count-nav">{{ count(Session::get('cart', [])) }}</span>)
                             </div>
                             <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-brand-sage transition-all group-hover:w-full"></span>
                        </a>
                    </div>
                    
                    <!-- Mobile Control -->
                    <div class="flex items-center sm:hidden">
                         <button @click="mobileMenu = true" class="text-gray-900 p-2">
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                         </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Drawer -->
        <div x-cloak x-show="mobileMenu" class="fixed inset-0 z-[60] overflow-hidden">
            <div x-show="mobileMenu" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500" class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="mobileMenu = false"></div>
            <div class="fixed inset-y-0 right-0 flex max-w-full pl-20 uppercase tracking-widest font-black">
                <div x-show="mobileMenu" x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500" class="pointer-events-auto w-screen max-w-md">
                    <div class="flex h-full flex-col overflow-y-scroll bg-brand-cream shadow-2xl">
                        <div class="px-8 py-10 flex justify-between items-center bg-white">
                            <h2 class="font-serif text-2xl font-bold text-brand-sage-dark">Navigation</h2>
                            <button @click="mobileMenu = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="mt-12 px-8 space-y-8 italic">
                            <a @click="mobileMenu = false" href="{{ route('home') }}" class="block text-3xl font-serif font-bold text-gray-800 border-b border-gray-100 pb-4">Home</a>
                            <a @click="mobileMenu = false" href="{{ route('shop') }}" class="block text-3xl font-serif font-bold text-gray-800 border-b border-gray-100 pb-4">Shop All</a>
                            <a @click="mobileMenu = false" href="{{ route('track.index') }}" class="block text-3xl font-serif font-bold text-gray-800 border-b border-gray-100 pb-4">Track Order</a>
                            <a @click="mobileMenu = false" href="{{ route('cart.index') }}" class="block text-3xl font-serif font-bold text-gray-800 border-b border-gray-100 pb-4 flex items-center justify-between">
                                Bag 
                                <span id="cart-count-mobile" class="bg-brand-sage text-white px-4 py-1 rounded-full text-lg shadow-lg">{{ count(Session::get('cart', [])) }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-24 mt-20 italic">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-16 text-center md:text-left">
                    <div class="md:col-span-2 space-y-8">
                         <div class="flex justify-center md:justify-start items-center gap-4">
                             @if($logo = \App\Helpers\Setting::get('store_logo'))
                                 <img src="{{ Storage::url($logo) }}" class="h-12 w-auto">
                             @endif
                             <h2 class="font-serif text-3xl font-bold">
                                 {{ \App\Helpers\Setting::get('store_name', 'Wooly Whims') }}
                             </h2>
                         </div>
                         <p class="text-gray-400 max-w-sm mx-auto md:mx-0 leading-relaxed italic">"Curation of softness, crafted for the souls that value the silent warmth of fine wool."</p>
                         <div class="flex justify-center md:justify-start gap-6">
                            <a href="{{ \App\Helpers\Setting::get('instagram_url', '#') }}" class="text-white hover:text-brand-sage transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                            <a href="{{ \App\Helpers\Setting::get('facebook_url', '#') }}" class="text-white hover:text-brand-sage transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                         </div>
                    </div>
                    <div>
                         <h3 class="text-xs font-black uppercase tracking-[0.3em] mb-8 text-gray-400">Atelier Info</h3>
                         <ul class="space-y-4 text-sm font-bold">
                             <li><a href="{{ route('shop') }}" class="hover:text-brand-sage transition">Full Collection</a></li>
                             <li><a href="{{ route('track.index') }}" class="hover:text-brand-sage transition">Track Order</a></li>
                             <li><a href="{{ route('pages.shipping') }}" class="hover:text-brand-sage transition">Shipping Polices</a></li>
                             <li><a href="{{ route('pages.privacy') }}" class="hover:text-brand-sage transition">Privacy Atelier</a></li>
                         </ul>
                    </div>
                </div>
                
                <!-- Developer Credit Section -->
                <div class="border-t border-gray-800 mt-20 pt-12 text-center space-y-6">
                    <div class="space-y-2">
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-500">Made with love 💌</p>
                        <p class="text-xs font-bold font-serif italic">By <a href="https://tuhinkhandakar.com/" target="_blank" class="text-brand-sage hover:underline decoration-brand-sage underline-offset-4">MD Tuhin Khandakar</a></p>
                    </div>
                    
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-[9px] font-black text-gray-600 tracking-widest uppercase">Founder & CEO</span>
                        <div class="flex gap-4 text-[10px] font-bold">
                            <a href="https://markiety.netlify.app/" target="_blank" class="hover:text-brand-sage transition">MARKIETY</a>
                            <span class="text-gray-700">|</span>
                            <a href="https://markietyitinstitute.netlify.app/" target="_blank" class="hover:text-brand-sage transition">MARKIETY INSTITUTE</a>
                        </div>
                    </div>
                    
                    <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-[9px] font-black uppercase tracking-widest text-gray-600 italic">
                        <p>&copy; {{ date('Y') }} {{ \App\Helpers\Setting::get('store_name', 'Wooly Whims') }}. All rights reserved.</p>
                        <p>Handcrafted by artisans</p>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            // Global AJAX Add to Cart
            document.addEventListener('submit', function(e) {
                if (e.target.closest('form') && e.target.closest('form').action.includes('/cart/add/')) {
                    e.preventDefault();
                    const form = e.target.closest('form');
                    const url = form.action;
                    const formData = new FormData(form);
                    
                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Update Nav Cart Count
                            const navCount = document.getElementById('cart-count-nav');
                            const mobileCount = document.getElementById('cart-count-mobile');
                            if (navCount) navCount.innerText = data.cart_count;
                            if (mobileCount) mobileCount.innerText = data.cart_count;
                            
                            // Trigger Toast
                            window.dispatchEvent(new CustomEvent('toast', { 
                                detail: { message: data.message, type: 'success' } 
                            }));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        </script>
    </body>
</html>
