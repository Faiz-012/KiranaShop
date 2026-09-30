<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::get();

        return response()->json([
            'success' => true,
            'data' => $supplier
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
        $validate = $request->validate([
            'name'         => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:15',
        ]);

        $supplier = Supplier::create($validate);

        return response()->json([
            'success' => true,
            'message' => 'Supplier Created successFully',
            'data' => $supplier
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Supplier = Supplier::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $Supplier
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
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:15',
        ]);

        $supplier->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'supplier updated successfully',
            'data' => $supplier
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Supplier = Supplier::findOrFail($id);

        $Supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'supplier deleted Successfully'
        ], 200);
    }
}
