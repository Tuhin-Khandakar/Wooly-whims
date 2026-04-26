@extends('layouts.admin')
@section('header', 'Store Settings')

@section('content')
<div class="max-w-5xl mx-auto italic">
    <h1 class="text-2xl font-bold text-gray-800 mb-8 font-serif">Global Configuration</h1>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Admin Profile -->
        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 space-y-8 border-l-4 border-l-[#2D4A1E]">
            <h3 class="text-xs font-black text-gray-300 uppercase tracking-[0.2em] border-b border-gray-50 pb-4">Personal Profile</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Display Name</label>
                    <input type="text" name="admin_name" value="{{ auth()->user()->name }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Profile Picture</label>
                    <div class="flex items-center gap-4">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ Storage::url(auth()->user()->profile_picture) }}" class="h-12 w-12 rounded-full object-cover bg-gray-100 border-2 border-brand-sage">
                        @else
                           <div class="h-12 w-12 rounded-full bg-brand-sage text-white flex items-center justify-center font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        @endif
                        <input type="file" name="admin_picture" class="flex-1 text-xs text-gray-400">
                    </div>
                </div>
            </div>
        </div>

        <!-- General Info -->
        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 space-y-8">
            <h3 class="text-xs font-black text-gray-300 uppercase tracking-[0.2em] border-b border-gray-50 pb-4">General Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Store Name</label>
                    <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'Wooly Whims' }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Store Logo</label>
                    <div class="flex items-center gap-4">
                        @if(isset($settings['store_logo']))
                            <img src="{{ Storage::url($settings['store_logo']) }}" class="h-12 w-12 object-contain bg-gray-100 rounded-lg">
                        @endif
                        <input type="file" name="store_logo" class="flex-1 text-xs text-gray-400">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Minimum Order Amount (Tk)</label>
                    <input type="number" name="min_order_amount" value="{{ $settings['min_order_amount'] ?? '1500' }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Home Hero Banner</label>
                    <div class="flex items-center gap-4">
                        @if(isset($settings['hero_banner']))
                            <img src="{{ Storage::url($settings['hero_banner']) }}" class="h-12 w-24 object-cover bg-gray-100 rounded-lg">
                        @endif
                        <input type="file" name="hero_banner" class="flex-1 text-xs text-gray-400">
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Logic -->
        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 space-y-8 text-indigo-900 border-l-4 border-l-brand-sage">
            <h3 class="text-xs font-black text-gray-300 uppercase tracking-[0.2em] border-b border-gray-50 pb-4">Fulfillment & Shipping</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Inside Dhaka Charge (Tk)</label>
                    <input type="number" name="delivery_inside" value="{{ $settings['delivery_inside'] ?? '80' }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Outside Dhaka Charge (Tk)</label>
                    <input type="number" name="delivery_outside" value="{{ $settings['delivery_outside'] ?? '120' }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 space-y-8">
            <h3 class="text-xs font-black text-gray-300 uppercase tracking-[0.2em] border-b border-gray-50 pb-4">Social Presence</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50/50 outline-none focus:border-[#2D4A1E] font-bold">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#2D4A1E] text-white px-12 py-5 rounded-2xl font-bold hover:bg-[#1f3513] transition shadow-xl shadow-[#2D4A1E]/20">Save All Configurations</button>
        </div>
    </form>
</div>
@endsection
