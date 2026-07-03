<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\StockTransaction;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockTransactionController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function inboundIndex(Request $request)
    {
        $search = $request->input('search');

        $transactions = StockTransaction::query()
            ->with(['createdBy', 'items.productVariant.product', 'items.productVariant.attributeValues'])
            ->where('type', 'inbound')
            ->when($search, function ($query, $search) {
                return $query->where('transaction_number', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock.inbound.index', compact('transactions', 'search'));
    }

    public function inboundCreate()
    {
        $variants = ProductVariant::with(['product', 'attributeValues'])->get();
        return view('stock.inbound.create', compact('variants'));
    }

    public function inboundStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'date.required' => 'Tanggal transaksi wajib diisi.',
            'items.required' => 'Item transaksi wajib diisi setidaknya 1 baris.',
            'items.*.product_variant_id.required' => 'Produk/Varian wajib dipilih.',
            'items.*.product_variant_id.exists' => 'Produk/Varian tidak valid.',
            'items.*.quantity.required' => 'Jumlah wajib diisi.',
            'items.*.quantity.integer' => 'Jumlah harus berupa angka.',
            'items.*.quantity.min' => 'Jumlah minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $transactionNumber = $this->generateTransactionNumber('inbound', $request->input('date'));
                
                $transaction = StockTransaction::create([
                    'transaction_number' => $transactionNumber,
                    'type' => 'inbound',
                    'date' => $request->input('date'),
                    'note' => $request->input('note'),
                    'created_by' => Auth::id() ?? 1,
                ]);

                foreach ($request->input('items') as $item) {
                    $variant = ProductVariant::findOrFail($item['product_variant_id']);
                    
                    // Create transaction detail
                    $transaction->items()->create([
                        'product_variant_id' => $variant->id,
                        'quantity' => $item['quantity'],
                    ]);

                    // Adjust Stock & Log Mutation
                    $this->stockService->adjustStock(
                        $variant,
                        $item['quantity'],
                        'inbound',
                        $request->input('note') ?? 'Barang Masuk dari Supplier',
                        $transaction,
                        $request->input('date')
                    );
                }
            });

            return redirect()
                ->route('stock.inbound.index')
                ->with('success', 'Transaksi barang masuk berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function outboundIndex(Request $request)
    {
        $search = $request->input('search');

        $transactions = StockTransaction::query()
            ->with(['createdBy', 'items.productVariant.product', 'items.productVariant.attributeValues'])
            ->where('type', 'outbound')
            ->when($search, function ($query, $search) {
                return $query->where('transaction_number', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock.outbound.index', compact('transactions', 'search'));
    }

    public function outboundCreate()
    {
        $variants = ProductVariant::with(['product', 'attributeValues'])->get();
        return view('stock.outbound.create', compact('variants'));
    }

    public function outboundStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'date.required' => 'Tanggal transaksi wajib diisi.',
            'items.required' => 'Item transaksi wajib diisi setidaknya 1 baris.',
            'items.*.product_variant_id.required' => 'Produk/Varian wajib dipilih.',
            'items.*.product_variant_id.exists' => 'Produk/Varian tidak valid.',
            'items.*.quantity.required' => 'Jumlah wajib diisi.',
            'items.*.quantity.integer' => 'Jumlah harus berupa angka.',
            'items.*.quantity.min' => 'Jumlah minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $transactionNumber = $this->generateTransactionNumber('outbound', $request->input('date'));
                
                // Pre-validate stock capacity before performing mutations
                foreach ($request->input('items') as $item) {
                    $variant = ProductVariant::findOrFail($item['product_variant_id']);
                    if ($variant->stock < $item['quantity']) {
                        throw new \Exception("Stok tidak mencukupi untuk varian {$variant->sku}. Tersedia: {$variant->stock}, Diminta: {$item['quantity']}.");
                    }
                }

                $transaction = StockTransaction::create([
                    'transaction_number' => $transactionNumber,
                    'type' => 'outbound',
                    'date' => $request->input('date'),
                    'note' => $request->input('note'),
                    'created_by' => Auth::id() ?? 1,
                ]);

                foreach ($request->input('items') as $item) {
                    $variant = ProductVariant::findOrFail($item['product_variant_id']);
                    
                    // Create transaction detail
                    $transaction->items()->create([
                        'product_variant_id' => $variant->id,
                        'quantity' => $item['quantity'],
                    ]);

                    // Adjust Stock & Log Mutation
                    $this->stockService->adjustStock(
                        $variant,
                        $item['quantity'],
                        'outbound',
                        $request->input('note') ?? 'Terjual ke Pelanggan',
                        $transaction,
                        $request->input('date')
                    );
                }
            });

            return redirect()
                ->route('stock.outbound.index')
                ->with('success', 'Transaksi barang keluar berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generateTransactionNumber($type, $dateString)
    {
        $prefix = $type === 'inbound' ? 'TX-IN' : 'TX-OUT';
        $datePart = date('Ymd', strtotime($dateString));
        $searchPattern = $prefix . '-' . $datePart . '-%';

        $lastTransaction = StockTransaction::where('transaction_number', 'like', $searchPattern)
            ->orderBy('transaction_number', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = $lastTransaction->transaction_number;
            $parts = explode('-', $lastNumber);
            $sequence = intval(end($parts)) + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $datePart, $sequence);
    }
}
