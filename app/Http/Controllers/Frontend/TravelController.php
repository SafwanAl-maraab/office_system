<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Models\Driver;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    /**
     * عرض جميع الرحلات
     */
    public function index()
    {
        $branchId = auth()->user()->employee->branch_id;

        $travels = Travel::with(['driver'])
            ->withCount('requests')
            ->where('branch_id', $branchId)
            ->latest()
            ->get();

        $drivers = Driver::where('branch_id', $branchId)
            ->where('status', true)
            ->get();

        return view('frontend.travels.index',
            compact('travels', 'drivers'));
    }

    /**
     * إنشاء رحلة جديدة
     */
    public function store(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $request->validate([
            'travel_date'   => 'required|date',
            'from_location' => 'required|string|max:150',
            'to_location'   => 'required|string|max:150',
            'capacity'      => 'required|integer|min:1',
            'driver_id'     => 'required|exists:drivers,id',
            'notes'         => 'nullable|string',
        ]);

        Travel::create([
            'branch_id'     => $branchId,
            'travel_date'   => $request->travel_date,
            'from_location' => $request->from_location,
            'to_location'   => $request->to_location,
            'capacity'      => $request->capacity,
            'driver_id'     => $request->driver_id,
            'notes'         => $request->notes,
        ]);

        return back()->with('success', 'تم إنشاء الرحلة بنجاح.');
    }

    /**
     * عرض تفاصيل الرحلة
     */
    public function show($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $travel = Travel::with([
            'driver',
            'requests.client'
        ])
            ->where('branch_id', $branchId)
            ->findOrFail($id);

        return view('frontend.travels.show',
            compact('travel'));
    }

    /**
     * تحديث الرحلة
     */
    public function update(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $travel = Travel::where('branch_id', $branchId)
            ->findOrFail($id);

        $request->validate([
            'travel_date'   => 'required|date',
            'from_location' => 'required|string|max:150',
            'to_location'   => 'required|string|max:150',
            'capacity'      => 'required|integer|min:1',
            'driver_id'     => 'required|exists:drivers,id',
            'notes'         => 'nullable|string',
        ]);

        $travel->update([
            'travel_date'   => $request->travel_date,
            'from_location' => $request->from_location,
            'to_location'   => $request->to_location,
            'capacity'      => $request->capacity,
            'driver_id'     => $request->driver_id,
            'notes'         => $request->notes,
        ]);

        return back()->with('success', 'تم تحديث الرحلة.');
    }

    /**
     * حذف الرحلة
     */
    public function destroy($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $travel = Travel::withCount('requests')
            ->where('branch_id', $branchId)
            ->where('id', $id)
            ->first();

        if (!$travel) {
            return redirect()
                ->route('dashboard.travels.index')
                ->withErrors(['error' => 'الرحلة غير موجودة.']);
        }

        if ($travel->requests_count > 0) {
            return redirect()
                ->route('dashboard.travels.index')
                ->withErrors(['error' => 'لا يمكن حذف رحلة مرتبطة بطلبات.']);
        }

        $travel->delete();

        return redirect()
            ->route('dashboard.travels.index')
            ->with('success', 'تم حذف الرحلة بنجاح.');
    }
}
