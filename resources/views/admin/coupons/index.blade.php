@extends('layouts.admin')
@section('header', 'Coupon Management')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Coupons</h1>
    <a href="{{ route('admin.coupons.create') }}" class="bg-[#2D4A1E] text-white px-6 py-2 rounded-lg font-bold hover:bg-[#1f3513]">
        + Create Coupon
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl font-bold text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-[10px] uppercase font-black tracking-widest border-b border-gray-100">
                    <th class="py-4 px-6">Code</th>
                    <th class="py-4 px-6 text-center">Type</th>
                    <th class="py-4 px-6 text-center">Value</th>
                    <th class="py-4 px-6 text-center">Used / Max</th>
                    <th class="py-4 px-6 text-right">Expiry</th>
                    <th class="py-4 px-6 text-center">Status</th>
                    <th class="py-4 px-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-gray-50/50 transition duration-200">
                    <td class="py-4 px-6 font-bold text-gray-900">{{ $coupon->code }}</td>
                    <td class="py-4 px-6 text-center italic text-sm">{{ ucfirst($coupon->type) }}</td>
                    <td class="py-4 px-6 text-center font-bold text-[#2D4A1E]">
                        {{ $coupon->type == 'percentage' ? $coupon->value . '%' : 'Tk ' . number_format($coupon->value, 2) }}
                    </td>
                    <td class="py-4 px-6 text-center text-sm font-medium">
                        {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}
                    </td>
                    <td class="py-4 px-6 text-right text-xs text-gray-500">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter
                            {{ $coupon->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-500 hover:text-blue-700 font-bold text-xs">Edit</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('Delete this coupon?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-300 hover:text-red-500 font-bold text-xs uppercase">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-20 text-center text-gray-400 font-serif italic text-lg">No Coupons Found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
