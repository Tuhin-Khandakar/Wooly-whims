@extends('layouts.app')

@section('title', 'Page Not Found | ' . \App\Helpers\Setting::get('store_name', 'Wooly Whims'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center text-center px-4">
    <div class="max-w-xl italic">
        <h1 class="font-serif text-[120px] font-black text-brand-sage/20 leading-none">404</h1>
        <h2 class="font-serif text-4xl font-bold text-gray-900 -mt-10 mb-6">Lost in the threads?</h2>
        <p class="text-gray-500 text-lg mb-12">The piece you're looking for seems to have been misplaced or never existed. Let's get you back to our collection.</p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-10 py-5 bg-gray-900 text-white rounded-full font-bold hover:bg-black transition shadow-xl">
                Go Home
            </a>
            <a href="{{ route('shop') }}" class="w-full sm:w-auto px-10 py-5 border-2 border-brand-sage text-brand-sage rounded-full font-bold hover:bg-brand-sage hover:text-white transition">
                Return to Shop
            </a>
        </div>
    </div>
</div>
@endsection
