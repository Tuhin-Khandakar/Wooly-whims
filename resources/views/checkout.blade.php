@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <h1 class="font-serif text-[40px] font-bold text-gray-900 mb-12">Checkout</h1>

    <form action="{{ route('checkout.place') }}" method="POST" x-data="{ area: 'inside', insideCharge: {{ \App\Helpers\Setting::get('delivery_inside', 60) }}, outsideCharge: {{ \App\Helpers\Setting::get('delivery_outside', 120) }} }">
        @csrf
        <div class="flex flex-col lg:flex-row gap-16">
            <!-- Delivery Details -->
            <div class="flex-1 space-y-10">
                <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-100">
                    <h2 class="font-serif text-2xl font-bold text-gray-800 mb-8 border-b border-gray-50 pb-4">Shipping Information</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Full Name</label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name') }}" class="w-full px-6 py-4 rounded-2xl border border-gray-200 focus:ring-brand-sage focus:border-brand-sage outline-none bg-gray-50/50">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Phone Number</label>
                            <input type="tel" name="customer_phone" required value="{{ old('customer_phone') }}" class="w-full px-6 py-4 rounded-2xl border border-gray-200 focus:ring-brand-sage focus:border-brand-sage outline-none bg-gray-50/50" placeholder="017xxxxxxxx">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Instagram / Facebook Handle</label>
                            <input type="text" name="customer_social" required value="{{ old('customer_social') }}" class="w-full px-6 py-4 rounded-2xl border border-gray-200 focus:ring-brand-sage focus:border-brand-sage outline-none bg-gray-50/50" placeholder="@username or Profile Link">
                            <p class="text-[10px] text-brand-sage font-bold mt-2 uppercase tracking-tight">We verify all orders via Social Media before fulfillment</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Delivery Area</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center justify-center p-4 border rounded-2xl cursor-pointer transition" :class="area === 'inside' ? 'border-brand-sage bg-brand-sage/5' : 'border-gray-100 bg-white hover:border-gray-200'">
                                    <input type="radio" name="area" value="inside" x-model="area" class="hidden">
                                    <span class="text-sm font-bold" :class="area === 'inside' ? 'text-brand-sage-dark' : 'text-gray-500'">Inside Dhaka (Tk <span x-text="insideCharge"></span>)</span>
                                </label>
                                <label class="relative flex items-center justify-center p-4 border rounded-2xl cursor-pointer transition" :class="area === 'outside' ? 'border-brand-sage bg-brand-sage/5' : 'border-gray-100 bg-white hover:border-gray-200'">
                                    <input type="radio" name="area" value="outside" x-model="area" class="hidden">
                                    <span class="text-sm font-bold" :class="area === 'outside' ? 'text-brand-sage-dark' : 'text-gray-500'">Outside Dhaka (Tk <span x-text="outsideCharge"></span>)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Complete Address</label>
                            <textarea name="customer_address" required rows="3" class="w-full px-6 py-4 rounded-2xl border border-gray-200 focus:ring-brand-sage focus:border-brand-sage outline-none bg-gray-50/50" placeholder="House no, Street, Landmark...">{{ old('customer_address') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Order Notes (Optional)</label>
                            <textarea name="notes" rows="2" class="w-full px-6 py-4 rounded-2xl border border-gray-200 focus:ring-brand-sage focus:border-brand-sage outline-none bg-gray-50/50">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-100">
                    <h2 class="font-serif text-2xl font-bold text-gray-800 mb-8 border-b border-gray-50 pb-4">Payment Method</h2>
                    <div class="flex items-center p-6 bg-brand-sage/5 border-2 border-brand-sage rounded-[32px]">
                         <div class="w-12 h-12 bg-brand-sage text-white rounded-full flex items-center justify-center mr-4">
                             <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                         </div>
                         <div>
                             <p class="font-bold text-gray-900">Cash on Delivery</p>
                             <p class="text-sm text-gray-500">Pay only when you receive your handmade treasures.</p>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <aside class="w-full lg:w-96">
                <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-100 sticky top-32 space-y-8">
                    <h3 class="font-serif text-2xl font-bold text-gray-800">Your Bag</h3>
                    
                    <div class="space-y-4 max-h-64 overflow-y-auto pr-2 scrollbar-hide">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                                <img src="{{ Storage::url($item['image']) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-400">Qty: {{ $item['quantity'] }} &times; Tk {{ number_format($item['price'], 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="pt-6 border-t border-gray-50 space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">Subtotal</span>
                            <span class="font-bold text-gray-800">Tk {{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($discount > 0)
                        <div class="flex justify-between items-center text-sm text-brand-sage">
                            <span class="font-medium">Coupon Discount</span>
                            <span class="font-bold">-Tk {{ number_format($discount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">Delivery Fee</span>
                            <span class="font-bold text-gray-800" x-text="'Tk ' + (area === 'inside' ? insideCharge : outsideCharge).toFixed(2)"></span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex justify-between items-center text-xl">
                            <span class="font-serif font-black text-gray-900 uppercase tracking-tighter">Total Payable</span>
                            <span class="font-black text-brand-sage-dark" 
                                  x-text="'Tk ' + ({{ $subtotal }} - {{ $discount }} + (area === 'inside' ? insideCharge : outsideCharge)).toFixed(2)">
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gray-900 text-white py-6 rounded-full font-bold text-lg hover:bg-black transition shadow-xl active:scale-95 transform">
                        Complete Order &rarr;
                    </button>

                    <p class="text-xs text-center text-gray-400">By placing this order you agree to our Terms of Service.</p>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection
