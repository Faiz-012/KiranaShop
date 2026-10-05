<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase = Purchase::with('supplier')->get();
        return response()->json([
            'success' => true,
            'data' => $purchase
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
            'supplier_id'   => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'paid_amount'   => 'required|numeric|min:0',
            'items'         => 'required|array|min:1', // items array hona chahiye
            'items.*.item_id'  => 'required|exists:items,id', // har item valid hona chahiye
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        $dueAmount = $totalAmount - $request->paid_amount;

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'total_amount' => $totalAmount,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $dueAmount,
        ]);

        foreach ($request->items as $item) {
            $subTotal = $item['quantity'] * $item['price'];

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $subTotal,
            ]);

            Item::where('id', $item['item_id'])
                ->increment('quantity', $item['quantity']);
        }


        return response()->json([
            'success' => true,
            'message' => 'Purchase created successfully!',
            'data'    => $purchase
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::with('supplier', 'purchaseItems.item')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $purchase
        ]);
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
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $newPaid = $purchase->paid_amount + $request->paid_amount;

        $newDue = $purchase->total_amount - $newPaid;

        $purchase->update([
            'paid_amount' => $newPaid,
            'due_amount' => $newDue,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Purchase updated successfully!',
            'data'    => $purchase
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase deleted successfully!'
        ], 200);
    }
}
