@extends('layouts.admin')
@section('header', 'Order Detail: ' . $order->order_number)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 flex items-center transition uppercase tracking-widest">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        Back to Orders
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl font-bold text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Order Details -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Status Switcher -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 italic">
            <h3 class="text-xs font-black text-gray-300 uppercase tracking-[0.2em] mb-4">Manage Status</h3>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex items-center gap-4">
                @csrf
                <select name="status" class="flex-1 px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-[#2D4A1E] font-bold text-gray-800 bg-gray-50/50">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="bg-[#2D4A1E] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#1f3513] transition shadow-md">Update</button>
            </form>
        </div>

        <!-- Items Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Ordered Items</h3>
                <span class="text-xs bg-gray-100 px-3 py-1 rounded-full text-gray-500 font-bold uppercase tracking-wider">{{ $order->items->count() }} Items</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                             <th class="py-4 px-8">Product Name</th>
                             <th class="py-4 px-8 text-center">Qty</th>
                             <th class="py-4 px-8 text-right">Price</th>
                             <th class="py-4 px-8 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <tr class="italic text-gray-700">
                            <td class="py-6 px-8 flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($item->product && $item->product->thumbnail)
                                        <img src="{{ Storage::url($item->product->thumbnail) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <span class="font-bold underline decoration-brand-sage/30">{{ $item->product_name }}</span>
                            </td>
                            <td class="py-6 px-8 text-center font-bold">{{ $item->quantity }}</td>
                            <td class="py-6 px-8 text-right text-sm">Tk {{ number_format($item->product_price, 2) }}</td>
                            <td class="py-6 px-8 text-right font-black text-gray-900">Tk {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50/50 p-8 space-y-3">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-bold text-gray-800">Tk {{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-between text-sm text-[#2D4A1E]">
                    <span>Discount ({{ $order->coupon_code }})</span>
                    <span class="font-bold">- Tk {{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Delivery Fee</span>
                    <span class="font-bold text-gray-800">Tk {{ number_format($order->delivery_charge, 2) }}</span>
                </div>
                <div class="flex justify-between text-xl font-black text-gray-900 pt-4 border-t border-gray-100">
                    <span>Final Amount</span>
                    <span class="text-[#2D4A1E]">Tk {{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Customer & Shipping Summary -->
    <div class="space-y-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6">
            <h3 class="text-xs font-black text-gray-300 uppercase tracking-[0.2em] border-b border-gray-50 pb-4">Customer Details</h3>
            
            <div class="space-y-4 italic">
                <div>
                    <span class="block text-[10px] uppercase font-black text-gray-400 mb-1">Name</span>
                    <p class="font-bold text-gray-800">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-black text-gray-400 mb-1">Phone</span>
                    <p class="font-bold text-gray-800">{{ $order->customer_phone }}</p>
                    <a href="tel:{{ $order->customer_phone }}" class="text-[10px] text-blue-500 font-bold hover:underline">Call &rarr;</a>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-black text-gray-400 mb-1">Instagram / Facebook</span>
                    <p class="font-bold text-brand-sage-dark">{{ $order->customer_social }}</p>
                    <p class="text-[9px] text-gray-400">Message them to confirm order</p>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-black text-gray-400 mb-1">Full Address</span>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $order->customer_address }}</p>
                </div>
                @if($order->notes)
                <div>
                    <span class="block text-[10px] uppercase font-black text-gray-400 mb-1">Customer Notes</span>
                    <p class="text-xs bg-yellow-50 p-4 rounded-xl border border-yellow-100 text-yellow-800 leading-relaxed">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-[#2D4A1E] p-8 rounded-2xl shadow-xl text-white">
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] mb-4 opacity-70">Payment Info</h3>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-200" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
                </div>
                <div>
                    <p class="font-black text-lg">Tk {{ number_format($order->total, 2) }}</p>
                    <p class="text-[10px] uppercase font-bold opacity-60">Cash on Delivery</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
