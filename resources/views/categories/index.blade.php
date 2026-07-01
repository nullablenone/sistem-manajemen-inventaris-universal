@extends('layouts.app')

@section('content')
    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success mb-6 flex justify-between items-center">
            <span>
                <i class="fad fa-check-circle mr-2"></i> {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-green-800 font-bold focus:outline-none">&times;</button>
        </div>
    @endif

    <!-- Card Container -->
    <div class="card mt-6">
        <!-- Card Header -->
        <div class="card-header flex flex-row justify-between items-center lg:flex-col lg:items-start gap-4">
            <div>
                <h1 class="h5 font-extrabold text-gray-800 m-0">Categories</h1>
                <p class="text-xs text-gray-500 mt-1">Manage your inventory product categories</p>
            </div>
            
            <div class="flex flex-row items-center gap-4 lg:w-full lg:justify-between">
                <!-- Search Form -->
                <form action="{{ route('categories.index') }}" method="GET" class="flex items-center">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" 
                               placeholder="Search categories..." 
                               class="p-2 pl-8 border border-gray-300 rounded text-sm bg-gray-50 focus:outline-none w-64 lg:w-full">
                        <i class="fad fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                    @if($search)
                        <a href="{{ route('categories.index') }}" class="ml-2 text-xs text-gray-500 hover:text-red-500">Clear</a>
                    @endif
                </form>

                <!-- Add Button -->
                <a href="{{ route('categories.create') }}" class="btn-bs-primary flex items-center gap-2 py-2 px-4 shadow hover:opacity-90">
                    <i class="fad fa-plus-circle text-xs"></i>
                    <span>Add Category</span>
                </a>
            </div>
        </div>

        <!-- Card Body Table -->
        <div class="card-body p-0 overflow-x-auto">
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 border-b border-gray-200"># ID</th>
                        <th class="px-6 py-4 border-b border-gray-200">Name</th>
                        <th class="px-6 py-4 border-b border-gray-200">Slug</th>
                        <th class="px-6 py-4 border-b border-gray-200">Created At</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $category->id }}</td>
                            <td class="px-6 py-4">{{ $category->name }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs font-mono font-medium">
                                    {{ $category->slug }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $category->created_at ? $category->created_at->format('M d, Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Edit Link -->
                                    <a href="{{ route('categories.edit', $category->id) }}" 
                                       class="btn-bs-info hover:opacity-85 text-xs py-1 px-3 flex items-center gap-1 rounded text-white bg-yellow-500">
                                        <i class="fad fa-edit text-xs"></i> Edit
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete category \&quot;{{ $category->name }}\&quot;?')" 
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-bs-danger hover:opacity-85 text-xs py-1 px-3 flex items-center gap-1 rounded text-white bg-red-600">
                                            <i class="fad fa-trash-alt text-xs"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fad fa-folder-open text-4xl text-gray-300 mb-2"></i>
                                    <p class="font-medium text-base">No Categories Found</p>
                                    <p class="text-xs text-gray-400 mt-1">Try another search or add a new category to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card Footer (Pagination) -->
        @if($categories->hasPages())
            <div class="card-footer bg-gray-50 border-t border-gray-200 px-6 py-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
