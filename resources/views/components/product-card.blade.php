<div class="group bg-white rounded-[16px] overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
    <a href="{{ route('product.show', $product->slug) }}" class="block relative aspect-[4/5] overflow-hidden bg-gray-50">
        @if($product->thumbnail)
            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif
        
        @if($product->sale_price)
            <span class="absolute top-4 left-4 bg-red-400 text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider">Sale</span>
        @endif
    </a>
    
    <div class="p-6">
        @if($product->category)
            <div class="mb-2">
                <span class="text-[10px] font-bold text-brand-sage-dark bg-brand-sage/10 px-2 py-1 rounded-md uppercase tracking-widest leading-none">
                    {{ $product->category->name }}
                </span>
            </div>
        @endif
        
        <h3 class="font-serif text-lg font-bold text-gray-800 mb-2 truncate">
            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-brand-sage transition">{{ $product->name }}</a>
        </h3>
        
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-2">
                @if($product->sale_price)
                    <span class="text-lg font-bold text-brand-sage-dark">Tk {{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-sm text-gray-400 line-through">Tk {{ number_format($product->price, 2) }}</span>
                @else
                    <span class="text-lg font-bold text-gray-800">Tk {{ number_format($product->price, 2) }}</span>
                @endif
            </div>
        </div>
        
        <form action="{{ route('cart.add', $product) }}" method="POST">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="w-full bg-brand-sage text-white py-3 rounded-xl font-bold hover:bg-brand-sage-dark transition shadow-md shadow-brand-sage/20 transform active:scale-95 duration-200">
                Add to Cart
            </button>
        </form>
    </div>
</div>
