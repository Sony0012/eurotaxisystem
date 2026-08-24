<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ActivityLogController;

class SparePartController extends Controller
{
    /**
     * Dedicated UI for Manage Inventory
     */
    public function manage()
    {
        $totalParts = SparePart::count();
        $totalStockValue = SparePart::select(DB::raw('SUM(price * stock_quantity) as total'))->value('total') ?? 0;
        $outOfStock = SparePart::where('stock_quantity', '<=', 0)->count();

        return view('inventory.index', compact('totalParts', 'totalStockValue', 'outOfStock'));
    }

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
     * Get purchase history (API)
     */
    public function history()
    {
        $history = DB::table('expenses')
            ->where(function ($q) {
                $q->where('category', 'like', '%part%')
                  ->orWhere('category', 'like', '%maintenance%');
            })
            ->whereNull('deleted_at')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Search and suggest real automotive part photos from online & local catalog
     */
    public function suggestImages(Request $request)
    {
        $query = trim($request->input('query', ''));
        if (empty($query)) {
            return response()->json(['success' => true, 'images' => []]);
        }

        $results = [];

        // 1. DuckDuckGo Image API search for real photographic car parts
        try {
            $tokenUrl = "https://duckduckgo.com/?q=" . urlencode($query . ' automotive part white background') . "&t=h_&iar=images&iax=images&ia=images";
            $ch = curl_init($tokenUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $html = curl_exec($ch);
            curl_close($ch);

            if ($html && preg_match('/vqd=([0-9-_]+)/', $html, $m)) {
                $vqd = $m[1];
                $apiUrl = "https://duckduckgo.com/i.js?l=us-en&o=json&q=" . urlencode($query . ' automotive part') . "&vqd=" . $vqd . "&f=,,,&p=1";
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Referer: https://duckduckgo.com/']);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $json = curl_exec($ch);
                curl_close($ch);

                $data = json_decode($json, true);
                if (!empty($data['results'])) {
                    foreach ($data['results'] as $item) {
                        if (!empty($item['image']) && !empty($item['thumbnail'])) {
                            $results[] = [
                                'thumb' => $item['thumbnail'],
                                'image' => $item['image'],
                                'title' => $item['title'] ?? 'Car Part Photo'
                            ];
                        }
                        if (count($results) >= 8) break;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Image Search Error: ' . $e->getMessage());
        }

        // 2. Openverse API Search (High-res actual Creative Commons photographs)
        if (count($results) < 8) {
            try {
                $needed = 8 - count($results);
                $ovUrl = "https://api.openverse.org/v1/images/?q=" . urlencode($query . ' car part') . "&page_size=" . $needed;
                $ch = curl_init($ovUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'EuroTaxi/1.0 (https://eurotaxisystem.site)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 4);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $ovJson = curl_exec($ch);
                curl_close($ch);
                $ovData = json_decode($ovJson, true);
                if (!empty($ovData['results'])) {
                    foreach ($ovData['results'] as $item) {
                        if (!empty($item['url'])) {
                            $results[] = [
                                'thumb' => $item['thumbnail'] ?? $item['url'],
                                'image' => $item['url'],
                                'title' => $item['title'] ?? 'Car Part Photo'
                            ];
                        }
                        if (count($results) >= 8) break;
                    }
                }
            } catch (\Exception $e) {}
        }

        // 3. Fallback to Wikimedia Commons if few results
        if (count($results) < 4) {
            try {
                $wikiUrl = "https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrnamespace=6&gsrsearch=" . urlencode($query . " car part filetype:bitmap") . "&gsrlimit=6&prop=imageinfo&iiprop=url|thumburl&iiurlwidth=300&format=json";
                $ch = curl_init($wikiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'EuroTaxi/1.0 (https://eurotaxisystem.site; admin@eurotaxisystem.site)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 4);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $wikiJson = curl_exec($ch);
                curl_close($ch);
                $wikiData = json_decode($wikiJson, true);
                if (!empty($wikiData['query']['pages'])) {
                    foreach ($wikiData['query']['pages'] as $p) {
                        if (!empty($p['imageinfo'][0])) {
                            $info = $p['imageinfo'][0];
                            $results[] = [
                                'thumb' => $info['thumburl'] ?? $info['url'],
                                'image' => $info['url'],
                                'title' => $p['title'] ?? 'Auto Part Image'
                            ];
                        }
                        if (count($results) >= 8) break;
                    }
                }
            } catch (\Exception $e) {}
        }

        return response()->json([
            'success' => true,
            'query'   => $query,
            'images'  => $results
        ]);
    }

    /**
     * Store or Update a spare part
     * qty_to_add = units being purchased/restocked (always additive)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id'         => 'nullable|integer|exists:spare_parts,id',
            'name'       => 'required|string|max:100',
            'category'   => 'nullable|string|max:100',
            'price'      => 'required|numeric|min:0.01|max:99999.99',
            'qty_to_add' => 'nullable|integer|min:0|max:999',
            'supplier'   => 'nullable|string|max:255',
            'image_url'  => 'nullable|string|max:1000',
        ]);

        $qtyToAdd = (int)($data['qty_to_add'] ?? 0);

        if (isset($data['id'])) {
            // ── UPDATE existing part ──────────────────────────────────────
            $part = SparePart::where('id', $data['id'])->firstOrFail();

            // Enforce add-only: never let qty decrease via this form
            if ($qtyToAdd < 0) {
                return response()->json(['success' => false, 'message' => 'Cannot reduce stock from here.'], 422);
            }

            $newStock = (int)($part->stock_quantity ?? 0) + $qtyToAdd;

            $part->update([
                'name'           => $data['name'],
                'category'       => $data['category'] ?? $part->category,
                'price'          => $data['price'],
                'stock_quantity' => $newStock,
                'supplier'       => $data['supplier'] ?? $part->supplier,
                'image_url'      => $data['image_url'] ?? $part->image_url,
            ]);
        } else {
            // ── CREATE new part ───────────────────────────────────────────
            $part = SparePart::create([
                'name'           => $data['name'],
                'category'       => $data['category'] ?? null,
                'price'          => $data['price'],
                'stock_quantity' => $qtyToAdd,
                'supplier'       => $data['supplier'] ?? null,
                'image_url'      => $data['image_url'] ?? null,
            ]);
        }

        // ── Auto-record as Office Expense if stock was added ──────────
        $expenseId = null;
        if ($qtyToAdd > 0) {
            $totalCost = $qtyToAdd * (float)$data['price'];
            $userId    = auth()->id() ?? (\App\Models\User::first()->id ?? 18);

            try {
                $expense = \App\Models\Expense::create([
                    'category'         => 'Spare Parts Purchase',
                    'expense_category' => 'Spare Parts Purchase',
                    'spare_part_id'    => $part->id,
                    'quantity'         => $qtyToAdd,
                    'unit_price'       => (float)$data['price'],
                    'description'      => "Inventory STOCK: {$qtyToAdd} pcs of {$part->name}",
                    'vendor_name'      => $part->supplier ?? 'Unspecified Supplier',
                    'amount'           => $totalCost,
                    'date'             => now()->toDateString(),
                    'status'           => 'approved',
                    'recorded_by'      => $userId,
                    'created_by'       => $userId,
                ]);
                $expenseId = $expense->id;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Inventory Expense Record Failed: ' . $e->getMessage());
            }
        }

        $msg = $expenseId
            ? "✅ Stock added! +{$qtyToAdd} pcs of {$part->name} — Purchase recorded in Office Expenses."
            : ($qtyToAdd === 0 ? "Part details updated successfully." : "Stock updated.");

        // Record Activity
        $action = isset($data['id']) ? 'Updated Spare Part' : 'Created Spare Part';
        $logNotes = "Part: {$part->name}\nPrice: ₱" . number_format($part->price, 2);
        if ($qtyToAdd > 0) $logNotes .= "\nStock Added: +{$qtyToAdd} units (New total: {$part->stock_quantity})";
        if ($expenseId) $logNotes .= "\nOffice Expense recorded: #{$expenseId}";
        ActivityLogController::log($action, $logNotes);

        return response()->json([
            'success'          => true,
            'message'          => $msg,
            'expense_recorded' => $expenseId !== null,
            'data'             => $part->fresh(),
        ]);
    }

    /**
     * Get archived spare parts (API)
     */
    public function archived()
    {
        $parts = SparePart::onlyTrashed()->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $parts
        ]);
    }

    /**
     * Delete a spare part (Soft Delete)
     */
    public function destroy($id)
    {
        $part = SparePart::where('id', $id)->firstOrFail();
        $name = $part->name;
        $part->delete();

        ActivityLogController::log('Archived Spare Part', "Part: {$name} moved to archive.");

        return response()->json([
            'success' => true,
            'message' => 'Part moved to archive successfully'
        ]);
    }

    /**
     * Restore a deleted spare part
     */
    public function restore($id)
    {
        $part = SparePart::withTrashed()->where('id', $id)->firstOrFail();
        $part->restore();

        ActivityLogController::log('Restored Spare Part', "Part: {$part->name} restored from archive.");

        return response()->json([
            'success' => true,
            'message' => 'Part restored from archive successfully'
        ]);
    }

    /**
     * Permanently delete a spare part
     */
    public function forceDelete($id, Request $request)
    {
        $password = $request->input('archive_password');
        if (!\App\Models\SystemSetting::verifyPassword($password)) {
            $msg = !\App\Models\SystemSetting::get('archive_deletion_password') 
                ? 'Archive deletion password is not set. Please set it in the System Security tab.' 
                : 'Invalid archive deletion password.';
            return response()->json(['success' => false, 'message' => $msg], 403);
        }

        $part = SparePart::withTrashed()->where('id', $id)->firstOrFail();
        $name = $part->name;
        $part->forceDelete();

        ActivityLogController::log('Permanently Deleted Spare Part', "Part: {$name} was permanently removed from unique system records.");

        return response()->json([
            'success' => true,
            'message' => 'Part permanently removed'
        ]);
    }
}

