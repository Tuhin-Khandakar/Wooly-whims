@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="mb-12 flex justify-between items-center">
        <a href="{{ route('track.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 flex items-center transition uppercase tracking-widest">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            Track another order
        </a>
    </div>

    <!-- Status Timeline -->
    <div class="bg-white p-10 md:p-16 rounded-[40px] shadow-sm border border-gray-100 mb-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-brand-sage/5 rounded-full -mr-16 -mt-16"></div>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-16 relative z-10">
            <div>
                <span class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] block mb-2">Current Progress</span>
                <h1 class="font-serif text-3xl font-bold text-gray-900 italic">Order #{{ $order->order_number }}</h1>
            </div>
            <div class="px-6 py-2 bg-brand-sage/10 text-brand-sage-dark rounded-full font-black uppercase text-xs tracking-widest">
                {{ strtoupper($order->status) }}
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="relative py-8">
            <!-- Line -->
            <div class="absolute left-6 md:left-0 md:top-1/2 md:-translate-y-1/2 w-0.5 md:w-full h-full md:h-0.5 bg-gray-100"></div>
            
            @php
                $statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
                $currentIndex = array_search($order->status, $statusOrder);
                if ($order->status == 'cancelled') $currentIndex = -1;
            @endphp

            <div class="relative flex flex-col md:flex-row justify-between gap-12 md:gap-4">
                @foreach(['Order Placed', 'Crafting Step', 'Shipped', 'Delivered'] as $index => $label)
                    @php
                        $isCompleted = $currentIndex >= $index;
                        $isCurrent = $currentIndex == $index;
                        $isPending = $currentIndex < $index;
                    @endphp
                    <div class="flex md:flex-col items-center gap-6 md:gap-4 flex-1">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center relative z-10 transition-all duration-500
                            {{ $isCompleted ? 'bg-brand-sage text-white shadow-lg shadow-brand-sage/30' : 'bg-white border-2 border-gray-100 text-gray-300' }}
                            {{ $isCurrent ? 'ring-4 ring-brand-sage/10 scale-110' : '' }}">
                            @if($isCompleted)
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            @else
                                <span class="font-black text-xs">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="text-left md:text-center">
                            <p class="text-[10px] font-black uppercase tracking-widest {{ $isCompleted ? 'text-gray-900' : 'text-gray-300' }}">{{ $label }}</p>
                            @if($isCurrent)
                                <p class="text-[9px] text-brand-sage font-bold italic">Current Phase</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($order->status == 'cancelled')
            <div class="mt-12 p-6 bg-red-50 rounded-3xl border border-red-100 text-center">
                <p class="text-red-700 font-bold italic">This order has been cancelled.</p>
            </div>
        @elseif($order->status == 'processing')
             <div class="mt-16 text-center italic text-gray-500 text-sm border-t border-gray-50 pt-8">
                 <p>Your treasures are being handcrafted with care. <br> Please allow 10-15 days for the artistic process and delivery.</p>
             </div>
        @endif
    </div>

    <!-- Quick Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 italic">
        <div class="bg-gray-50/50 p-10 rounded-[40px] border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Delivery Summary</h3>
            <div class="space-y-4 text-sm">
                <p><span class="text-gray-400 font-bold mr-2">Sent to:</span> {{ $order->customer_name }}</p>
                <p><span class="text-gray-400 font-bold mr-2">Location:</span> {{ $order->customer_address }}</p>
            </div>
        </div>
        <div class="bg-brand-sage p-10 rounded-[40px] shadow-xl text-white">
            <h3 class="text-xs font-black opacity-60 uppercase tracking-widest mb-6">Need Assistance?</h3>
            <p class="text-sm mb-6 leading-relaxed">If you have any questions regarding your delivery or the status of your treasures, please message us on social media.</p>
            <a href="{{ \App\Helpers\Setting::get('instagram_url', '#') }}" target="_blank" class="inline-block bg-white text-brand-sage px-6 py-3 rounded-full font-bold text-xs hover:bg-brand-cream transition">
                Message Us &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
