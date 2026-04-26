@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="mb-10 inline-flex items-center justify-center w-20 h-20 bg-brand-sage/10 text-brand-sage rounded-[28px]">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
    </div>

    <h1 class="font-serif text-[40px] font-bold text-gray-900 mb-4">Track Your <span class="italic text-brand-sage">Order</span></h1>
    <p class="text-gray-600 mb-12 max-w-lg mx-auto italic">Enter your order number to see where your handcrafted treasures are in the crafting and delivery process.</p>

    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-100 italic">
        <form action="{{ route('track.status') }}" method="GET" class="space-y-6">
            @if(session('error'))
                <div class="p-4 bg-red-50 text-red-600 rounded-2xl text-sm font-bold animate-pulse">
                    {{ session('error') }}
                </div>
            @endif

            <div class="text-left">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Order Number</label>
                <input type="text" name="order_number" required placeholder="WW-20260417-XXXXX" 
                       class="w-full px-8 py-5 rounded-[28px] border border-gray-100 bg-gray-50/50 outline-none focus:border-brand-sage focus:ring-4 focus:ring-brand-sage/5 transition font-bold text-gray-800 placeholder:text-gray-300">
            </div>

            <button type="submit" class="w-full bg-[#2D4A1E] text-white py-6 rounded-full font-bold text-lg hover:bg-[#1f3513] transition shadow-xl shadow-brand-sage/10 transform active:scale-95">
                Track Status &rarr;
            </button>
        </form>
    </div>

    <div class="mt-12 text-sm text-gray-400 font-medium">
        Need help? Message us on <a href="{{ \App\Helpers\Setting::get('instagram_url', '#') }}" class="text-brand-sage font-bold hover:underline">Instagram</a>
    </div>
</div>
@endsection
