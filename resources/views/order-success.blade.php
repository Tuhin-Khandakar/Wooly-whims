@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <!-- Success Celebration -->
    <div class="mb-12 inline-flex items-center justify-center w-24 h-24 bg-brand-sage text-white rounded-[32px] shadow-lg shadow-brand-sage/40 animate-bounce print:hidden">
         <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
    </div>

    <h1 class="font-serif text-[48px] leading-tight font-black text-gray-900 mb-4 print:hidden">Your order is <span class="italic text-brand-sage">confirmed!</span></h1>
    <p class="text-lg text-gray-600 mb-12 print:hidden italic">Congratulations! Your handcrafted treasures are officially on their way to being prepared.</p>

    <!-- Order Info Card -->
    <div id="order-summary" class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden text-left mb-12">
        <div class="bg-gray-50/50 p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Order Number</span>
                <span class="text-xl font-bold font-serif text-gray-900">{{ $order->order_number }}</span>
            </div>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Status</span>
                <span class="px-4 py-1.5 bg-brand-sage/10 text-brand-sage-dark rounded-full font-bold text-sm">Processing</span>
            </div>
        </div>

        <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h3 class="font-serif text-xl font-bold text-gray-800 mb-6">Delivery Details</h3>
                <div class="space-y-4 text-gray-600 text-sm italic">
                    <p><span class="font-bold text-gray-400 uppercase text-[10px] tracking-widest mr-2">Recipient:</span> {{ $order->customer_name }}</p>
                    <p><span class="font-bold text-gray-400 uppercase text-[10px] tracking-widest mr-2">Phone:</span> {{ $order->customer_phone }}</p>
                    <p><span class="font-bold text-gray-400 uppercase text-[10px] tracking-widest mr-2">Social:</span> {{ $order->customer_social }}</p>
                    <p><span class="font-bold text-gray-400 uppercase text-[10px] tracking-widest mr-2">Address:</span> {{ $order->customer_address }}</p>
                    <p><span class="font-bold text-gray-400 uppercase text-[10px] tracking-widest mr-2">Payment:</span> Cash on Delivery (Tk {{ number_format($order->total, 2) }})</p>
                </div>
            </div>

            <div>
                <h3 class="font-serif text-xl font-bold text-gray-800 mb-6">Order Items</h3>
                <div class="space-y-4 italic">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 font-medium">{{ $item->quantity }} &times; {{ $item->product_name }}</span>
                        <span class="font-bold text-gray-900">Tk {{ number_format($item->subtotal, 2) }}</span>
                    </div>
                    @endforeach
                    <div class="pt-4 border-t border-gray-100 flex justify-between items-center font-bold">
                        <span class="text-gray-900">Total Charged</span>
                        <span class="text-brand-sage-dark text-lg">Tk {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Print Signature -->
        <div class="hidden print:block p-8 border-t border-gray-50 text-center">
            <p class="font-serif text-brand-sage-dark font-bold">Thank you for supporting Wooly Whims</p>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-2">Verified Handcrafted Quality</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col items-center justify-center gap-6 print:hidden">
        <p class="text-gray-500 mb-8 italic">We'll message you on Instagram or Facebook shortly to confirm your order details. Please keep an eye on your requests!</p>
            
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="window.print()" class="w-full sm:w-auto bg-gray-100 text-gray-700 px-10 py-4 rounded-full font-bold hover:bg-gray-200 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                Download Receipt
            </button>
            <a href="{{ \App\Helpers\Setting::get('instagram_url', '#') }}" target="_blank" class="w-full sm:w-auto bg-[#2D4A1E] text-white px-10 py-4 rounded-full font-bold hover:bg-[#1f3513] transition shadow-xl flex items-center justify-center gap-2 transform hover:-translate-y-1">
                <svg class="w-5 h-5 text-brand-cream" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                Follow for Updates
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto bg-white border-2 border-gray-100 text-gray-500 px-10 py-4 rounded-full font-bold hover:bg-gray-50 transition flex items-center justify-center">
                Back to Home
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        nav, footer, .print\:hidden { display: none !important; }
        body * { visibility: hidden; }
        #order-summary, #order-summary * { visibility: visible; }
        #order-summary { 
            position: absolute; 
            left: 50%; 
            top: 0; 
            transform: translateX(-50%); 
            width: 100%; 
            border: none !important; 
            box-shadow: none !important; 
        }
    }
</style>
@endsection
