<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerLedger;
use Illuminate\Http\Request;

class CustomerLedgerController extends Controller
{
    public function index()
    {
        $customer = Customer::with('customerLedger')->get();

        $data = $customer->map(function ($customer) {
            $credit = $customer->customerLedger->where('type', 'credit')->sum('amount');
            $debit = $customer->customerLedger->where('type', 'debit')->sum('amount');
            $due = $credit - $debit;

            return [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'total_credit'  => $credit,
                'total_debit'   => $debit,
                'due_amount'    => $due,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount'      => 'required|numeric|min:1',
            'note'        => 'nullable|string',
            'date'        => 'required|date',
        ]);

        $ledger = CustomerLedger::create([
            'customer_id' => $request->customer_id,
            'type' => 'debit',
            'amount' => $request->amount,
            'note'        => $request->note,
            'date'        => $request->date,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully!',
            'data' => $ledger
        ], 201);
    }

    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);

        $ledger = CustomerLedger::where('customer_id', $id)->orderBy('date', 'asc')->get();

        $credit = $ledger->where('type', 'credit')->sum('amount');
        $debit = $ledger->where('type', 'debit')->sum('amount');
        $due = $credit - $debit;

        return response()->json([
            'success' => true,
            'customer' => $customer->name,
            'balance' => $due,
            'history' => $ledger
        ]);
    }
}
