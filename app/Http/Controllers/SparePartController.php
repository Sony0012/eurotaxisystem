<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SparePartController extends Controller
{
    /**
     * Get all spare parts (API)
     */
    public function index()
    {
        $parts = SparePart::orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $parts
        ]);
    }

    /**
     * Store or Update a spare part
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|integer|exists:spare_parts,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $quantityAdded = 0;
        if (isset($data['id'])) {
            $part = \App\Models\SparePart::find($data['id']);
            $oldStock = (int)($part->stock_quantity ?? 0);
            $newStock = (int)($data['stock_quantity'] ?? 0);
            if ($newStock > $oldStock) {
                $quantityAdded = $newStock - $oldStock;
            }
            $part->update($data);
        } else {
            $part = \App\Models\SparePart::create($data);
            $quantityAdded = (int)($data['stock_quantity'] ?? 0);
        }

        // Auto-record as Office Expense if stock was added
        if ($quantityAdded > 0) {
            $totalCost = $quantityAdded * (float)$data['price'];
            \App\Models\Expense::create([
                'category' => 'Maintenance Supplies',
                'description' => "PURCHASED: {$quantityAdded} pcs of {$data['name']} from " . ($data['supplier'] ?? 'Unspecified Supplier'),
                'amount' => $totalCost,
                'date' => now()->toDateString(),
                'status' => 'approved',
                'recorded_by' => auth()->id(),
                'notes' => 'Auto-generated from Inventory Management update.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Part saved and expense recorded successfully',
            'data' => $part
        ]);
    }

    /**
     * Delete a spare part
     */
    public function destroy($id)
    {
        $part = SparePart::findOrFail($id);
        $part->delete();

        return response()->json([
            'success' => true,
            'message' => 'Part deleted successfully'
        ]);
    }
}
