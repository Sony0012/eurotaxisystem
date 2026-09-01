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
        $baseUrl = url('image/parts_photos');

        // 1. Tires, Wheels & Rims
        if (preg_match('/tire|tyre|wheel|rim|mag\s*wheel|radial|tubeless/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/tire.png",
                'image' => "{$baseUrl}/tire.png",
                'title' => 'Radial Tubeless Car Tire & Mag Wheel'
            ];
            $list[] = [
                'thumb' => 'https://image.made-in-china.com/2f0j00mCdkoBUrMiqh/Japanese-Auto-Parts-Spring-Shock-Absorber-for-Toyota-Nissan-Mazda-Hyundai-Mitsubishi-Suspension-Car-Accessories.jpg',
                'image' => 'https://image.made-in-china.com/2f0j00mCdkoBUrMiqh/Japanese-Auto-Parts-Spring-Shock-Absorber-for-Toyota-Nissan-Mazda-Hyundai-Mitsubishi-Suspension-Car-Accessories.jpg',
                'title' => 'Alloy Wheel Rim Component'
            ];
        }

        // 2. Shock Absorbers / Struts
        if (preg_match('/shock|absorber|strut|suspension|coilover|spring/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/shock_absorber.png",
                'image' => "{$baseUrl}/shock_absorber.png",
                'title' => 'KYB Coilover Spring & Strut Shock Absorber'
            ];
            $list[] = [
                'thumb' => 'https://image.made-in-china.com/2f0j00mCdkoBUrMiqh/Japanese-Auto-Parts-Spring-Shock-Absorber-for-Toyota-Nissan-Mazda-Hyundai-Mitsubishi-Suspension-Car-Accessories.jpg',
                'image' => 'https://image.made-in-china.com/2f0j00mCdkoBUrMiqh/Japanese-Auto-Parts-Spring-Shock-Absorber-for-Toyota-Nissan-Mazda-Hyundai-Mitsubishi-Suspension-Car-Accessories.jpg',
                'title' => 'Toyota Vios Front Strut Assembly'
            ];
        }

        // 3. Brake Discs / Rotors
        if (preg_match('/disc|disk|rotor/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/brake_disk.png",
                'image' => "{$baseUrl}/brake_disk.png",
                'title' => 'Cast Iron Vented Brake Disc Rotor'
            ];
        }

        // 4. Brake Pads & Calipers
        if (preg_match('/pad|brake|caliper/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/brake_pads.png",
                'image' => "{$baseUrl}/brake_pads.png",
                'title' => 'Ceramic Disc Brake Pads Set'
            ];
            $list[] = [
                'thumb' => "{$baseUrl}/brake_disk.png",
                'image' => "{$baseUrl}/brake_disk.png",
                'title' => 'Vented Brake Disc Rotor'
            ];
        }

        // 5. Batteries
        if (preg_match('/battery|motolite|amaron|yuasa/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/battery.png",
                'image' => "{$baseUrl}/battery.png",
                'title' => 'Motolite Gold 12V Maintenance Free Battery'
            ];
            $list[] = [
                'thumb' => 'https://cdn.shopify.com/s/files/1/0615/7982/1304/files/Motolite_Gold.png?v=1687245245',
                'image' => 'https://cdn.shopify.com/s/files/1/0615/7982/1304/files/Motolite_Gold.png?v=1687245245',
                'title' => 'Motolite 12V Automotive Battery'
            ];
        }

        // 6. Spark Plugs & Ignition Coils
        if (preg_match('/spark|plug|iridium|ignition|coil/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/spark_plug.png",
                'image' => "{$baseUrl}/spark_plug.png",
                'title' => 'Denso Iridium Power Spark Plug'
            ];
        }

        // 7. Engine Oil & Lubricants
        if (preg_match('/engine\s*oil|synthetic|oil\s*5w|oil\s*10w|oil\s*20w|lubricant|motul|castrol|shell/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/engine_oil.png",
                'image' => "{$baseUrl}/engine_oil.png",
                'title' => 'Fully Synthetic 5W-30 Motor Oil'
            ];
        }

        // 8. Oil Filter
        if (preg_match('/oil\s*filter/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/oil_filter.png",
                'image' => "{$baseUrl}/oil_filter.png",
                'title' => 'Toyota Genuine Spin-on Oil Filter'
            ];
        }

        // 9. Air & Cabin Filter
        if (preg_match('/air\s*filter|cabin\s*filter/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/air_filter.png",
                'image' => "{$baseUrl}/air_filter.png",
                'title' => 'Engine Air Filter Element'
            ];
        }

        // 10. Wiper Blades
        if (preg_match('/wiper|blade/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/wiper_blade.png",
                'image' => "{$baseUrl}/wiper_blade.png",
                'title' => 'Aerodynamic Hybrid Wiper Blade'
            ];
        }

        // 11. Belts
        if (preg_match('/belt|serpentine|timing|fan\s*belt|alternator\s*belt/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/timing_belt.png",
                'image' => "{$baseUrl}/timing_belt.png",
                'title' => 'Multi-Rib Serpentine Timing Belt'
            ];
        }

        // 12. Alternator & Starter Motor
        if (preg_match('/alternator|starter|dynamo/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/alternator.png",
                'image' => "{$baseUrl}/alternator.png",
                'title' => '12V 80A Automotive Alternator'
            ];
        }

        // 13. Window & Automotive Glass
        if (preg_match('/window|glass|windshield|windscreen|tint|regulator/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/window_glass.png",
                'image' => "{$baseUrl}/window_glass.png",
                'title' => 'Tempered Automotive Car Window Glass'
            ];
        }

        // 14. Mirrors & Body Panels
        if (preg_match('/mirror|side\s*mirror|rearview/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/side_mirror.png",
                'image' => "{$baseUrl}/side_mirror.png",
                'title' => 'Aerodynamic Electric Side Wing Mirror'
            ];
        }

        // 15. Exhaust & Emissions
        if (preg_match('/exhaust|muffler|pipe|header|catalytic|downpipe/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/exhaust.png",
                'image' => "{$baseUrl}/exhaust.png",
                'title' => 'Polished Dual Tip Stainless Exhaust Muffler'
            ];
        }

        // 16. Engine Internals (Piston & Rods)
        if (preg_match('/piston|rod|crank|cam|valve|engine\s*block/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/piston.png",
                'image' => "{$baseUrl}/piston.png",
                'title' => 'Forged Aluminum Engine Piston & Rod'
            ];
        }

        // 17. Cooling & Radiators
        if (preg_match('/radiator|condenser|cooling\s*fan/i', $q)) {
            $list[] = [
                'thumb' => "{$baseUrl}/radiator.png",
                'image' => "{$baseUrl}/radiator.png",
                'title' => 'Aluminum Engine Cooling Radiator'
            ];
        }

        // 18. General Fallback Real Automotive Part Images
        if (empty($list)) {
            $list[] = [
                'thumb' => "{$baseUrl}/brake_disk.png",
                'image' => "{$baseUrl}/brake_disk.png",
                'title' => 'Precision Automotive Component'
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
        // Sanitize incoming request inputs
        $input = $request->all();
        if (isset($input['id']) && (empty($input['id']) || $input['id'] === 'null' || $input['id'] === '0')) {
            $input['id'] = null;
        }
        if (isset($input['supplier']) && trim($input['supplier']) === '') {
            $input['supplier'] = null;
        }
        if (isset($input['category']) && trim($input['category']) === '') {
            $input['category'] = null;
        }
        $request->replace($input);

        $data = $request->validate([
            'id'         => 'nullable|integer|exists:spare_parts,id',
            'name'       => ['required', 'string', 'min:2', 'max:70', 'regex:/^[a-zA-Z0-9\s\(\)\/\-\.,]+$/'],
            'category'   => 'nullable|string|max:100',
            'price'      => 'required|numeric|min:0.01|max:500000',
            'qty_to_add' => 'nullable|integer|min:0|max:10000',
            'supplier'   => 'nullable|string|max:255',
            'image_url'  => 'nullable|string',
        ], [
            'name.required'      => 'Part name is required.',
            'name.min'           => 'Part name must be at least 2 characters.',
            'name.max'           => 'Part name cannot exceed 70 characters.',
            'name.regex'         => 'Part name cannot contain special symbols. Only letters, numbers, spaces, and () / - . are allowed.',
            'price.required'     => 'Price is required.',
            'price.numeric'      => 'Price must be a valid number.',
            'price.min'          => 'Price must be at least ₱0.01.',
            'price.max'          => 'Price cannot exceed ₱500,000.00.',
            'qty_to_add.integer' => 'Quantity must be a whole number.',
            'qty_to_add.min'     => 'Quantity cannot be negative.',
            'qty_to_add.max'     => 'Quantity cannot exceed 10,000 units.',
        ]);

        $qtyToAdd = (int)($data['qty_to_add'] ?? 0);
        $partId   = !empty($data['id']) ? (int)$data['id'] : null;

        if ($partId) {
            // ── UPDATE existing part ──────────────────────────────────────
            $part = SparePart::where('id', $partId)->firstOrFail();

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
                'supplier'       => array_key_exists('supplier', $data) ? $data['supplier'] : $part->supplier,
                'image_url'      => array_key_exists('image_url', $data) ? $data['image_url'] : $part->image_url,
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
            : ($partId ? "Part details updated successfully." : "New part added successfully.");

        // Record Activity
        $action = $partId ? 'Updated Spare Part' : 'Created Spare Part';
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

