<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TripGroup;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\TripGroupBus;

class TripGroupController extends Controller
{

    public function index()
    {
        $tripGroups = TripGroup::with('tripGroupBuses.bus','tripGroupBuses.driver')
            ->latest()
            ->paginate(12);

        $buses = Bus::where('status',1)->get();
        $drivers = Driver::where('status',1)->get();

        return view('frontend.trip-groups.index',
            compact('tripGroups','buses','drivers'));
    }


    public function store(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:150',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:departure_date',
            'total_seats' => 'required|integer|min:1'

        ]);

        TripGroup::create([

            'branch_id' => auth()->user()->employee->branch_id,
            'name' => $request->name,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date,
            'total_seats' => $request->total_seats,
            'status' => 1

        ]);

        return back()->with('success','تم إنشاء الحملة');
    }



    public function attachBus(Request $request)
    {

        $request->validate([

            'trip_group_id' => 'required',
            'bus_id' => 'required',
            'driver_id' => 'required'

        ]);


        TripGroupBus::create([

            'trip_group_id' => $request->trip_group_id,
            'bus_id' => $request->bus_id,
            'driver_id' => $request->driver_id

        ]);


        return back()->with('success','تم ربط الباص بالحملة');

    }


}