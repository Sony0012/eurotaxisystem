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
        $rawQuery = trim($request->input('query', ''));
        if (empty($rawQuery)) {
            return response()->json(['success' => true, 'images' => []]);
        }

        $results = [];

        // 1. First inject High-Definition Curated Real Product Photos for the specific component
        $curated = $this->getCuratedRealPartPhotos($rawQuery);
        if (!empty($curated)) {
            $results = array_merge($results, $curated);
        }

        // 2. DuckDuckGo Image API search for real photographic car parts
        try {
            $cleanQuery = trim(preg_replace('/[^a-zA-Z0-9\s]/', ' ', $rawQuery));
            $searchString = $cleanQuery . ' car spare part';

            $tokenUrl = "https://duckduckgo.com/?q=" . urlencode($searchString) . "&t=h_&iar=images&iax=images&ia=images";
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
                $apiUrl = "https://duckduckgo.com/i.js?l=us-en&o=json&q=" . urlencode($searchString) . "&vqd=" . $vqd . "&f=,,,&p=1";
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
                            $title = strtolower($item['title'] ?? '');
                            // Filter out historic / vintage cars if irrelevant
                            if (str_contains($title, 'bentley') || str_contains($title, 'bugatti') || str_contains($title, '1929') || str_contains($title, '1930')) {
                                continue;
                            }
                            $results[] = [
                                'thumb' => $item['thumbnail'],
                                'image' => $item['image'],
                                'title' => $item['title'] ?? 'Car Part Photo'
                            ];
                        }
                        if (count($results) >= 12) break;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Image Search Error: ' . $e->getMessage());
        }

        // Deduplicate results by image URL
        $unique = [];
        $final = [];
        foreach ($results as $item) {
            $key = $item['thumb'];
            if (!isset($unique[$key])) {
                $unique[$key] = true;
                $final[] = $item;
            }
            if (count($final) >= 10) break;
        }

        return response()->json([
            'success' => true,
            'query'   => $rawQuery,
            'images'  => $final
        ]);
    }

    /**
     * Curated high-resolution genuine photographic parts library
     */
    private function getCuratedRealPartPhotos($query)
    {
        $q = strtolower(trim($query));
        $list = [];

        // 1. Shock Absorbers / Struts
        if (preg_match('/shock|absorber|strut|suspension|coilover/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/repair-car-shock-absorber-garage-car-service-by-masters_124507-54540.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/repair-car-shock-absorber-garage-car-service-by-masters_124507-54540.jpg?w=2000',
                'title' => 'KYB Hydraulic Strut & Spring Shock Absorber'
            ];
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/new-parts-auto-repair-car-shock-absorber-insulated-white-background_124507-44535.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/new-parts-auto-repair-car-shock-absorber-insulated-white-background_124507-44535.jpg?w=2000',
                'title' => 'Gas-Pressurized Front Shock Absorber'
            ];
            $list[] = [
                'thumb' => 'https://image.made-in-china.com/2f0j00mCdkoBUrMiqh/Japanese-Auto-Parts-Spring-Shock-Absorber-for-Toyota-Nissan-Mazda-Hyundai-Mitsubishi-Suspension-Car-Accessories.jpg',
                'image' => 'https://image.made-in-china.com/2f0j00mCdkoBUrMiqh/Japanese-Auto-Parts-Spring-Shock-Absorber-for-Toyota-Nissan-Mazda-Hyundai-Mitsubishi-Suspension-Car-Accessories.jpg',
                'title' => 'Toyota Vios Front Strut Assembly'
            ];
            $list[] = [
                'thumb' => 'https://thumbs.dreamstime.com/z/shock-absorber-front-car-suspension-component-isolated-white-background-d-174197507.jpg',
                'image' => 'https://thumbs.dreamstime.com/z/shock-absorber-front-car-suspension-component-isolated-white-background-d-174197507.jpg',
                'title' => 'Heavy Duty Rear Shock Absorber Pair'
            ];
        }

        // 2. Brake Pads
        if (preg_match('/pad|brake/i', $q) && !preg_match('/fluid|hose|disc|disk|rotor|shoe/i', $q)) {
            $list[] = [
                'thumb' => 'https://images.unsplash.com/photo-1600705722908-bab1e61c0b4d?w=400&auto=format&fit=crop&q=80',
                'image' => 'https://images.unsplash.com/photo-1600705722908-bab1e61c0b4d?w=1200&auto=format&fit=crop&q=80',
                'title' => 'Ceramic Disc Brake Pads Set'
            ];
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/brake-pads-car-isolated-white-background_124507-42231.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/brake-pads-car-isolated-white-background_124507-42231.jpg?w=2000',
                'title' => 'Toyota Genuine Front Brake Pads'
            ];
        }

        // 3. Brake Discs / Rotors
        if (preg_match('/disc|disk|rotor/i', $q)) {
            $list[] = [
                'thumb' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=400&auto=format&fit=crop&q=80',
                'image' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=1200&auto=format&fit=crop&q=80',
                'title' => 'Vented Brake Rotor Disk'
            ];
        }

        // 4. Batteries
        if (preg_match('/battery|motolite/i', $q)) {
            $list[] = [
                'thumb' => 'https://cdn.shopify.com/s/files/1/0615/7982/1304/files/Motolite_Gold.png?v=1687245245',
                'image' => 'https://cdn.shopify.com/s/files/1/0615/7982/1304/files/Motolite_Gold.png?v=1687245245',
                'title' => 'Motolite Gold 12V Maintenance Free Battery'
            ];
        }

        // 5. Spark Plugs
        if (preg_match('/spark|plug|iridium/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/set-new-spark-plugs-isolated-white-background_124507-42119.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/set-new-spark-plugs-isolated-white-background_124507-42119.jpg?w=2000',
                'title' => 'Denso Iridium Power Spark Plugs'
            ];
        }

        // 6. Engine Oil & Lubricants
        if (preg_match('/engine\s*oil|synthetic|oil\s*5w|oil\s*10w|lubricant/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/canister-with-engine-oil-isolated-white-background_124507-39120.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/canister-with-engine-oil-isolated-white-background_124507-39120.jpg?w=2000',
                'title' => 'Fully Synthetic 5W-30 Motor Oil'
            ];
        }

        // 7. Oil Filter
        if (preg_match('/oil\s*filter/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/car-oil-filter-isolated-white-background_124507-43105.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/car-oil-filter-isolated-white-background_124507-43105.jpg?w=2000',
                'title' => 'Toyota Genuine Spin-on Oil Filter'
            ];
        }

        // 8. Air & Cabin Filter
        if (preg_match('/air\s*filter|cabin\s*filter/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/car-air-filter-isolated-white-background_124507-43156.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/car-air-filter-isolated-white-background_124507-43156.jpg?w=2000',
                'title' => 'Engine Air Filter Element'
            ];
        }

        // 9. Coolant
        if (preg_match('/coolant|antifreeze/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/canister-with-radiator-coolant-isolated-white-background_124507-39144.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/canister-with-radiator-coolant-isolated-white-background_124507-39144.jpg?w=2000',
                'title' => 'Long Life Engine Radiator Coolant'
            ];
        }

        // 10. Wiper Blades
        if (preg_match('/wiper|blade/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/car-windshield-wiper-blades-isolated-white-background_124507-44012.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/car-windshield-wiper-blades-isolated-white-background_124507-44012.jpg?w=2000',
                'title' => 'Aerodynamic Hybrid Wiper Blades (Pair)'
            ];
        }

        // 11. Belts
        if (preg_match('/belt|serpentine|timing/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/new-car-timing-belt-isolated-white-background_124507-44111.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/new-car-timing-belt-isolated-white-background_124507-44111.jpg?w=2000',
                'title' => 'Multi-Rib Serpentine Fan Belt'
            ];
        }

        // 12. Tie Rod / Ball Joint
        if (preg_match('/tie\s*rod|ball\s*joint|rack\s*end|suspension/i', $q)) {
            $list[] = [
                'thumb' => 'https://img.freepik.com/premium-photo/new-steering-tie-rod-end-isolated-white-background_124507-44210.jpg?w=400',
                'image' => 'https://img.freepik.com/premium-photo/new-steering-tie-rod-end-isolated-white-background_124507-44210.jpg?w=2000',
                'title' => 'Front Suspension Outer Tie Rod End'
            ];
        }

        return $list;
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

