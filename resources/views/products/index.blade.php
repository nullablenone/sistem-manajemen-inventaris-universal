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
                <h1 class="h5 font-extrabold text-gray-800 m-0">Products</h1>
                <p class="text-xs text-gray-500 mt-1">Manage your catalog products and stock variants</p>
            </div>
            
            <div class="flex flex-row items-center gap-4 lg:w-full lg:justify-between">
                <!-- Search Form -->
                <form action="{{ route('products.index') }}" method="GET" class="flex items-center">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" 
                               placeholder="Search products..." 
                               class="p-2 pl-8 border border-gray-200 rounded text-sm bg-gray-50 focus:outline-none focus:border-teal-500 w-64 lg:w-full transition-colors">
                        <i class="fad fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                    @if($search)
                        <a href="{{ route('products.index') }}" class="ml-2 text-xs text-gray-500 hover:text-red-500 transition-colors">Clear</a>
                    @endif
                </form>

                <!-- Add Button -->
                <a href="{{ route('products.create') }}" class="btn-bs-primary flex items-center gap-2 py-2 px-4 shadow hover:opacity-90 transition-opacity bg-teal-600 text-white rounded font-medium text-sm">
                    <i class="fad fa-plus-circle text-xs"></i>
                    <span>Add Product</span>
                </a>
            </div>
        </div>

        <!-- Card Body Table -->
        <div class="card-body p-0 overflow-x-auto">
            <table class="table-auto w-full text-left text-sm text-gray-600">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold"># ID</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold w-1/4">Product Name</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Category</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Mode</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Variants / Price</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $product->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5 truncate max-w-[250px]">{{ $product->description ?? 'No description' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-2 py-0.5 rounded text-xs font-semibold">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->has_variant)
                                    <span class="bg-teal-50 text-teal-700 border border-teal-100 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                        Variants
                                    </span>
                                @else
                                    <span class="bg-gray-50 text-gray-600 border border-gray-250 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                        Single
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($product->has_variant)
                                    <span class="text-teal-700 font-bold">
                                        {{ $product->variants->count() }} Varian Terbuat
                                    </span>
                                @else
                                    @php $variant = $product->variants->first(); @endphp
                                    <span class="text-gray-900 font-semibold font-mono">
                                        Rp {{ number_format($variant->price ?? 0, 2, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="btn-bs-info hover:opacity-85 text-xs py-1 px-3 flex items-center gap-1 rounded text-white bg-yellow-500 shadow-sm transition-opacity">
                                        <i class="fad fa-edit text-xs"></i> Edit
                                    </a>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete product \&quot;{{ $product->name }}\&quot;?')" 
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fad fa-box text-4xl text-gray-300"></i>
                                    <p class="font-semibold text-base text-gray-700">No Products Found</p>
                                    <p class="text-xs text-gray-400">Add a new product or adjust search criteria to see catalog entries.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card Footer -->
        @if($products->hasPages())
            <div class="card-footer bg-gray-50 border-t border-gray-200 px-6 py-4 rounded-b-lg">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
