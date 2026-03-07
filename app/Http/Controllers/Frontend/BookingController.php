<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Trip;
use App\Models\Currency;

class BookingController extends Controller
{

    /*
    ==========================
    عرض الحجوزات
    ==========================
    */

    public function index(Request $request)
    {

        $search = $request->search;

        $bookings = Booking::with([
            'client',
            'trip.bus',
            'currency'
        ])

            ->when($search, function ($query) use ($search) {

                $query->whereHas('client', function ($q) use ($search) {
                    $q->where('full_name','LIKE',"%{$search}%");
                });

            })

            ->latest()
            ->paginate(12)
            ->withQueryString();



        /*
        ==========================
        احصائيات
        ==========================
        */

        $stats = [

            'total' => Booking::count(),

            'confirmed' => Booking::where('status','confirmed')->count(),

            'pending' => Booking::where('status','pending')->count(),

            'cancelled' => Booking::where('status','cancelled')->count(),

        ];


        return view('frontend.bookings.index', compact(

            'bookings',
            'search',
            'stats'

        ));
    }



    /*
    ==========================
    حفظ حجز
    ==========================
    */

    public function store(Request $request)
    {

        $request->validate([

            'client_id' => 'required',
            'trip_id' => 'required',
            'currency_id' => 'required',

        ]);


        Booking::create([

            'client_id' => $request->client_id,
            'trip_id' => $request->trip_id,
            'currency_id' => $request->currency_id,

            'final_price' => $request->final_price,

            'status' => $request->status ?? 'pending',

        ]);


        return redirect()
            ->route('bookings.index')
            ->with('success','تم اضافة الحجز بنجاح');

    }



    /*
    ==========================
    عرض حجز
    ==========================
    */

    public function show($id)
    {

        $booking = Booking::with([
            'client',
            'trip.bus',
            'currency'
        ])->findOrFail($id);


        return view('frontend.bookings.show',compact('booking'));

    }



    /*
    ==========================
    صفحة التعديل
    ==========================
    */

    public function edit($id)
    {

        $booking = Booking::findOrFail($id);

        $clients = Client::all();

        $trips = Trip::all();

        $currencies = Currency::all();


        return view('frontend.bookings.edit',compact(

            'booking',
            'clients',
            'trips',
            'currencies'

        ));
    }



    /*
    ==========================
    تحديث الحجز
    ==========================
    */

    public function update(Request $request,$id)
    {

        $booking = Booking::findOrFail($id);


        $booking->update([

            'client_id' => $request->client_id,
            'trip_id' => $request->trip_id,
            'currency_id' => $request->currency_id,

            'final_price' => $request->final_price,

            'status' => $request->status

        ]);


        return redirect()
            ->route('bookings.index')
            ->with('success','تم تعديل الحجز');

    }



    /*
    ==========================
    حذف الحجز
    ==========================
    */

    public function destroy($id)
    {

        $booking = Booking::findOrFail($id);

        $booking->delete();


        return redirect()
            ->route('bookings.index')
            ->with('success','تم حذف الحجز');

    }


}
