@extends('layouts.app')

@section('content')
    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success mb-6 flex justify-between items-center bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded shadow-sm">
            <span>
                <i class="fad fa-check-circle mr-2 text-green-500"></i> {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-green-800 font-bold focus:outline-none hover:text-green-950">&times;</button>
        </div>
    @endif

    <!-- Card Container -->
    <div class="card mt-6 shadow-md border border-gray-100 bg-white rounded-lg">
        <!-- Card Header -->
        <div class="card-header flex flex-row justify-between items-center lg:flex-col lg:items-start gap-4 p-6 border-b border-gray-100">
            <div>
                <h1 class="h5 font-extrabold text-gray-800 m-0">Attributes</h1>
                <p class="text-xs text-gray-500 mt-1">Manage your product options and variant attributes (e.g., Size, Color)</p>
            </div>
            
            <div class="flex flex-row items-center gap-4 lg:w-full lg:justify-between">
                <!-- Search Form -->
                <form action="{{ route('attributes.index') }}" method="GET" class="flex items-center">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" 
                               placeholder="Search attributes..." 
                               class="p-2 pl-8 border border-gray-200 rounded text-sm bg-gray-50 focus:outline-none focus:border-teal-500 w-64 lg:w-full transition-colors">
                        <i class="fad fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                    @if($search)
                        <a href="{{ route('attributes.index') }}" class="ml-2 text-xs text-gray-500 hover:text-red-500 transition-colors">Clear</a>
                    @endif
                </form>

                <!-- Add Button -->
                <a href="{{ route('attributes.create') }}" class="btn-bs-primary flex items-center gap-2 py-2 px-4 shadow hover:opacity-90 transition-opacity bg-teal-600 text-white rounded font-medium text-sm">
                    <i class="fad fa-plus-circle text-xs"></i>
                    <span>Add Attribute</span>
                </a>
            </div>
        </div>

        <!-- Card Body Table -->
        <div class="card-body p-0 overflow-x-auto">
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold"># ID</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold w-1/4">Attribute Name</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold w-1/2">Values</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Created At</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($attributes as $attribute)
                        <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $attribute->id }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $attribute->name }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5 py-1">
                                    @forelse($attribute->values as $val)
                                        <span class="bg-teal-50 text-teal-700 border border-teal-150 px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm transition-all hover:bg-teal-100">
                                            {{ $val->value }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">No values defined</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $attribute->created_at ? $attribute->created_at->format('M d, Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Edit Link -->
                                    <a href="{{ route('attributes.edit', $attribute->id) }}" 
                                       class="btn-bs-info hover:opacity-85 text-xs py-1 px-3 flex items-center gap-1 rounded text-white bg-yellow-500 shadow-sm transition-opacity">
                                        <i class="fad fa-edit text-xs"></i> Edit
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('attributes.destroy', $attribute->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete attribute \&quot;{{ $attribute->name }}\&quot;? This will also delete all its values.')" 
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-bs-danger hover:opacity-85 text-xs py-1 px-3 flex items-center gap-1 rounded text-white bg-red-600 shadow-sm transition-opacity">
                                            <i class="fad fa-trash-alt text-xs"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fad fa-tags text-4xl text-gray-300 mb-3"></i>
                                    <p class="font-semibold text-base text-gray-700">No Attributes Found</p>
                                    <p class="text-xs text-gray-400 mt-1">Try another search or add a new attribute with values to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card Footer (Pagination) -->
        @if($attributes->hasPages())
            <div class="card-footer bg-gray-50 border-t border-gray-200 px-6 py-4 rounded-b-lg">
                {{ $attributes->links() }}
            </div>
        @endif
    </div>
@endsection
