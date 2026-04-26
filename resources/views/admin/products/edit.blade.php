@extends('layouts.admin')
@section('header', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Products
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" x-data="{ name: '{{ addslashes($product->name) }}', slug: '{{ $product->slug }}' }">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    <input type="text" name="name" x-model="name" @input="slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#2D4A1E] focus:border-[#2D4A1E] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" x-model="slug" readonly class="w-full px-4 py-2 border border-gray-100 bg-gray-50 text-gray-400 rounded-lg outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#2D4A1E] focus:border-[#2D4A1E] outline-none">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#2D4A1E] focus:border-[#2D4A1E] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price (Tk)</label>
                    <input type="number" name="price" value="{{ $product->price }}" step="0.01" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#2D4A1E] focus:border-[#2D4A1E] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price (Tk)</label>
                    <input type="number" name="sale_price" value="{{ $product->sale_price }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#2D4A1E] focus:border-[#2D4A1E] outline-none">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#2D4A1E] focus:border-[#2D4A1E] outline-none">{{ $product->description }}</textarea>
            </div>

            <div class="space-y-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Thumbnail</label>
                    <div class="flex items-start space-x-4">
                        @if($product->thumbnail)
                            <img src="{{ Storage::url($product->thumbnail) }}" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="flex-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Add Gallery Images</label>
                    <div class="grid grid-cols-4 md:grid-cols-6 gap-2 mb-4" x-data="{ deletedImages: [] }">
                        @foreach($product->images as $img)
                            <div class="relative group" x-show="!deletedImages.includes({{ $img->id }})">
                                <img src="{{ Storage::url($img->image_path) }}" class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                                <span @click="deletedImages.push({{ $img->id }})" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition cursor-pointer z-10">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </span>
                            </div>
                        @endforeach
                        
                        <template x-for="id in deletedImages" :key="id">
                            <input type="hidden" name="delete_images[]" :value="id">
                        </template>
                    </div>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
            </div>

            <div class="flex items-center space-x-6 mb-8">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="w-5 h-5 text-[#2D4A1E] border-gray-300 rounded focus:ring-[#2D4A1E]">
                    <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-5 h-5 text-[#2D4A1E] border-gray-300 rounded focus:ring-[#2D4A1E]">
                    <span class="ml-2 text-sm text-gray-700">Display (Active)</span>
                </label>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <button type="submit" class="bg-[#2D4A1E] text-white px-8 py-3 rounded-lg font-bold hover:bg-[#1f3513] transition shadow-md">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
