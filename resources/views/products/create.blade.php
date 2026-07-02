@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto my-8">
        <!-- Back Link -->
        <a href="{{ route('products.index') }}" class="mb-6 inline-flex items-center text-sm text-gray-500 hover:text-teal-600 transition-colors">
            <i class="fad fa-arrow-left mr-2"></i> Back to Products
        </a>

        <!-- Card Container -->
        <div class="card shadow-md border border-gray-250 bg-white rounded-lg">
            <div class="card-header p-6 border-b border-gray-200">
                <h1 class="h6 font-extrabold text-gray-800 m-0">Create New Product</h1>
                <p class="text-xs text-gray-500 mt-1.5">Specify product details, category, and configure variants</p>
            </div>
            
            <div class="card-body p-6">
                <form action="{{ route('products.store') }}" method="POST" id="createProductForm">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-xs font-bold text-gray-600 uppercase mb-2">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('name') border-red-500 @enderror" 
                               placeholder="e.g. Cotton T-Shirt Premium" required autofocus>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Category Field -->
                    <div class="mb-6">
                        <label for="category_id" class="block text-xs font-bold text-gray-600 uppercase mb-2">Category</label>
                        <select name="category_id" id="category_id" required
                                class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('category_id') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div class="mb-6">
                        <label for="description" class="block text-xs font-bold text-gray-600 uppercase mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('description') border-red-500 @enderror" 
                                  placeholder="Describe the product details, benefits, material, etc...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Variant Toggle Checkbox -->
                    <div class="mb-8 flex items-center gap-3 bg-teal-50 p-4 border border-teal-100 rounded-lg shadow-sm">
                        <input type="checkbox" name="has_variant" id="has_variant" value="1" {{ old('has_variant') ? 'checked' : '' }}
                               class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500 cursor-pointer">
                        <div>
                            <label for="has_variant" class="block text-sm font-semibold text-teal-900 cursor-pointer">
                                Produk ini memiliki varian (ukuran/warna)
                            </label>
                            <p class="text-xs text-teal-600 mt-0.5">Aktifkan jika produk ini memiliki opsi variasi seperti ukuran, warna, atau spesifikasi lainnya.</p>
                        </div>
                    </div>

                    <!-- Single Product Inputs (Show/Hide dynamically) -->
                    <div id="singleProductSection" class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="single_sku" class="block text-xs font-bold text-gray-600 uppercase mb-2">SKU</label>
                            <input type="text" name="single_sku" id="single_sku" value="{{ old('single_sku') }}"
                                   class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('single_sku') border-red-500 @enderror" 
                                   placeholder="e.g. SKU-COTTON-001">
                            @error('single_sku')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="single_price" class="block text-xs font-bold text-gray-600 uppercase mb-2">Harga Jual (Rp)</label>
                            <input type="number" name="single_price" id="single_price" step="0.01" value="{{ old('single_price') }}"
                                   class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('single_price') border-red-500 @enderror" 
                                   placeholder="e.g. 150000">
                            @error('single_price')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Dynamic Variant Section (Show/Hide dynamically) -->
                    <div id="variantSection" class="mb-8 border-t border-gray-200 pt-8 mt-8" style="display: none;">
                        <h3 class="text-sm font-bold text-gray-800 uppercase mb-5 flex items-center gap-2 text-teal-600">
                            <i class="fad fa-sliders-h"></i> Pengaturan Varian Produk
                        </h3>
                        
                        <!-- List of Attributes with Value Checkboxes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @foreach($attributes as $attribute)
                                @if($attribute->values->count() > 0)
                                    <div class="bg-gray-50 border border-gray-200 p-5 rounded-lg">
                                        <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center justify-between border-b border-gray-200 pb-2">
                                            <span>{{ $attribute->name }}</span>
                                            <span class="text-[10px] bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full font-bold">Atribut</span>
                                        </h4>
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($attribute->values as $value)
                                                <label class="flex items-center gap-2 bg-white px-3 py-1.5 border border-gray-200 rounded-md cursor-pointer hover:border-teal-400 hover:bg-teal-50/20 transition-all select-none">
                                                    <input type="checkbox" 
                                                           class="attribute-value-checkbox w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500" 
                                                           data-attribute-id="{{ $attribute->id }}"
                                                           data-attribute-name="{{ $attribute->name }}"
                                                           data-value-id="{{ $value->id }}"
                                                           data-value-text="{{ $value->value }}">
                                                    <span class="text-xs text-gray-700 font-semibold">{{ $value->value }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Generated Combinations Table -->
                        <div id="combinationsContainer" style="display: none;">
                            <h4 class="text-xs font-bold text-gray-600 uppercase mb-4 flex items-center gap-2">
                                <i class="fad fa-table text-teal-500"></i> Kombinasi Varian yang Dihasilkan
                            </h4>
                            <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm mb-6">
                                <table class="w-full text-left text-sm" id="combinationsTable">
                                    <thead class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-6 py-3 w-1/3">Detail Varian</th>
                                            <th class="px-6 py-3 w-1/3">SKU Varian</th>
                                            <th class="px-6 py-3 w-1/3">Harga Jual (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-150 text-gray-700">
                                        <!-- Dynamically generated rows -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Placeholder Message -->
                        <div id="noCombinationsMessage" class="text-center py-8 bg-gray-50 border border-dashed border-gray-200 rounded-lg text-gray-500 text-xs flex flex-col items-center justify-center gap-2 mb-6">
                            <i class="fad fa-tags text-xl text-gray-300"></i>
                            <span>Silakan pilih opsi nilai atribut di atas untuk mulai membuat kombinasi varian otomatis.</span>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-4 border-t border-gray-200 pt-6 mt-8">
                        <button type="submit" class="btn-bs-primary py-2 px-6 shadow hover:opacity-90 transition-opacity bg-teal-600 text-white rounded font-semibold text-sm">
                            <i class="fad fa-save mr-2"></i> Save Product
                        </button>
                        <a href="{{ route('products.index') }}" class="btn-gray py-2 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded font-semibold text-sm transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS script for variant handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hasVariantCheckbox = document.getElementById('has_variant');
            const singleProductSection = document.getElementById('singleProductSection');
            const variantSection = document.getElementById('variantSection');
            const attributeCheckboxes = document.querySelectorAll('.attribute-value-checkbox');
            const combinationsContainer = document.getElementById('combinationsContainer');
            const noCombinationsMessage = document.getElementById('noCombinationsMessage');
            const combinationsTableBody = document.querySelector('#combinationsTable tbody');
            const singlePriceInput = document.getElementById('single_price');
            const singleSkuInput = document.getElementById('single_sku');

            // Toggle variant/single view
            function toggleVariantMode() {
                if (hasVariantCheckbox.checked) {
                    singleProductSection.style.display = 'none';
                    variantSection.style.display = 'block';
                    // Make single inputs not required
                    singlePriceInput.required = false;
                    
                    // Generate combinations on view toggle
                    generateVariantCombinations();
                } else {
                    singleProductSection.style.display = 'grid';
                    variantSection.style.display = 'none';
                    // Make single price required
                    singlePriceInput.required = true;
                }
            }

            // Cartesian Product algorithm helper
            function cartesianProduct(arrays) {
                if (arrays.length === 0) return [];
                return arrays.reduce((acc, curr) => {
                    let res = [];
                    acc.forEach(a => {
                        curr.forEach(b => {
                            res.push(a.concat([b]));
                        });
                    });
                    return res;
                }, [[]]);
            }

            // Generate combinations based on checked attributes
            function generateVariantCombinations() {
                // Group selected values by attribute ID
                const groupedValues = {};
                attributeCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        const attrId = cb.getAttribute('data-attribute-id');
                        const attrName = cb.getAttribute('data-attribute-name');
                        const valId = cb.getAttribute('data-value-id');
                        const valText = cb.getAttribute('data-value-text');

                        if (!groupedValues[attrId]) {
                            groupedValues[attrId] = {
                                id: attrId,
                                name: attrName,
                                values: []
                            };
                        }
                        groupedValues[attrId].values.push({
                            id: valId,
                            text: valText
                        });
                    }
                });

                const groupArrays = Object.values(groupedValues).map(g => g.values);
                const combinations = cartesianProduct(groupArrays);

                // Render table rows
                combinationsTableBody.innerHTML = '';
                
                if (combinations.length > 0 && groupArrays.length > 0) {
                    combinationsContainer.style.display = 'block';
                    noCombinationsMessage.style.display = 'none';

                    combinations.forEach((combination, index) => {
                        // Generate label (e.g. Red - S)
                        const label = combination.map(item => item.text).join(' - ');
                        
                        // Try to inherit base product name or a generated SKU prefix
                        const prodName = document.getElementById('name').value.trim();
                        const skuPrefix = prodName ? prodName.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 5) : 'VAR';
                        const generatedSku = `${skuPrefix}-${label.toUpperCase().replace(/\s+/g, '')}-${index + 1}`;

                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-gray-50 transition-colors border-b border-gray-100';
                        tr.innerHTML = `
                            <td class="px-6 py-4 font-semibold text-gray-800 text-xs">
                                <span class="bg-gray-100 text-gray-700 px-2.5 py-1 rounded border border-gray-250 font-medium">
                                    ${label}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <input type="text" name="variants[${index}][sku]" value="${generatedSku}" 
                                       placeholder="SKU" 
                                       class="w-full p-2 border border-gray-200 rounded text-sm bg-gray-50 focus:outline-none focus:border-teal-500 font-mono">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="variants[${index}][price]" required step="0.01" 
                                       placeholder="Harga Jual" 
                                       class="w-full p-2 border border-gray-200 rounded text-sm bg-gray-50 focus:outline-none focus:border-teal-500">
                                
                                <!-- Hidden fields representing value IDs -->
                                ${combination.map(item => `<input type="hidden" name="variants[${index}][attribute_values][]" value="${item.id}">`).join('')}
                            </td>
                        `;
                        combinationsTableBody.appendChild(tr);
                    });
                } else {
                    combinationsContainer.style.display = 'none';
                    noCombinationsMessage.style.display = 'flex';
                }
            }

            // Listeners
            hasVariantCheckbox.addEventListener('change', toggleVariantMode);
            
            attributeCheckboxes.forEach(cb => {
                cb.addEventListener('change', generateVariantCombinations);
            });

            // Listen for keyup on product name to dynamically suggest better SKU prefixes for generated variant tables
            document.getElementById('name').addEventListener('input', function() {
                if (hasVariantCheckbox.checked) {
                    // Update SKU inputs if they haven't been manually touched by user yet
                    const inputs = combinationsTableBody.querySelectorAll('input[type="text"]');
                    const prodName = this.value.trim();
                    const skuPrefix = prodName ? prodName.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 5) : 'VAR';
                    
                    inputs.forEach((input, index) => {
                        const currentVal = input.value;
                        const labelPart = currentVal.substring(currentVal.indexOf('-') + 1);
                        input.value = `${skuPrefix}-${labelPart}`;
                    });
                }
            });

            // Initial view toggle
            toggleVariantMode();
        });
    </script>
@endsection
