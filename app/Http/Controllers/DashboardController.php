<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMutation;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = ProductVariant::sum('stock');
        $totalInbound = StockMutation::where('type', 'inbound')->sum('quantity');
        $totalOutbound = StockMutation::where('type', 'outbound')->sum('quantity');

        // Recent outbound transactions
        $recentSales = StockTransaction::with(['items.productVariant.product', 'createdBy'])
            ->where('type', 'outbound')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalStock',
            'totalInbound',
            'totalOutbound',
            'recentSales'
        ));
    }
}

