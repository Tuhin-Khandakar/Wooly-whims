@extends('layouts.admin')
@section('header', 'Products List')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-[#2D4A1E] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#1f3513] transition shadow-sm">
        + Add Product
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                    <th class="py-3 px-6 font-medium">Thumbnail</th>
                    <th class="py-3 px-6 font-medium">Name</th>
                    <th class="py-3 px-6 font-medium">Category</th>
                    <th class="py-3 px-6 font-medium">Price</th>
                    <th class="py-3 px-6 font-medium">Stock</th>
                    <th class="py-3 px-6 font-medium">Status</th>
                    <th class="py-3 px-6 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-6">
                        @if($product->thumbnail)
                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded-lg border border-gray-200">
                        @else
                            <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-6 font-medium text-gray-800">{{ $product->name }}</td>
                    <td class="py-3 px-6 text-gray-600 text-sm">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="py-3 px-6 font-medium text-gray-800">
                        @if($product->sale_price)
                            <span class="text-sm line-through text-gray-400 font-normal">Tk {{ number_format($product->price, 2) }}</span>
                            <span class="text-[#2D4A1E]">Tk {{ number_format($product->sale_price, 2) }}</span>
                        @else
                            Tk {{ number_format($product->price, 2) }}
                        @endif
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-sm {{ $product->stock <= 5 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                            {{ number_format($product->stock) }}
                        </span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-3 px-6 flex items-center space-x-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 transition font-medium">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 px-6 text-center text-gray-500">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
