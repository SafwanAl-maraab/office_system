<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Bus;
use App\Models\Driver;
use Illuminate\Http\Request;

class BusController extends Controller
{

    public function index(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $query = Bus::with([
            'agent',
            'drivers'
        ])
            ->withCount([
                'drivers',
                'trips'
            ])
            ->where('branch_id', $branchId);

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('plate_number', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        $buses = $query
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $totalBuses = Bus::where('branch_id', $branchId)->count();

        $activeBuses = Bus::where('branch_id', $branchId)
            ->where('status', 'active')
            ->count();

        $maintenanceBuses = Bus::where('branch_id', $branchId)
            ->where('status', 'maintenance')
            ->count();

        $inactiveBuses = Bus::where('branch_id', $branchId)
            ->where('status', 'inactive')
            ->count();

        $agents = Agent::where('branch_id', $branchId)
            ->where('status', true)
            ->orderBy('name')
            ->get();


        return view(
            'frontend.buses.index',
            compact(
                'buses',
                'agents',
                'totalBuses',
                'activeBuses',
                'maintenanceBuses',
                'inactiveBuses'
            )
        );
    }


    public function store(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $request->validate([
            'plate_number' => 'required|max:100|unique:buses,plate_number',
            'agent_id'     => 'nullable|exists:agents,id',
            'model'        => 'nullable|max:150',
            'capacity'     => 'required|integer|min:1',
            'status'       => 'required|in:active,maintenance,inactive',
        ]);

        Bus::create([
            'branch_id'   => $branchId,
            'plate_number'=> $request->plate_number,
            'agent_id'    => $request->agent_id,
            'model'       => $request->model,
            'capacity'    => $request->capacity,
            'status'      => $request->status,
        ]);

        return back()->with(
            'success',
            'تم إضافة الحافلة بنجاح.'
        );
    }


    public function update(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $bus = Bus::where('branch_id', $branchId)
            ->findOrFail($id);

        $request->validate([
            'plate_number' => 'required|max:100|unique:buses,plate_number,' . $bus->id,
            'agent_id'     => 'nullable|exists:agents,id',
            'model'        => 'nullable|max:150',
            'capacity'     => 'required|integer|min:1',
            'status'       => 'required|in:active,maintenance,inactive',
        ]);

        $bus->update([
            'plate_number'=> $request->plate_number,
            'agent_id'    => $request->agent_id,
            'model'       => $request->model,
            'capacity'    => $request->capacity,
            'status'      => $request->status,
        ]);

        return back()->with(
            'success',
            'تم تحديث الحافلة بنجاح.'
        );
    }


    public function destroy($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $bus = Bus::withCount([
            'trips',
            'drivers'
        ])
            ->where('branch_id', $branchId)
            ->findOrFail($id);

        if ($bus->trips_count > 0) {

            return back()->withErrors([
                'error' => 'لا يمكن حذف الحافلة لوجود رحلات مرتبطة بها.'
            ]);
        }

        $bus->delete();

        return back()->with(
            'success',
            'تم حذف الحافلة بنجاح.'
        );
    }


}
