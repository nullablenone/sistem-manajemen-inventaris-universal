@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto my-8">
        <!-- Back Link -->
        <a href="{{ route('stock.inbound.index') }}" class="mb-6 inline-flex items-center text-sm text-gray-500 hover:text-teal-600 transition-colors">
            <i class="fad fa-arrow-left mr-2"></i> Kembali ke Daftar Barang Masuk
        </a>

        <!-- Alert Error -->
        @if(session('error'))
            <div class="alert alert-danger mb-6 flex justify-between items-center bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded shadow-sm">
                <span>
                    <i class="fad fa-times-circle mr-2 text-red-500"></i> {{ session('error') }}
                </span>
                <button onclick="this.parentElement.remove()" class="text-red-800 font-bold focus:outline-none hover:text-red-950">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded shadow-sm">
                <p class="font-bold mb-1">Terjadi kesalahan validasi:</p>
                <ul class="list-disc pl-5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Container -->
        <div class="card shadow-md border border-gray-250 bg-white rounded-lg">
            <div class="card-header p-6 border-b border-gray-200">
                <h1 class="h6 font-extrabold text-gray-800 m-0">Catat Barang Masuk Baru (Inbound)</h1>
                <p class="text-xs text-gray-500 mt-1.5">Masukkan tanggal, catatan supplier, dan daftar varian barang yang dibeli.</p>
            </div>
            
            <div class="card-body p-6">
                <form action="{{ route('stock.inbound.store') }}" method="POST" id="inboundForm">
                    @csrf

                    <div class="grid grid-cols-2 gap-6 mb-6 md:grid-cols-1">
                        <!-- Date Field -->
                        <div>
                            <label for="date" class="block text-xs font-bold text-gray-600 uppercase mb-2">Tanggal Transaksi</label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                                   class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('date') border-red-500 @enderror" 
                                   required>
                            @error('date')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Note Field -->
                        <div>
                            <label for="note" class="block text-xs font-bold text-gray-600 uppercase mb-2">Catatan / Supplier</label>
                            <input type="text" name="note" id="note" value="{{ old('note') }}" 
                                   class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none focus:border-teal-500 bg-gray-50 @error('note') border-red-500 @enderror" 
                                   placeholder="Contoh: Pembelian dari Supplier PT ABC atau Catatan Toko">
                            @error('note')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-3">Daftar Barang Masuk</label>
                        
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm mb-4">
                            <table class="w-full text-left text-sm" id="itemsTable">
                                <thead class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-3 w-3/4">Nama Varian Produk (SKU)</th>
                                        <th class="px-6 py-3 w-1/4">Jumlah (Pcs)</th>
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-gray-700">
                                    <!-- Dynamic Rows will be inserted here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Row Button -->
                        <button type="button" id="addRowBtn" class="btn-bs-secondary inline-flex items-center gap-2 py-2 px-4 bg-gray-600 text-white rounded font-medium text-xs hover:bg-gray-700 transition-colors">
                            <i class="fad fa-plus"></i> Tambah Baris Barang
                        </button>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-4 border-t border-gray-200 pt-6 mt-8">
                        <button type="submit" class="btn-bs-primary py-2 px-6 shadow hover:opacity-90 transition-opacity bg-teal-600 text-white rounded font-semibold text-sm">
                            <i class="fad fa-save mr-2"></i> Simpan Transaksi Masuk
                        </button>
                        <a href="{{ route('stock.inbound.index') }}" class="btn-gray py-2 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded font-semibold text-sm transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS code for dynamic rows creation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#itemsTable tbody');
            const addRowBtn = document.getElementById('addRowBtn');
            let rowIndex = 0;

            // Product options data from php
            const variantOptions = [
                @foreach($variants as $variant)
                    {
                        id: "{{ $variant->id }}",
                        name: @json($variant->display_name)
                    },
                @endforeach
            ];

            function addRow() {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50/50 transition-colors border-b border-gray-100';
                tr.dataset.index = rowIndex;

                // Build select options
                let optionsHtml = '<option value="">-- Pilih Varian Produk --</option>';
                variantOptions.forEach(opt => {
                    optionsHtml += `<option value="${opt.id}">${opt.name}</option>`;
                });

                tr.innerHTML = `
                    <td class="px-6 py-3">
                        <select name="items[${rowIndex}][product_variant_id]" required
                                class="w-full p-2 border border-gray-200 rounded text-sm bg-gray-50 focus:outline-none focus:border-teal-500">
                            ${optionsHtml}
                        </select>
                    </td>
                    <td class="px-6 py-3">
                        <input type="number" name="items[${rowIndex}][quantity]" min="1" required value="1"
                               class="w-full p-2 border border-gray-200 rounded text-sm bg-gray-50 focus:outline-none focus:border-teal-500 font-semibold text-center">
                    </td>
                    <td class="px-6 py-3 text-center">
                        <button type="button" class="removeRowBtn text-red-500 hover:text-red-700 transition-colors focus:outline-none text-base">
                            <i class="fad fa-minus-circle"></i>
                        </button>
                    </td>
                `;

                tableBody.appendChild(tr);

                // Add delete listener to the button
                tr.querySelector('.removeRowBtn').addEventListener('click', function() {
                    tr.remove();
                    toggleEmptyState();
                });

                rowIndex++;
                toggleEmptyState();
            }

            function toggleEmptyState() {
                if (tableBody.children.length === 0) {
                    const emptyTr = document.createElement('tr');
                    emptyTr.id = 'emptyStateRow';
                    emptyTr.innerHTML = `
                        <td colspan="3" class="px-6 py-8 text-center text-gray-400 text-xs">
                            Belum ada barang ditambahkan. Klik tombol "Tambah Baris Barang" di atas.
                        </td>
                    `;
                    tableBody.appendChild(emptyTr);
                } else {
                    const emptyRow = document.getElementById('emptyStateRow');
                    if (emptyRow) {
                        emptyRow.remove();
                    }
                }
            }

            // Event Listeners
            addRowBtn.addEventListener('click', addRow);

            // Add first row on init
            addRow();
        });
    </script>
@endsection
