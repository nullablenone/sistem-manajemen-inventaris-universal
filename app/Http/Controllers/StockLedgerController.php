<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\StockMutation;
use Illuminate\Http\Request;

class StockLedgerController extends Controller
{
    public function index(Request $request)
    {
        $variantId = $request->input('product_variant_id');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $mutations = StockMutation::query()
            ->with(['productVariant.product', 'productVariant.attributeValues', 'reference'])
            ->when($variantId, function ($query, $variantId) {
                return $query->where('product_variant_id', $variantId);
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('date', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('date', '<=', $endDate);
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $variants = ProductVariant::with(['product', 'attributeValues'])->get();

        return view('stock.ledger.index', compact('mutations', 'variants', 'variantId', 'type', 'startDate', 'endDate'));
    }
}
