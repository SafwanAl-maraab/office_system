<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Trip;
use App\Models\Invoice;
use App\Models\Payment;



use App\Models\BranchCashbox;



class BookingController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | عرض صفحة الحجوزات
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $search = $request->search;
        $status = $request->status;

        $query = Booking::with([
            'client',
            'trip.bus',
            'currency'
        ])->where('branch_id', $branchId);

        // البحث باسم العميل
        if ($search) {
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%");
            });
        }

        // فلترة الحالة
        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(12);

        $clients = Client::where('branch_id',$branchId)->latest()->paginate(12);
        $trips =    Trip::where('branch_id',$branchId)->latest()->paginate(12);
        // احصائيات
        $stats = [
            'total' => Booking::where('branch_id',$branchId)->count(),
            'confirmed' => Booking::where('branch_id',$branchId)->where('status','confirmed')->count(),
            'pending' => Booking::where('branch_id',$branchId)->where('status','pending')->count(),
            'cancelled' => Booking::where('branch_id',$branchId)->where('status','cancelled')->count(),
            'sales' => Invoice::where('branch_id',$branchId)->sum('total_amount'),
            'paid' => Payment::where('branch_id',$branchId)->sum('amount'),
        ];

        return view('frontend.bookings.index', compact(
            'bookings',
            'search',
            'stats',
            'clients',
            'trips'


        ));
    }

    /*
    |--------------------------------------------------------------------------
    | إنشاء الحجز
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $branchId = auth()->user()->employee->branch_id;

            /*
            ==========================
            الرحلة
            ==========================
            */

            $trip = Trip::findOrFail($request->trip_id);


            /*
            ==========================
            السعر
            ==========================
            */

            $salePrice = $trip->sale_price;

            $discountPercent = $request->discount_percent ?? 0;

            $discountAmount = ($salePrice * $discountPercent) / 100;

            $finalPrice = $salePrice - $discountAmount;


            /*
            ==========================
            إنشاء الحجز
            ==========================
            */

            $booking = Booking::create([

                'branch_id' => $branchId,

                'client_id' => $request->client_id,

                'trip_id' => $trip->id,

                'seat_number' => $request->seat_number,

                'purchase_price' => $trip->purchase_price,

                'sale_price' => $salePrice,
                'currency_id' => $trip->currency_id,
                'discount_percent' => $discountPercent,


                'created_by' => auth()->user()->employee->id,

            ]);


            /*
            ==========================
            إنشاء الفاتورة
            ==========================
            */

            $invoice = Invoice::create([

                'branch_id' => $branchId,

                'client_id' => $request->client_id,

                'reference_type' => 'booking',

                'reference_id' => $booking->id,

                'total_amount' => $finalPrice,

                'paid_amount' => 0,

                'remaining_amount' => $finalPrice,

                'cost' => $trip->purchase_price,

                'currency_id' => $trip->currency_id,

                'status' => 'pending',

                'is_refund' => 0,

            ]);


            /*
            ==========================
            تسجيل دفعة
            ==========================
            */

            $paymentAmount = $request->payment_amount ?? 0;

            if ($paymentAmount > 0) {

                Payment::create([

                    'branch_id' => $branchId,

                    'client_id' => $request->client_id,

                    'invoice_id' => $invoice->id,

                    'created_by' => auth()->user()->employee->id,

                    'amount' => $paymentAmount,

                    'currency_id' => $trip->currency_id,

                    'payment_method' => 'cash',

                ]);


                /*
                ==========================
                تحديث الفاتورة
                ==========================
                */

                $invoice->update([

                    'paid_amount' => $paymentAmount,

                    'remaining_amount' => $finalPrice - $paymentAmount,

                    'status' => $paymentAmount >= $finalPrice ? 'paid' : 'partial'

                ]);


                /*
                ==========================
                تحديث الخزنة
                ==========================
                */

                $cashbox = BranchCashbox::where('branch_id',$branchId)
                    ->where('currency_id',$trip->currency_id)
                    ->first();


                if ($cashbox) {

                    $cashbox->increment('balance',$paymentAmount);

                }

            }


            DB::commit();


            return redirect()
                ->route('dashboard.bookings.index')
                ->with('success','تم إنشاء الحجز بنجاح');


        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error','حدث خطأ أثناء إنشاء الحجز: '.$e->getMessage());

        }

    }
    public function tripSeats($tripId)
    {
        $trip = Trip::with('bus')->findOrFail($tripId);

        $bookedSeats = Booking::where('trip_id',$tripId)
            ->pluck('seat_number');

        return response()->json([

            'totalSeats' => $trip->bus->seats,

            'bookedSeats' => $bookedSeats

        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | عرض تفاصيل الحجز
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {

        $branchId = auth()->user()->employee->branch_id;

        $booking = Booking::with([
            'client',
            'trip.bus',
            'currency',
            'invoice.payments'
        ])
            ->where('branch_id',$branchId)
            ->findOrFail($id);


        return view('frontend.bookings.show',compact('booking'));

    }



    /*
    |--------------------------------------------------------------------------
    | حذف الحجز
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {

        $booking = Booking::findOrFail($id);

        $booking->delete();

        return back()->with('success','تم حذف الحجز');

    }



}
