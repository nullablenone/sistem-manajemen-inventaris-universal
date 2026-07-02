@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto my-8">
        <!-- Back Link -->
        <a href="{{ route('categories.index') }}" class="mb-6 inline-flex items-center text-sm text-gray-500 hover:text-teal-600 transition-colors">
            <i class="fad fa-arrow-left mr-2"></i> Back to Categories
        </a>

        <!-- Card Container -->
        <div class="card shadow-md border border-gray-250 bg-white rounded-lg">
            <div class="card-header p-6 border-b border-gray-200">
                <h1 class="h6 font-extrabold text-gray-800 m-0">Create New Category</h1>
                <p class="text-xs text-gray-500 mt-1.5">Add a new category classification for your products</p>
            </div>
            
            <div class="card-body p-6">
                <form action="{{ route('categories.store') }}" method="POST" id="createCategoryForm">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-xs font-bold text-gray-600 uppercase mb-2">Category Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('name') border-red-500 @enderror" 
                               placeholder="e.g. Electronic Devices" required autofocus>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Slug Field -->
                    <div class="mb-6">
                        <label for="slug" class="block text-xs font-bold text-gray-600 uppercase mb-2">Slug</label>
                        <div class="relative">
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                                   class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 font-mono @error('slug') border-red-500 @enderror" 
                                   placeholder="electronic-devices" required>
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5">Unique URL-friendly identifier, generated automatically from the name.</p>
                        @error('slug')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6 mt-8">
                        <button type="submit" class="btn-bs-primary py-2 px-6 shadow hover:opacity-90 font-semibold text-sm">
                            <i class="fad fa-save mr-2"></i> Save Category
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn-gray py-2 px-6 font-semibold text-sm">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS script for slugification -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('input', function() {
                const nameVal = this.value;
                const slugVal = nameVal.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // remove special chars
                    .replace(/\s+/g, '-')         // replace space with dash
                    .replace(/-+/g, '-');         // collapse duplicate dashes
                slugInput.value = slugVal;
            });
        });
    </script>
@endsection
