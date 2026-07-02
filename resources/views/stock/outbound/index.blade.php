@extends('layouts.app')

@section('content')
    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="alert alert-success mb-6 flex justify-between items-center bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded shadow-sm">
            <span>
                <i class="fad fa-check-circle mr-2 text-green-500"></i> {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-green-800 font-bold focus:outline-none hover:text-green-950">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-6 flex justify-between items-center bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded shadow-sm">
            <span>
                <i class="fad fa-times-circle mr-2 text-red-500"></i> {{ session('error') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-red-800 font-bold focus:outline-none hover:text-red-950">&times;</button>
        </div>
    @endif

    <!-- Card Container -->
    <div class="card my-8 shadow-md border border-gray-250 bg-white rounded-lg">
        <!-- Card Header -->
        <div class="card-header flex flex-row justify-between items-center lg:flex-col lg:items-start gap-6 p-6 border-b border-gray-200">
            <div>
                <h1 class="h5 font-extrabold text-gray-800 m-0">Barang Keluar (Outbound / Sales)</h1>
                <p class="text-xs text-gray-500 mt-1.5">Mencatat pergerakan barang keluar dari gudang (penjualan/rusak/retur).</p>
            </div>
            
            <div class="flex flex-row items-center gap-6 lg:w-full lg:justify-between">
                <!-- Search Form -->
                <form action="{{ route('stock.outbound.index') }}" method="GET" class="flex items-center">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" 
                                placeholder="Cari nomor transaksi / catatan..." 
                                class="p-2 pl-8 border border-gray-200 rounded text-sm bg-gray-50 focus:outline-none focus:border-red-500 w-72 lg:w-full transition-colors">
                        <i class="fad fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                    </div>
                    @if($search)
                        <a href="{{ route('stock.outbound.index') }}" class="ml-2 text-xs text-gray-500 hover:text-red-500 transition-colors">Clear</a>
                    @endif
                </form>

                <!-- Add Button -->
                <a href="{{ route('stock.outbound.create') }}" class="btn-bs-primary flex items-center gap-2 py-2 px-4 shadow hover:opacity-90 transition-opacity bg-red-600 text-white rounded font-medium text-sm">
                    <i class="fad fa-plus-circle text-xs"></i>
                    <span>Catat Barang Keluar</span>
                </a>
            </div>
        </div>

        <!-- Card Body Table -->
        <div class="card-body p-0 overflow-x-auto">
            <table class="table-auto w-full text-left text-sm text-gray-600">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold"># ID</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">No Transaksi</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold w-1/3">Detail Item</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Catatan</th>
                        <th class="px-6 py-4 border-b border-gray-200 font-semibold">Operator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $tx->id }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-red-700 text-xs">{{ $tx->transaction_number }}</td>
                            <td class="px-6 py-4">{{ $tx->date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <ul class="list-disc pl-4 space-y-1 text-xs">
                                    @foreach($tx->items as $item)
                                        @php $v = $item->productVariant; @endphp
                                        <li>
                                            <span class="font-semibold text-gray-800">{{ $v->product->name }}</span>
                                            @if($v->product->has_variant && $v->attributeValues->isNotEmpty())
                                                <span class="text-gray-500">({{ $v->attributeValues->pluck('value')->implode(' / ') }})</span>
                                            @endif
                                            @if($v->sku)
                                                <span class="text-gray-400 font-mono text-[10px]">[{{ $v->sku }}]</span>
                                            @endif
                                            : <span class="bg-red-100 text-red-800 px-1.5 py-0.5 rounded font-bold ml-1">-{{ $item->quantity }} pcs</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-xs italic">{{ $tx->note ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs font-semibold">{{ $tx->createdBy->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fad fa-arrow-alt-up text-4xl text-gray-300"></i>
                                    <p class="font-semibold text-base text-gray-700">Belum Ada Transaksi Keluar</p>
                                    <p class="text-xs text-gray-400">Silakan catat barang keluar baru saat terjadi penjualan atau pengeluaran stok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card Footer -->
        @if($transactions->hasPages())
            <div class="card-footer bg-gray-50 border-t border-gray-200 px-6 py-4 rounded-b-lg">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection
