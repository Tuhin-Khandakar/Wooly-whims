@extends('layouts.admin')
@section('header', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Categories
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" x-data="{ name: '{{ addslashes($category->name) }}', slug: '{{ $category->slug }}' }">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                    <input type="text" name="name" x-model="name" @input="slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#2D4A1E] focus:border-[#2D4A1E] outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" x-model="slug" readonly class="w-full px-4 py-2 border border-gray-100 bg-gray-50 text-gray-400 rounded-lg outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Category Image</label>
                    <div class="flex items-start space-x-4">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                        @endif
                        <input type="file" name="image" accept="image/*" class="flex-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                </div>

                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="w-5 h-5 text-[#2D4A1E] border-gray-300 rounded focus:ring-[#2D4A1E]">
                        <span class="ml-2 text-sm text-gray-700 font-medium">Display on storefront</span>
                    </label>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <button type="submit" class="w-full bg-[#2D4A1E] text-white px-8 py-3 rounded-lg font-bold hover:bg-[#1f3513] transition shadow-md">
                        Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
