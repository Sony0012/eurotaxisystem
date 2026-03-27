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

        return view('archive.index', compact(
            'archivedUnits',
            'archivedDrivers',
            'archivedExpenses',
            'archivedBoundaries',
            'archivedMaintenance',
            'archivedFranchiseCases',
            'archivedStaff'
        ));
    }

    public function restore($type, $id)
    {
        $model = $this->getModelByType($type);
        if (!$model) {
            return back()->with('error', 'Invalid model type.');
        }

        $item = $model::withTrashed()->findOrFail($id);
        $item->restore();

        return back()->with('success', ucfirst($type) . ' restored successfully.');
    }

    public function forceDelete($type, $id)
    {
        $model = $this->getModelByType($type);
        if (!$model) {
            return back()->with('error', 'Invalid model type.');
        }

        $item = $model::withTrashed()->findOrFail($id);
        $item->forceDelete();

        return back()->with('success', ucfirst($type) . ' permanently deleted.');
    }

    private function getModelByType($type)
    {
        return match ($type) {
            'unit' => Unit::class,
            'driver' => Driver::class,
            'expense' => Expense::class,
            'boundary' => Boundary::class,
            'maintenance' => Maintenance::class,
            'franchise_case' => FranchiseCase::class,
            'staff' => Staff::class,
            default => null,
        };
    }
}
