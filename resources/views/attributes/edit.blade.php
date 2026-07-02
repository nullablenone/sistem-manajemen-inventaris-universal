@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto my-8">
        <!-- Back Link -->
        <a href="{{ route('attributes.index') }}" class="mb-6 inline-flex items-center text-sm text-gray-500 hover:text-teal-600 transition-colors">
            <i class="fad fa-arrow-left mr-2"></i> Back to Attributes
        </a>

        <!-- Card Container -->
        <div class="card shadow-md border border-gray-250 bg-white rounded-lg">
            <div class="card-header p-6 border-b border-gray-200">
                <h1 class="h6 font-extrabold text-gray-800 m-0">Edit Attribute</h1>
                <p class="text-xs text-gray-500 mt-1.5">Modify details and options for attribute: <span class="font-semibold text-teal-600">{{ $attribute->name }}</span></p>
            </div>
            
            <div class="card-body p-6">
                <form action="{{ route('attributes.update', $attribute->id) }}" method="POST" id="editAttributeForm">
                    @csrf
                    @method('PUT')

                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-xs font-bold text-gray-600 uppercase mb-2">Attribute Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $attribute->name) }}" 
                               class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('name') border-red-500 @enderror" 
                               placeholder="e.g. Size, Color, Material" required autofocus>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Attribute Values (Tags Input) -->
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Attribute Values</label>
                        
                        <div id="tagsInputContainer" class="w-full min-h-[50px] p-2.5 border border-gray-200 rounded bg-gray-50 flex flex-wrap gap-2 items-center focus-within:border-teal-500 focus-within:bg-white transition-all cursor-text">
                            <!-- Tags will be dynamically placed here -->
                            
                            <!-- Inside input -->
                            <input type="text" id="tagValueInput" placeholder="Press Enter or comma to add values..." 
                                   class="flex-1 min-w-[150px] p-1.5 text-sm bg-transparent outline-none border-none">
                        </div>

                        <!-- Real values sent to server (hidden inputs) -->
                        <div id="hiddenValuesContainer">
                            @if(old('values'))
                                @foreach(old('values') as $oldVal)
                                    <input type="hidden" name="values[]" value="{{ $oldVal }}">
                                @endforeach
                            @else
                                @foreach($attribute->values as $val)
                                    <input type="hidden" name="values[]" value="{{ $val->value }}">
                                @endforeach
                            @endif
                        </div>

                        <p class="text-xs text-gray-400 mt-1.5">Type a value (e.g. "XL", "Merah") and press <span class="font-semibold">Enter</span> or <span class="font-semibold">Comma (,)</span>. Minimal 1 value is required.</p>
                        
                        @error('values')
                            <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                        @enderror
                        @error('values.*')
                            <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6 mt-8">
                        <button type="submit" class="btn-bs-primary py-2 px-6 shadow hover:opacity-90 transition-opacity bg-teal-600 text-white rounded font-semibold text-sm">
                            <i class="fad fa-save mr-2"></i> Update Attribute
                        </button>
                        <a href="{{ route('attributes.index') }}" class="btn-gray py-2 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded font-semibold text-sm transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS for Dynamic Tags -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('tagsInputContainer');
            const input = document.getElementById('tagValueInput');
            const hiddenContainer = document.getElementById('hiddenValuesContainer');
            const form = document.getElementById('editAttributeForm');
            
            // Local array tracking values
            let values = [];

            // Load values from hidden inputs
            const preloadedInputs = hiddenContainer.querySelectorAll('input[type="hidden"]');
            preloadedInputs.forEach(inp => {
                if (inp.value.trim() !== '') {
                    values.push(inp.value.trim());
                }
            });

            // Re-render UI with loaded values
            renderTags();

            // Focus input when clicking anywhere on the container wrapper
            container.addEventListener('click', function(e) {
                if (e.target === container || e.target.classList.contains('flex-wrap')) {
                    input.focus();
                }
            });

            // Key events for input
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // prevent form submission
                    addTag();
                }
            });

            input.addEventListener('keyup', function(e) {
                if (e.key === ',') {
                    // Remove trailing comma from input value and add
                    input.value = input.value.replace(/,/g, '');
                    addTag();
                }
            });

            // Blur event to capture left-over input value as a tag
            input.addEventListener('blur', function() {
                addTag();
            });

            function addTag() {
                const rawVal = input.value.trim();
                if (rawVal === '') return;

                // Split by comma if any were pasted
                const parts = rawVal.split(',').map(s => s.trim()).filter(s => s !== '');
                
                parts.forEach(val => {
                    // Check if value already exists (case-insensitive checking to prevent duplicates)
                    const isDuplicate = values.some(item => item.toLowerCase() === val.toLowerCase());
                    if (!isDuplicate) {
                        values.push(val);
                    }
                });

                input.value = '';
                renderTags();
            }

            function removeTag(index) {
                values.splice(index, 1);
                renderTags();
            }

            function renderTags() {
                // Clear existing tag elements in the wrapper
                const tagSpans = container.querySelectorAll('.tag-badge');
                tagSpans.forEach(span => span.remove());

                // Clear hidden values container
                hiddenContainer.innerHTML = '';

                // Insert tags before the text input element
                values.forEach((val, idx) => {
                    // Create tag badge
                    const span = document.createElement('span');
                    span.className = 'tag-badge bg-teal-100 text-teal-800 border border-teal-250 px-2.5 py-1 rounded text-xs font-semibold flex items-center gap-1.5 shadow-sm transition-all hover:bg-teal-200';
                    span.innerHTML = `
                        <span>${val}</span>
                        <button type="button" class="remove-btn text-teal-600 hover:text-teal-900 focus:outline-none font-bold text-sm leading-none">&times;</button>
                    `;

                    // Remove button event
                    span.querySelector('.remove-btn').addEventListener('click', function(e) {
                        e.stopPropagation();
                        removeTag(idx);
                    });

                    // Prepend tag to input
                    container.insertBefore(span, input);

                    // Add hidden input field to form
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'values[]';
                    hiddenInput.value = val;
                    hiddenContainer.appendChild(hiddenInput);
                });

                // Update input placeholder based on values present
                if (values.length > 0) {
                    input.placeholder = '';
                } else {
                    input.placeholder = 'Press Enter or comma to add values...';
                }
            }

            // Client side validation on submit just in case
            form.addEventListener('submit', function(e) {
                // If there's some remaining text in input, add it as a tag first
                addTag();
                
                if (values.length === 0) {
                    e.preventDefault();
                    alert('Harap masukkan minimal satu nilai atribut.');
                    input.focus();
                }
            });
        });
    </script>
@endsection
