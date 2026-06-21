<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BusDriver;
use App\Models\Driver;
use Illuminate\Http\Request;

use App\Models\Trip;
use App\Models\Bus;
use App\Models\Currency;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;


class TripController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | عرض الرحلات
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        $branchId = auth()->user()->employee->branch_id;


        $search = $request->search;

        $trips = Trip::with(['bus','currency','bookings'])
            ->where('branch_id', $branchId)

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

        $drivers = Driver::where('branch_id', $branchId)
            ->where('type', 'regular')
            ->where('status', 'active')
            ->get();

        $buses = Bus::where('status', 'active')
            ->whereNotNull('agent_id') // تم عكس الشرط هنا ليجلب الباصات المرتبطة بوكيل فقط
            ->get();

        $currencies = Currency::all();

        return view('frontend.trips.index',compact(
            'trips',
            'buses',
            'currencies',
            'search',
            'drivers'
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
            'bus_id'         => 'required|exists:buses,id',
            'from_city'      => 'required|string|max:255',
            'to_city'        => 'required|string|max:255',
            'driver_id'      => 'required|exists:drivers,id',
            'trip_date'      => 'required|date',
            'trip_time'      => 'required',
            'purchase_price' => 'required|numeric',
            'sale_price'     => 'required|numeric',
            'currency_id'    => 'required|exists:currencies,id'
        ]);

        $bus = Bus::findOrFail($request->bus_id);

        if ($bus->status != 'active') {
            return back()->with('error', 'هذا الباص غير متاح حاليا');
        }

        $exists = Trip::where('bus_id', $request->bus_id)
            ->where('trip_date', $request->trip_date)
            ->where('trip_time', $request->trip_time)
            ->exists();

        if ($exists) {
            return back()->with('error', 'هذا الباص لديه رحلة بنفس التاريخ والوقت');
        }

        // بدء العملية المالية المركبة بأمان
        DB::beginTransaction();

        try {
            $branchId = auth()->user()->employee->branch_id;

            // 1. إنشاء الرحلة
            Trip::create([
                'branch_id'      => $branchId,
                'bus_id'         => $request->bus_id,
                'from_city'      => $request->from_city,
                'to_city'        => $request->to_city,
                'trip_date'      => $request->trip_date,
                'trip_time'      => $request->trip_time,
                'purchase_price' => $request->purchase_price,
                'sale_price'     => $request->sale_price,
                'currency_id'    => $request->currency_id,
                'notes'          => $request->notes,
                'status'         => 'scheduled',
                'created_by'     => auth()->id()
            ]);

            // 2. تحديث حالة الباص
            $bus->update([
                'status' => 'inactive'
            ]);

            // 3. تعيين السائق للباص
            BusDriver::create([
                'branch_id' => $branchId,
                'bus_id'    => $request->bus_id,
                'driver_id' => $request->driver_id,
                'start_at'  => $request->trip_time,
                'end_at'    => null,
                'active'    => true
            ]);

            // تأكيد حفظ كافة العمليات في قاعدة البيانات بنجاح
            DB::commit();

            return redirect()
                ->route('trips.index')
                ->with('success', 'تم إنشاء الرحلة بنجاح');

        } catch (Exception $e) {
            // في حال حدوث أي خطأ، يتم التراجع عن كل ما تم تنفيذه بالأعلى وكأن شيئاً لم يكن
            DB::rollBack();

            // تسجيل تفاصيل الخطأ في ملف الـ log للرجوع إليه لاحقاً
            Log::error('خطأ أثناء إنشاء الرحلة: ' . $e->getMessage());

            // العودة للخلف مع إظهار نص الخطأ الفعلي
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ غير متوقع أثناء الحفظ: ' . $e->getMessage());
        }
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

                'status' => 'active'

            ]);

        }


        $trip->delete();


        return redirect()
            ->route('trips.index')
            ->with('success','تم حذف الرحلة');

    }

}
