@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20" x-data="cart()">
    <h1 class="font-serif text-[40px] font-bold text-gray-900 mb-12 text-center md:text-left">Your Shopping Bag</h1>

    @if(count($cartItems) > 0)
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Cart Items -->
        <div class="flex-1 space-y-8">
            <template x-for="(item, id) in items" :key="id">
                <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 flex items-center gap-6 transition hover:shadow-md">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden bg-gray-50 flex-shrink-0">
                        <img :src="'/storage/' + item.image" :alt="item.name" class="w-full h-full object-cover">
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <a :href="'/product/' + item.slug" class="font-serif text-lg font-bold text-gray-800 hover:text-brand-sage transition block truncate" x-text="item.name"></a>
                        <p class="text-sm text-gray-400 mt-1" x-text="'Unit price: Tk ' + Number(item.price).toFixed(2)"></p>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center border border-gray-100 rounded-full p-1 bg-gray-50">
                                <button @click="updateQty(id, item.quantity - 1)" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white transition">-</button>
                                <input type="number" :value="item.quantity" class="w-10 text-center border-none focus:ring-0 font-bold bg-transparent text-sm" readonly>
                                <button @click="updateQty(id, item.quantity + 1)" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white transition">+</button>
                            </div>
                            
                            <form :action="'/cart/remove/' + id" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-red-300 hover:text-red-500 uppercase tracking-widest transition">Remove</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="text-right flex-shrink-0 w-24">
                        <span class="font-bold text-gray-800" x-text="'Tk ' + (item.price * item.quantity).toFixed(2)"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Sidebar Summary -->
        <aside class="w-full lg:w-96">
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 space-y-8 sticky top-32">
                <h3 class="font-serif text-2xl font-bold text-gray-800">Order Summary</h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center text-gray-500">
                        <span>Bag Subtotal</span>
                        <span class="font-bold text-gray-800" x-text="'Tk ' + subtotal"></span>
                    </div>
                    
                    <div class="flex justify-between items-center text-gray-500" x-show="discount > 0">
                        <span>Discount Applied</span>
                        <span class="font-bold text-brand-sage" x-text="'-Tk ' + discount"></span>
                    </div>
                    
                    <div class="flex justify-between items-center text-gray-500">
                        <span>Delivery Fee</span>
                        <span class="font-bold text-gray-800" x-text="'Tk ' + Number(delivery).toFixed(2)"></span>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 flex justify-between items-center text-lg">
                        <span class="font-serif font-bold text-gray-900">Final Total</span>
                        <span class="font-bold text-brand-sage-dark text-2xl" x-text="'Tk ' + total"></span>
                    </div>
                </div>

                <!-- Coupon -->
                <div class="pt-6 border-t border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Promo Code</p>
                    <div class="flex gap-2">
                        <input type="text" x-model="couponCode" placeholder="Enter code" class="flex-1 px-4 py-3 rounded-xl border border-gray-100 focus:ring-brand-sage focus:border-brand-sage outline-none text-sm bg-gray-50">
                        <button @click="applyCoupon()" class="bg-gray-800 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-700 transition shadow-md">Apply</button>
                    </div>
                    <p x-show="error" class="text-xs text-red-500 mt-2" x-text="error"></p>
                    <p x-show="success" class="text-xs text-green-500 mt-2" x-text="success"></p>
                </div>

                <a href="{{ route('checkout') }}" class="w-full bg-brand-sage text-white py-5 rounded-full font-bold text-lg hover:bg-brand-sage-dark transition shadow-lg shadow-brand-sage/30 flex justify-center items-center transform hover:scale-105 active:scale-95 duration-200">
                    Proceed to Checkout
                </a>

                <div class="text-center">
                    <p class="text-xs text-gray-400">Taxes are calculated at checkout. Free shipping on orders over Tk 1500.</p>
                </div>
            </div>
        </aside>
    </div>
    @else
    <div class="bg-white rounded-[40px] p-24 text-center border border-gray-100 shadow-sm">
        <div class="mb-10 text-brand-sage/20">
            <svg class="w-32 h-32 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z" stroke-width="1"></path></svg>
        </div>
        <h2 class="font-serif text-3xl font-bold text-gray-800 mb-4">Your bag is empty</h2>
        <p class="text-gray-500 mb-10 max-w-sm mx-auto text-lg leading-relaxed">It looks like you haven't added any of our handcrafted treasures yet.</p>
        <a href="{{ route('shop') }}" class="inline-block bg-brand-sage text-white px-12 py-5 rounded-full font-bold text-lg hover:bg-brand-sage-dark transition shadow-lg shadow-brand-sage/30 hover:scale-105 transform">
            Start Shopping
        </a>
    </div>
    @endif
</div>

<script>
    function cart() {
        return {
            items: @json($cartItems),
            subtotal: '{{ number_format($subtotal, 2) }}',
            discount: '{{ number_format($discount, 2) }}',
            total: '{{ number_format($total, 2) }}',
            delivery: '{{ $delivery }}',
            couponCode: '',
            error: '',
            success: '',
            
            updateQty(id, qty) {
                if (qty < 1) return;
                fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id, quantity: qty })
                })
                .then(res => res.json())
                .then(data => {
                    this.items[id].quantity = qty;
                    this.subtotal = data.subtotal;
                    this.discount = data.discount;
                    this.total = data.total;
                });
            },
            
            applyCoupon() {
                this.error = '';
                this.success = '';
                fetch('/cart/coupon/apply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code: this.couponCode })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'error') {
                        this.error = data.message;
                    } else {
                        this.success = data.message;
                        this.discount = data.discount;
                        this.total = data.total;
                    }
                });
            }
        }
    }
</script>
@endsection
