<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Trip;
use App\Models\Bus;
use App\Models\Currency;

class TripController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | عرض الرحلات
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        $search = $request->search;

        $trips = Trip::with(['bus','currency','bookings'])

            ->when($search,function($query) use ($search){

                $query->where('from_city','like',"%$search%")
                    ->orWhere('to_city','like',"%$search%")
                    ->orWhereHas('bus',function($q) use ($search){

                        $q->where('plate_number','like',"%$search%")
                            ->orWhere('model','like',"%$search%");

                    });

            })

            ->latest()
            ->paginate(12);


        $buses = Bus::where('status','active')->get();

        $currencies = Currency::all();

        return view('frontend.trips.index',compact(
            'trips',
            'buses',
            'currencies',
            'search'
        ));

    }



    /*
   |--------------------------------------------------------------------------
   | إنشاء رحلة
   |--------------------------------------------------------------------------
   */

    public function store(Request $request)
    {

        $request->validate([

            'bus_id' => 'required|exists:buses,id',

            'from_city' => 'required|string|max:255',

            'to_city' => 'required|string|max:255',

            'trip_date' => 'required|date',

            'trip_time' => 'required',

            'purchase_price' => 'required|numeric',

            'sale_price' => 'required|numeric',

            'currency_id' => 'required|exists:currencies,id'

        ]);


        $bus = Bus::findOrFail($request->bus_id);


        if($bus->status != 'active'){

            return back()->with('error','هذا الباص غير متاح حاليا');

        }


        $exists = Trip::where('bus_id',$request->bus_id)

            ->where('trip_date',$request->trip_date)

            ->where('trip_time',$request->trip_time)

            ->exists();


        if($exists){

            return back()->with('error','هذا الباص لديه رحلة بنفس التاريخ والوقت');

        }
        $branchId = auth()->user()->employee->branch_id;

        Trip::create([

            'branch_id' => $branchId,

            'bus_id' => $request->bus_id,

            'from_city' => $request->from_city,

            'to_city' => $request->to_city,

            'trip_date' => $request->trip_date,

            'trip_time' => $request->trip_time,

            'purchase_price' => $request->purchase_price,

            'sale_price' => $request->sale_price,

            'currency_id' => $request->currency_id,

            'notes' => $request->notes,

            'status' => 'scheduled',

            'created_by' => auth()->id()

        ]);


        $bus->update([

            'status' => 'inactive'

        ]);


        return redirect()
            ->route('trips.index')
            ->with('success','تم إنشاء الرحلة بنجاح');

    }




    /*
   |--------------------------------------------------------------------------
   | تحديث الرحلة
   |--------------------------------------------------------------------------
   */

    public function update(Request $request,$id)
    {

        $trip = Trip::findOrFail($id);

        $request->validate([

            'bus_id' => 'required|exists:buses,id',

            'from_city' => 'required|string|max:255',

            'to_city' => 'required|string|max:255',

            'trip_date' => 'required|date',

            'trip_time' => 'required',

            'purchase_price' => 'required|numeric',

            'sale_price' => 'required|numeric',

            'currency_id' => 'required|exists:currencies,id',

            'status' => 'required'

        ]);


        $oldBus = Bus::find($trip->bus_id);


        if($trip->bus_id != $request->bus_id){

            $oldBus->update([

                'status' => 'active'

            ]);

            $newBus = Bus::find($request->bus_id);

            $newBus->update([

                'status' => 'inactive'

            ]);

        }


        $trip->update([

            'bus_id' => $request->bus_id,

            'from_city' => $request->from_city,

            'to_city' => $request->to_city,

            'trip_date' => $request->trip_date,

            'trip_time' => $request->trip_time,

            'purchase_price' => $request->purchase_price,

            'sale_price' => $request->sale_price,

            'currency_id' => $request->currency_id,

            'notes' => $request->notes,

            'status' => $request->status

        ]);


        if($request->status == 'completed'){

            $bus = Bus::find($request->bus_id);

            $bus->update([

                'status' => 'active'

            ]);

        }


        return redirect()
            ->route('trips.index')
            ->with('success','تم تحديث الرحلة');

    }




    /*
   |--------------------------------------------------------------------------
   | حذف الرحلة
   |--------------------------------------------------------------------------
   */

    public function destroy($id)
    {

        $trip = Trip::findOrFail($id);

        $bus = Bus::find($trip->bus_id);


        if($bus){

            $bus->update([

                'status' => 'available'

            ]);

        }


        $trip->delete();


        return redirect()
            ->route('trips.index')
            ->with('success','تم حذف الرحلة');

    }

}
