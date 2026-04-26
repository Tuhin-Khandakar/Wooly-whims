@extends('layouts.admin')
@section('header', 'Order Management')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <h1 class="text-2xl font-bold text-gray-800">Orders</h1>
    
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3">
        <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:border-[#2D4A1E]">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        
        <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:border-[#2D4A1E]">
        
        <button type="submit" class="bg-[#2D4A1E] text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-[#1f3513]">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-lg text-sm font-bold hover:bg-gray-200 uppercase tracking-tighter">Clear</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-[10px] uppercase tracking-widest border-b border-gray-100">
                    <th class="py-4 px-6 font-bold">Order #</th>
                    <th class="py-4 px-6 font-bold">Customer</th>
                    <th class="py-4 px-6 font-bold">Date</th>
                    <th class="py-4 px-6 font-bold">Total</th>
                    <th class="py-4 px-6 font-bold text-center">Items</th>
                    <th class="py-4 px-6 font-bold text-center">Status</th>
                    <th class="py-4 px-6 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 italic">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/50 transition duration-200">
                    <td class="py-4 px-6 font-bold text-gray-900">{{ $order->order_number }}</td>
                    <td class="py-4 px-6">
                        <div class="font-medium text-gray-800">{{ $order->customer_name }}</div>
                        <div class="text-xs text-gray-400">{{ $order->customer_phone }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="py-4 px-6 font-bold text-gray-900">Tk {{ number_format($order->total, 2) }}</td>
                    <td class="py-4 px-6 text-center text-sm font-medium text-gray-600">
                        {{ $order->items->count() ?? '0' }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-700
                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-700
                            @elseif($order->status == 'delivered') bg-green-100 text-green-700
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-700
                            @endif
                        ">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-block bg-gray-50 text-[#2D4A1E] px-4 py-2 rounded-lg text-xs font-bold border border-gray-100 hover:bg-white transition">View Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-20 text-center text-gray-400 font-serif italic text-lg">No orders matches your criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-50">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
