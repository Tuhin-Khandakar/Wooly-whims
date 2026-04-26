@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative bg-brand-cream pt-20 pb-32 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-2xl">
            <h1 class="font-serif text-[56px] leading-[1.1] font-black text-gray-900 mb-6 drop-shadow-sm">
                Handcrafted <br><span class="italic text-brand-sage-dark font-normal">with love</span>
            </h1>
            <p class="text-lg text-gray-600 mb-10 max-w-md leading-relaxed">
                Discover our curated collection of artisanal pieces, designed to bring warmth and elegance to your home.
            </p>
            <a href="{{ route('shop') }}" class="inline-block bg-brand-sage text-white px-10 py-5 rounded-full font-bold text-lg hover:bg-brand-sage-dark transition shadow-lg shadow-brand-sage/30 hover:scale-105 transform">
                Shop the Collection
            </a>
        </div>
    </div>
    
    <!-- Decorative background elements -->
    <div class="absolute right-0 top-0 w-1/2 h-full hidden lg:block">
        <div class="absolute inset-0 bg-brand-sage/5 rounded-l-[100px] transform translate-x-20"></div>
        <div class="absolute top-1/2 right-20 -translate-y-1/2 w-[480px] h-[600px] rounded-[60px] overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-700">
            @php
                $heroBanner = \App\Helpers\Setting::get('hero_banner');
                $heroUrl = $heroBanner ? Storage::url($heroBanner) : asset('storage/assets/hero-banner.png');
            @endphp
            <img src="{{ $heroUrl }}" class="w-full h-full object-cover" alt="Wooly Whims Artisanal Collection">
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-brand-sage font-bold uppercase tracking-[0.2em] text-xs mb-2 block">Departments</span>
                <h2 class="font-serif text-4xl font-bold italic text-gray-800">Browse Categories</h2>
            </div>
            <a href="{{ route('shop') }}" class="text-brand-sage font-bold hover:underline transition">View All Categories &rarr;</a>
        </div>
        
        <div class="flex overflow-x-auto pb-8 space-x-6 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0 sm:grid sm:grid-cols-3 lg:grid-cols-6 sm:space-x-0 sm:gap-6">
            @forelse($categories as $category)
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="flex-shrink-0 w-48 sm:w-auto group">
                    <div class="aspect-square rounded-full overflow-hidden mb-4 border-4 border-brand-cream group-hover:border-brand-sage transition-all duration-300">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-center font-bold text-gray-700 group-hover:text-brand-sage transition">{{ $category->name }}</h3>
                </a>
            @empty
                <p class="text-gray-400 col-span-full text-center py-10 italic">Categories coming soon.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-24 bg-brand-cream/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-brand-sage font-bold uppercase tracking-[0.2em] text-xs mb-2 block">Curated Pick</span>
            <h2 class="font-serif text-4xl font-bold italic text-gray-800">Featured Creations</h2>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            @forelse($featured_products as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <div class="col-span-full py-20 text-center text-gray-400 italic">
                    Our featured collection is being handcrafted. Check back soon!
                </div>
            @endforelse
        </div>
        
        <div class="mt-16 text-center">
            <a href="{{ route('shop') }}" class="inline-block border-2 border-brand-sage text-brand-sage px-12 py-4 rounded-full font-bold hover:bg-brand-sage hover:text-white transition transform hover:scale-105">
                Explore All Products
            </a>
        </div>
    </div>
</section>

<!-- Newsletter / Pinterest Vibe -->
<section class="py-24 bg-white relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 bg-brand-sage/10 rounded-[40px] p-12 md:p-20 text-center relative z-10">
        <h2 class="font-serif text-[40px] leading-tight font-bold text-gray-900 mb-6">Join our cozy community</h2>
        <p class="text-gray-600 mb-10 max-w-lg mx-auto">Subscribe for styling tips, exclusive previews of new collections, and a little inspiration for your handcrafted life.</p>
        
        <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input type="email" placeholder="Your email address" class="flex-1 px-6 py-4 rounded-full border border-gray-200 focus:ring-brand-sage focus:border-brand-sage outline-none shadow-sm">
            <button class="bg-gray-800 text-white px-8 py-4 rounded-full font-bold hover:bg-gray-700 transition shadow-lg">Subscribe</button>
        </form>
    </div>
</section>
@endsection
