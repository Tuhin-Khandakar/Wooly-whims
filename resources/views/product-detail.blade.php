@extends('layouts.app')

@section('title', $product->name . ' | ' . \App\Helpers\Setting::get('store_name', 'Wooly Whims'))
@section('meta_description', $meta_description)
@section('og_image', Storage::url($product->thumbnail))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20" x-data="{ mainImage: '{{ Storage::url($product->thumbnail) }}', quantity: 1 }">
    <div class="flex flex-col lg:flex-row gap-16">
        
        <!-- Image Gallery -->
        <div class="w-full lg:w-1/2">
            <div class="aspect-[4/5] rounded-[32px] overflow-hidden bg-white border border-gray-100 shadow-sm mb-6">
                <img :src="mainImage" class="w-full h-full object-cover transition-opacity duration-300" alt="{{ $product->name }}">
            </div>
            
            <div class="grid grid-cols-4 gap-4">
                <div @click="mainImage = '{{ Storage::url($product->thumbnail) }}'" class="aspect-square rounded-2xl overflow-hidden border-2 cursor-pointer transition" :class="mainImage == '{{ Storage::url($product->thumbnail) }}' ? 'border-brand-sage' : 'border-transparent hover:border-gray-200'">
                    <img src="{{ Storage::url($product->thumbnail) }}" class="w-full h-full object-cover">
                </div>
                @foreach($product->images as $image)
                    <div @click="mainImage = '{{ Storage::url($image->image_path) }}'" class="aspect-square rounded-2xl overflow-hidden border-2 cursor-pointer transition" :class="mainImage == '{{ Storage::url($image->image_path) }}' ? 'border-brand-sage' : 'border-transparent hover:border-gray-200'">
                        <img src="{{ Storage::url($image->image_path) }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Product Details -->
        <div class="w-full lg:w-1/2 space-y-8">
            <div class="space-y-4">
                <span class="text-brand-sage font-bold uppercase tracking-[0.2em] text-xs px-3 py-1 bg-brand-sage/10 rounded-full">{{ $product->category->name }}</span>
                <h1 class="font-serif text-[44px] leading-tight font-black text-gray-900">{{ $product->name }}</h1>
                
                <div class="flex items-center space-x-4">
                    @if($product->sale_price)
                        <span class="text-3xl font-bold text-brand-sage-dark">Tk {{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-xl text-gray-400 line-through">Tk {{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-3xl font-bold text-gray-800">Tk {{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
            </div>

            <div class="prose prose-brand text-gray-600 max-w-none leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>

            <div class="space-y-6 pt-4">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-bold text-gray-900 uppercase tracking-wider">Quantity</span>
                    <div class="flex items-center border border-gray-200 rounded-full p-1 bg-white">
                        <button @click="if(quantity > 1) quantity--" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-50 transition">-</button>
                        <input type="number" x-model="quantity" class="w-12 text-center border-none focus:ring-0 font-bold" readonly>
                        <button @click="quantity++" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-50 transition">+</button>
                    </div>
                    <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-medium italic">
                        {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' available)' : 'Out of Stock' }}
                    </span>
                </div>

                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" :value="quantity">
                    <button type="submit" class="w-full md:w-auto px-12 py-5 bg-brand-sage text-white rounded-full font-bold text-lg hover:bg-brand-sage-dark transition shadow-lg shadow-brand-sage/30 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:scale-105 transform' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        Add to Shopping Bag
                    </button>
                </form>
            </div>

            <!-- Perks -->
            <div class="grid grid-cols-2 gap-4 pt-10 border-t border-gray-100">
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-brand-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span>Sustainably Sourced</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-brand-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span>Handmade in Bangladesh</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-brand-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span>Fast Shipping</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($related_products->count() > 0)
    <div class="mt-32 pt-20 border-t border-gray-100">
        <h2 class="font-serif text-3xl font-bold italic text-gray-800 mb-12 text-center">You might also like...</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($related_products as $related)
                @include('components.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
