@extends('layouts.admin')
@section('header', 'Categories List')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-[#2D4A1E] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#1f3513] transition shadow-sm">
        + Add Category
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                    <th class="py-3 px-6 font-medium">Image</th>
                    <th class="py-3 px-6 font-medium">Name</th>
                    <th class="py-3 px-6 font-medium">Slug</th>
                    <th class="py-3 px-6 font-medium text-center">Products</th>
                    <th class="py-3 px-6 font-medium">Status</th>
                    <th class="py-3 px-6 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-6">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="h-12 w-12 object-cover rounded-lg border border-gray-200">
                        @else
                            <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-6 font-medium text-gray-800">{{ $category->name }}</td>
                    <td class="py-3 px-6 text-gray-500 text-sm italic">{{ $category->slug }}</td>
                    <td class="py-3 px-6 text-center font-medium text-gray-700">{{ $category->products_count }}</td>
                    <td class="py-3 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-3 px-6 flex items-center space-x-3">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900 transition font-medium">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 px-6 text-center text-gray-500">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
