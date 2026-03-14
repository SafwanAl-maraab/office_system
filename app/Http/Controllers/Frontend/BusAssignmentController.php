<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BusDriver;
use App\Models\Bus;
use App\Models\Driver;

class BusAssignmentController extends Controller
{

    public function index(Request $request)
    {

        $branchId = auth()->user()->employee->branch_id;

        $search = $request->search;

        $records = BusDriver::with([
            'bus.agent',
            'driver'
        ])

            ->where('branch_id',$branchId)

            ->when($search,function($q) use ($search){

                $q->whereHas('bus',function($q) use ($search){

                    $q->where('plate_number','like',"%$search%");

                })
                    ->orWhereHas('driver',function($q) use ($search){

                        $q->where('name','like',"%$search%");

                    });

            })

            ->latest()

            ->paginate(12);



        $buses = Bus::with('agent')
            ->where('branch_id',$branchId)
            ->get();

        $drivers = Driver::where('branch_id',$branchId)
            ->where('status','active')
            ->get();



        return view(
            'frontend.bus_assignments.index',
            compact(
                'records',
                'buses',
                'drivers',
                'search'
            )
        );

    }



    public function store(Request $request)
    {

        $request->validate([

            'bus_id'=>'required',
            'driver_id'=>'required',
            'start_at'=>'required',
            'end_at'=>'nullable'

        ]);


        BusDriver::create([

            'branch_id'=>auth()->user()->employee->branch_id,

            'bus_id'=>$request->bus_id,
            'driver_id'=>$request->driver_id,

            'start_at'=>$request->start_at,
            'end_at'=>$request->end_at,

            'active'=>true

        ]);


        return back()->with('success','تم إضافة السائق');

    }



    public function update(Request $request,$id)
    {

        $record = BusDriver::findOrFail($id);

        $record->update([

            'start_at'=>$request->start_at,
            'end_at'=>$request->end_at

        ]);

        return back()->with('success','تم تعديل الوقت');

    }



    public function destroy($id)
    {

        BusDriver::findOrFail($id)->delete();

        return back()->with('success','تم حذف السائق');

    }

}
