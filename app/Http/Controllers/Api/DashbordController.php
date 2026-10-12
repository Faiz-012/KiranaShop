<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerLedger;
use App\Models\Item;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashbordController extends Controller
{

    //     Dashboard API mein kya hoga:

    // → Today's sales total
    // → Total stock value
    // → Low stock items alert
    // → Total customers due
    // → Recent sales
    function index()
    {

        $today = now()->toDateString();

        $todaySales = Sale::whereDate('sale_date', $today)->sum('total_amount');

        $totalStokeValue = Item::sum(DB::raw('quantity * selling_price'));

        $lowstock = Item::where('quantity', '<=', DB::raw('low_stock_alert'))->get();

        $totalCredit = CustomerLedger::where('type', 'credit')->sum('amount');
        $totalDebit  = CustomerLedger::where('type', 'debit')->sum('amount');
        $totalDue    = $totalCredit - $totalDebit;

        $recentSales = Sale::with('customer')->orderBy('created_at', 'desc')->limit(5)->get();

        return response()->json([
            'success' => true,
            'today_sales' => $todaySales,
            'stock_value' => $totalStokeValue,
            'low_stock_alert' => $lowstock,
            'total_due' => $totalDue,
            'recent_sales' => $recentSales
        ]);
    }
}
