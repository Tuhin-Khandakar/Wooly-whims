@extends('layouts.admin')
@section('header', 'Edit Coupon: ' . $coupon->code)

@section('content')
<div class="max-w-3xl mx-auto italic">
    <div class="mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-xs font-black text-gray-400 hover:text-gray-600 flex items-center transition uppercase tracking-widest">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            Back to Coupons
        </a>
    </div>

    <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Coupon Code</label>
                    <input type="text" name="code" required value="{{ old('code', $coupon->code) }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                    @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Coupon Type</label>
                    <select name="type" required class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                        <option value="flat" {{ $coupon->type == 'flat' ? 'selected' : '' }}>Flat Discount (Tk)</option>
                        <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Discount Value</label>
                    <input type="number" name="value" step="0.01" required value="{{ old('value', $coupon->value) }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Min Order Amount (Tk)</label>
                    <input type="number" name="min_order" value="{{ old('min_order', $coupon->min_order ?? 0) }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Max Uses (Empty for Unlimited)</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Expiry Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>
            </div>

            <div class="mt-8">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} class="w-5 h-5 text-[#2D4A1E] border-gray-300 rounded focus:ring-[#2D4A1E]">
                    <span class="ml-2 text-sm font-bold text-gray-700">Set as Active</span>
                </label>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-50">
                <button type="submit" class="w-full bg-[#2D4A1E] text-white py-4 rounded-xl font-bold hover:bg-[#1f3513] transition shadow-lg">Update Coupon</button>
            </div>
        </form>
    </div>
</div>
@endsection
