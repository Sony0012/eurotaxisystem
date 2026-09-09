<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Boundary;
use App\Models\Maintenance;
use App\Models\FranchiseCase;
use App\Models\Staff;
use App\Models\BoundaryRule;
use App\Models\Supplier;

use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    public function index()
    {
        $archivedUnits = Unit::onlyTrashed()->get();
        $archivedDrivers = Driver::onlyTrashed()->get();
        $archivedExpenses = Expense::onlyTrashed()->get();
        $archivedBoundaries = Boundary::onlyTrashed()->get();
        $archivedMaintenance = Maintenance::with('unit')->onlyTrashed()->get();
        $archivedFranchiseCases = FranchiseCase::onlyTrashed()->get();
        $archivedStaff = Staff::onlyTrashed()->get();
        $archivedIncidents = \App\Models\DriverBehavior::onlyTrashed()
            ->leftJoin('units as u', 'driver_behavior.unit_id', '=', 'u.id')
            ->leftJoin('drivers as d', 'driver_behavior.driver_id', '=', 'd.id')
            ->select(
                'driver_behavior.*',
                'u.plate_number',
                DB::raw("TRIM(CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,''))) as driver_name")
            )->get();
        
        $archivedPricingRules = BoundaryRule::onlyTrashed()->get();
        $archivedSuppliers = Supplier::onlyTrashed()->get();
        $archivedSpareParts = \App\Models\SparePart::onlyTrashed()->get();
        $archivedAccidents = \App\Models\RescueRequest::with(['driver', 'unit'])->onlyTrashed()->get();

        // ─── Archived User Accounts (System Login Access) ───
        $archivedUserAccounts = \App\Models\User::onlyTrashed()
            ->where('role', '!=', 'super_admin')
            ->get();

        // ─── Archived Driver Terms ───
        $termsArchiveDir = public_path('uploads/archives/terms');
        $archivedDriverTerms = [];
        if (file_exists($termsArchiveDir)) {
            $archivedDriverTerms = array_values(array_diff(scandir($termsArchiveDir), ['.', '..']));
        }

        return view('archive.index', compact(
            'archivedUnits',
            'archivedDrivers',
            'archivedExpenses',
            'archivedBoundaries',
            'archivedMaintenance',
            'archivedFranchiseCases',
            'archivedStaff',
            'archivedIncidents',
            'archivedAccidents',
            'archivedPricingRules',
            'archivedSuppliers',
            'archivedSpareParts',
            'archivedUserAccounts',
            'archivedDriverTerms'
        ));
    }

    public function restore($type, $id)
    {
        $model = $this->getModelByType($type);
        if (!$model) {
            return back()->with('error', 'Invalid model type.');
        }

        $item = $model::withTrashed()->where('id', $id)->firstOrFail();
        $name = $item->plate_number ?? ($item->full_name ?? ($item->name ?? ($item->case_no ?? ($item->description ?? ("ID# " . $item->id)))));
        $item->restore();

        system_log("Restored " . ucfirst($type), "Item: {$name} was restored from the system archive.");

        if (request()->wantsJson() || request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => ucfirst($type) . ' restored successfully.']);
        }

        return back()->with('success', ucfirst($type) . ' restored successfully.');
    }

    public function forceDelete($type, $id, Request $request)
    {
        $password = $request->input('archive_password');
        if (!\App\Models\SystemSetting::verifyPassword($password)) {
            $msg = !\App\Models\SystemSetting::get('archive_deletion_password')
                ? 'Archive deletion password is not set. Please set it in the System Security tab.'
                : 'Invalid archive deletion password.';

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $model = $this->getModelByType($type);
        if (!$model) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Invalid model type.'], 400);
            }
            return back()->with('error', 'Invalid model type.');
        }

        $item = $model::withTrashed()->where('id', $id)->firstOrFail();
        $name = $item->plate_number ?? ($item->full_name ?? ($item->name ?? ($item->case_no ?? ($item->description ?? ("ID# " . $item->id)))));

        // Safety: Unlink any driver records before permanently deleting a User
        if ($type === 'user') {
            Driver::where('user_id', $item->id)->update(['user_id' => null]);
        }

        // Safety: Unlink any unit assignments before permanently deleting a Driver
        if (in_array($type, ['driver', 'drivers'])) {
            Unit::where('driver_id', $item->id)->update(['driver_id' => null, 'updated_at' => now()]);
            Unit::where('secondary_driver_id', $item->id)->update(['secondary_driver_id' => null, 'updated_at' => now()]);
            Unit::where('current_turn_driver_id', $item->id)->update(['current_turn_driver_id' => null, 'updated_at' => now()]);
        }

        $item->forceDelete();

        system_log("Permanently Deleted " . ucfirst($type), "Item: {$name} was permanently wiped from the database.");

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => ucfirst($type) . ' permanently deleted.']);
        }
        return back()->with('success', ucfirst($type) . ' permanently deleted.');
    }

    public function bulkForceDelete(Request $request)
    {
        $password = $request->input('archive_password');
        if (!\App\Models\SystemSetting::verifyPassword($password)) {
            $msg = !\App\Models\SystemSetting::get('archive_deletion_password')
                ? 'Archive deletion password is not set. Please set it in the System Security tab.'
                : 'Invalid archive deletion password.';

            return response()->json(['success' => false, 'message' => $msg], 422);
        }

        $type = $request->input('type');
        $ids = (array) $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.'], 400);
        }

        // Special handling for driver_terms
        if ($type === 'driver_terms' || $type === 'driver_term') {
            $termsArchiveDir = public_path('uploads/archives/terms');
            $deletedCount = 0;
            foreach ($ids as $filename) {
                $cleanFilename = basename($filename);
                $filePath = $termsArchiveDir . DIRECTORY_SEPARATOR . $cleanFilename;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                    $deletedCount++;
                }
            }
            system_log("Bulk Deleted Driver Terms", "Permanently deleted {$deletedCount} archived driver term document(s).");
            return response()->json(['success' => true, 'message' => "{$deletedCount} term document(s) permanently deleted.", 'deleted_ids' => $ids]);
        }

        $model = $this->getModelByType($type);
        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Invalid model type.'], 400);
        }

        // Safety: Unlink any driver records before permanently deleting Users
        if (in_array($type, ['user', 'users', 'user_account', 'user_accounts', 'driver_account', 'driver_accounts'])) {
            Driver::whereIn('user_id', $ids)->update(['user_id' => null]);
        }

        // Safety: Unlink any unit assignments before permanently deleting Drivers
        if (in_array($type, ['driver', 'drivers'])) {
            Unit::whereIn('driver_id', $ids)->update(['driver_id' => null, 'updated_at' => now()]);
            Unit::whereIn('secondary_driver_id', $ids)->update(['secondary_driver_id' => null, 'updated_at' => now()]);
            Unit::whereIn('current_turn_driver_id', $ids)->update(['current_turn_driver_id' => null, 'updated_at' => now()]);
        }

        $items = $model::withTrashed()->whereIn('id', $ids)->get();
        $count = $items->count();

        foreach ($items as $item) {
            $item->forceDelete();
        }

        system_log("Bulk Permanently Deleted " . ucfirst($type), "Permanently wiped {$count} " . ucfirst($type) . " record(s) from database.");

        return response()->json([
            'success' => true,
            'message' => "{$count} item(s) permanently deleted.",
            'deleted_ids' => $ids
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $type = $request->input('type');
        $ids = (array) $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.'], 400);
        }

        // Special handling for driver_terms
        if ($type === 'driver_terms' || $type === 'driver_term') {
            $termsArchiveDir = public_path('uploads/archives/terms');
            $termsActiveDir = public_path('uploads/terms');
            if (!file_exists($termsActiveDir)) {
                @mkdir($termsActiveDir, 0777, true);
            }
            $restoredCount = 0;
            foreach ($ids as $filename) {
                $cleanFilename = basename($filename);
                $src = $termsArchiveDir . DIRECTORY_SEPARATOR . $cleanFilename;
                $dest = $termsActiveDir . DIRECTORY_SEPARATOR . $cleanFilename;
                if (file_exists($src)) {
                    @rename($src, $dest);
                    $restoredCount++;
                }
            }
            system_log("Bulk Restored Driver Terms", "Restored {$restoredCount} archived driver term document(s).");
            return response()->json(['success' => true, 'message' => "{$restoredCount} term document(s) restored successfully.", 'restored_ids' => $ids]);
        }

        $model = $this->getModelByType($type);
        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Invalid model type.'], 400);
        }

        $items = $model::withTrashed()->whereIn('id', $ids)->get();
        $count = $items->count();

        foreach ($items as $item) {
            $item->restore();
        }

        system_log("Bulk Restored " . ucfirst($type), "Restored {$count} " . ucfirst($type) . " record(s) from the archive.");

        return response()->json([
            'success' => true,
            'message' => "{$count} item(s) restored successfully.",
            'restored_ids' => $ids
        ]);
    }

    private function getModelByType($type)
    {
        return match ($type) {
            'unit', 'units' => Unit::class,
            'driver', 'drivers' => Driver::class,
            'expense', 'expenses' => Expense::class,
            'boundary', 'boundaries' => Boundary::class,
            'maintenance' => Maintenance::class,
            'franchise_case', 'franchise_cases' => FranchiseCase::class,
            'staff' => Staff::class,
            'incident', 'incidents' => \App\Models\DriverBehavior::class,
            'accident', 'accidents' => \App\Models\RescueRequest::class,
            'pricing_rule', 'pricing_rules' => BoundaryRule::class,
            'supplier', 'suppliers' => Supplier::class,
            'spare_part', 'spare_parts' => \App\Models\SparePart::class,
            'user', 'users', 'user_account', 'user_accounts', 'driver_account', 'driver_accounts' => \App\Models\User::class,

            default => null,
        };
    }
}

