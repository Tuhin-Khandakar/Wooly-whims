@extends('layouts.app')

@section('content')
<!-- Shop Header -->
<header class="bg-brand-cream pt-20 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl">
            <h1 class="font-serif text-[48px] font-bold text-gray-900 mb-4">Our Store</h1>
            <p class="text-gray-600 text-lg">Explore our full range of handcrafted artisanal goods.</p>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Top Bar: Search and Sort -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('shop') }}" method="GET" class="w-full md:w-1/2 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for products..." class="w-full pl-12 pr-6 py-3 rounded-xl border border-gray-200 focus:ring-brand-sage focus:border-brand-sage outline-none">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
        </form>
        
        <div class="flex items-center space-x-4 w-full md:w-auto justify-end">
            <span class="text-sm text-gray-500 font-medium">Sort by:</span>
            <form action="{{ route('shop') }}" method="GET" id="sortForm">
                @foreach(request()->except('sort') as $key => $value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endforeach
                <select name="sort" onchange="this.form.submit()" class="pl-4 pr-10 py-3 rounded-xl border border-gray-200 focus:ring-brand-sage focus:border-brand-sage outline-none text-sm font-bold bg-white">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="space-y-10 sticky top-32">
                <!-- Categories -->
                <div>
                    <h3 class="font-serif text-xl font-bold mb-6 text-gray-800 border-b border-gray-100 pb-2">Collections</h3>
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('shop', request()->except('category', 'page')) }}" class="flex items-center justify-between group {{ !request('category') ? 'text-brand-sage font-bold' : 'text-gray-600' }}">
                                <span class="group-hover:translate-x-1 transition-transform">All Collections</span>
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-400 font-normal">∞</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('shop', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" class="flex items-center justify-between group {{ request('category') == $cat->slug ? 'text-brand-sage font-bold' : 'text-gray-600' }}">
                                    <span class="group-hover:translate-x-1 transition-transform">{{ $cat->name }}</span>
                                    <span class="text-xs {{ request('category') == $cat->slug ? 'bg-brand-sage text-white' : 'bg-gray-100 text-gray-400' }} px-2 py-1 rounded-full font-normal">
                                        {{ $cat->products_count ?? '0' }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Range -->
                <div>
                    <h3 class="font-serif text-xl font-bold mb-6 text-gray-800 border-b border-gray-100 pb-2">Price Range</h3>
                    <form action="{{ route('shop') }}" method="GET" class="space-y-4">
                        @foreach(request()->except('min_price', 'max_price', 'page') as $key => $value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endforeach
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1 block">Min (Tk)</label>
                                <input type="number" name="min_price" value="{{ request('min_price') }}" class="w-full p-3 rounded-lg border border-gray-200 text-sm outline-none focus:border-brand-sage">
                            </div>
                            <div>
                                <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1 block">Max (Tk)</label>
                                <input type="number" name="max_price" value="{{ request('max_price') }}" class="w-full p-3 rounded-lg border border-gray-200 text-sm outline-none focus:border-brand-sage">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-brand-sage/10 text-brand-sage-dark font-bold py-3 rounded-xl hover:bg-brand-sage hover:text-white transition">Filter Price</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>
                
                <div class="mt-20">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-3xl p-20 text-center border border-dashed border-gray-200">
                    <div class="mb-6 opacity-20">
                         <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-serif font-bold text-gray-800 mb-2">No products found</h2>
                    <p class="text-gray-500 mb-8">We couldn't find anything matching your current filters. Try relaxing your criteria.</p>
                    <a href="{{ route('shop') }}" class="text-brand-sage font-bold hover:underline">Clear all filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
