@extends('layouts.app')

@section('content')
    <!-- Card Container -->
    <div class="card my-8 shadow-md border border-gray-250 bg-white rounded-lg">
        <!-- Card Header -->
        <div class="card-header p-6 border-b border-gray-200">
            <h1 class="h5 font-extrabold text-gray-800 m-0">Kartu Stok (Stock Ledger / Riwayat Mutasi)</h1>
            <p class="text-xs text-gray-500 mt-1.5">Laporan audit menyeluruh untuk memantau setiap pergerakan stok masuk dan keluar secara kronologis.</p>
        </div>

        <!-- Filter Form -->
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <form action="{{ route('stock.ledger.index') }}" method="GET" class="grid grid-cols-4 gap-4 xl:grid-cols-2 lg:grid-cols-1">
                <!-- Product/Variant Select -->
                <div>
                    <label for="product_variant_id" class="block text-[10px] font-bold text-gray-600 uppercase mb-2">Varian Produk</label>
                    <select name="product_variant_id" id="product_variant_id"
                            class="w-full p-2 border border-gray-200 rounded text-xs bg-white focus:outline-none focus:border-teal-500">
                        <option value="">-- Semua Produk & Varian --</option>
                        @foreach($variants as $v)
                            <option value="{{ $v->id }}" {{ $variantId == $v->id ? 'selected' : '' }}>
                                {{ $v->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mutation Type Select -->
                <div>
                    <label for="type" class="block text-[10px] font-bold text-gray-600 uppercase mb-2">Tipe Mutasi</label>
                    <select name="type" id="type"
                            class="w-full p-2 border border-gray-200 rounded text-xs bg-white focus:outline-none focus:border-teal-500">
                        <option value="">-- Semua Tipe --</option>
                        <option value="inbound" {{ $type === 'inbound' ? 'selected' : '' }}>Barang Masuk (Inbound)</option>
                        <option value="outbound" {{ $type === 'outbound' ? 'selected' : '' }}>Barang Keluar (Outbound)</option>
                    </select>
                </div>

                <!-- Date Range Filters -->
                <div>
                    <label for="start_date" class="block text-[10px] font-bold text-gray-600 uppercase mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                           class="w-full p-2 border border-gray-200 rounded text-xs bg-white focus:outline-none focus:border-teal-500">
                </div>

                <div>
                    <label for="end_date" class="block text-[10px] font-bold text-gray-600 uppercase mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                           class="w-full p-2 border border-gray-200 rounded text-xs bg-white focus:outline-none focus:border-teal-500">
                </div>

                <!-- Submit and Reset Buttons -->
                <div class="col-span-4 xl:col-span-2 lg:col-span-1 flex items-center justify-end gap-3 mt-2">
                    <button type="submit" class="py-2 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded text-xs font-semibold shadow transition-colors">
                        <i class="fad fa-filter mr-1.5"></i> Terapkan Filter
                    </button>
                    @if($variantId || $type || $startDate || $endDate)
                        <a href="{{ route('stock.ledger.index') }}" class="py-2 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded text-xs font-semibold transition-colors">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Card Body Table -->
        <div class="card-body p-0 overflow-x-auto">
            <table class="table-auto w-full text-left text-sm text-gray-600">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Tanggal / Waktu</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Varian Produk (SKU)</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold text-center">Tipe</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold text-center">Stok Awal</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold text-center">Mutasi (Qty)</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold text-center">Stok Akhir</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Dokumen / Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mutations as $m)
                        @php $v = $m->productVariant; @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs text-gray-500 font-medium">
                                {{ $m->date->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $v->product->name }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                    @if($v->product->has_variant && $v->attributeValues->isNotEmpty())
                                        <span class="bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200 font-medium">
                                            {{ $v->attributeValues->pluck('value')->implode(' / ') }}
                                        </span>
                                    @endif
                                    @if($v->sku)
                                        <span class="font-mono text-gray-400">SKU: {{ $v->sku }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($m->type === 'inbound')
                                    <span class="bg-green-50 text-green-700 border border-green-200 px-2 py-0.5 rounded text-[10px] font-bold uppercase font-sans">
                                        Masuk
                                    </span>
                                @else
                                    <span class="bg-red-50 text-red-700 border border-red-200 px-2 py-0.5 rounded text-[10px] font-bold uppercase font-sans">
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-mono font-medium text-gray-500">
                                {{ $m->stock_before }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono font-bold">
                                @if($m->type === 'inbound')
                                    <span class="text-green-600">+{{ $m->quantity }}</span>
                                @else
                                    <span class="text-red-600">-{{ $m->quantity }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-mono font-bold text-gray-900">
                                {{ $m->stock_after }}
                            </td>
                            <td class="px-6 py-4 text-xs font-sans">
                                @if($m->reference)
                                    <span class="font-mono font-semibold text-gray-800">{{ $m->reference->transaction_number }}</span>
                                    <span class="text-gray-400"> - </span>
                                @endif
                                <span class="italic text-gray-500">{{ $m->note }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fad fa-clipboard-list text-4xl text-gray-300"></i>
                                    <p class="font-semibold text-base text-gray-700">Tidak Ada Data Mutasi Stok</p>
                                    <p class="text-xs text-gray-400">Sesuaikan kriteria filter atau buat transaksi baru untuk melihat log mutasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card Footer -->
        @if($mutations->hasPages())
            <div class="card-footer bg-gray-50 border-t border-gray-200 px-6 py-4 rounded-b-lg">
                {{ $mutations->links() }}
            </div>
        @endif
    </div>
@endsection
