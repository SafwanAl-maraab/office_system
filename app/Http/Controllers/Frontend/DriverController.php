<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * عرض السائقين
     */
    /**
     * عرض السائقين
     */
    public function index(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $query = Driver::query()
            ->where('branch_id', $branchId);

        // البحث
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        // الإحصائيات
        $totalDrivers = Driver::where('branch_id', $branchId)->count();

        $activeDrivers = Driver::where('branch_id', $branchId)
            ->where('status', 'active')
            ->count();

        $inactiveDrivers = Driver::where('branch_id', $branchId)
            ->where('status', 'inactive')
            ->count();

        $suspendedDrivers = Driver::where('branch_id', $branchId)
            ->where('status', 'suspended')
            ->count();

        // البيانات
        $drivers = $query
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view(
            'frontend.drivers.index',
            compact(
                'drivers',
                'totalDrivers',
                'activeDrivers',
                'inactiveDrivers',
                'suspendedDrivers'
            )
        );
    }
    /**
     * إضافة سائق
     */
    public function store(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'license_number' => 'required|string|max:100|unique:drivers,license_number',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        Driver::create([
            'branch_id' => $branchId,
            'name' => $request->name,
            'phone' => $request->phone,
            'license_number' => $request->license_number,
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'تم إضافة السائق بنجاح.'
        );
    }

    /**
     * تحديث سائق
     */
    public function update(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $driver = Driver::where('branch_id', $branchId)
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'license_number' => 'required|string|max:100|unique:drivers,license_number,' . $driver->id,
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $driver->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'license_number' => $request->license_number,
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'تم تحديث بيانات السائق.'
        );
    }

    /**
     * حذف سائق
     */
    public function destroy($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $driver = Driver::withCount([
            'travels',
            'trips'
        ])
            ->where('branch_id', $branchId)
            ->findOrFail($id);

        if (
            $driver->travels_count > 0 ||
            $driver->trips_count > 0
        ) {

            return back()->withErrors([
                'error' => 'لا يمكن حذف السائق لوجود رحلات مرتبطة به.'
            ]);
        }

        $driver->delete();

        return back()->with(
            'success',
            'تم حذف السائق بنجاح.'
        );
    }
}
