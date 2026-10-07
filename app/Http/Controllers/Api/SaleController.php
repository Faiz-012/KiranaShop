<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerLedger;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sale = Sale::with('customer', 'saleitems.item')->get();
        return response()->json([
            'success' => true,
            'data' => $sale
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'      => 'nullable|exists:customers,id', // walk-in ke liye null
            'customer_name' => 'nullable|string|max:255',
            'sale_date'        => 'required|date',
            'payment_type'     => 'required|in:cash,credit',
            'items'            => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name, // walk-in customer
            'sale_date' => $request->sale_date,
            'payment_type' => $request->payment_type,
            'total_amount' => $totalAmount
        ]);

        foreach ($request->items as $item) {
            $subTotal = $item['quantity'] * $item['price'];

            SaleItem::create([
                'sale_id' => $sale->id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $subTotal
            ]);

            Item::where('id', $item['item_id'])
                ->decrement('quantity', $item['quantity']);
        }

        if ($request->payment_type === 'credit' && $request->customer_id) {
            CustomerLedger::create([
                'customer_id' => $request->customer_id,
                'type'        => 'credit', // customer pe credit hai
                'amount'      => $totalAmount,
                'note'        => 'Sale #' . $sale->id,
                'date'        => $request->sale_date,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sale created successfully!',
            'data'    => $sale
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::with('customer', 'saleitems.item')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data'    => $sale
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $sale = Sale::findOrFail($id);

         $sale->delete();

        return response()->json([
            'success' => true,
            'message'=> 'Sale record Deleted SuccessFully',
            'data'    => $sale
        ], 201);
    }
}
