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

        $bookings = Booking::with([
            'client',
            'trip'
        ])
            ->where('branch_id', $branchId)

            ->when($search, function ($query) use ($search) {

                $query->whereHas('client', function ($q) use ($search) {

                    $q->where('full_name', 'LIKE', "%{$search}%");

                });

            })

            ->latest()

            ->paginate(12)

            ->withQueryString();

        return view(
            'frontend.bookings.index',
            compact('bookings', 'search')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | إنشاء حجز
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $branchId = auth()->user()->employee->branch_id;

        $request->validate([

            'client_id' => 'required|exists:clients,id',

            'trip_id' => 'required|exists:trips,id',

            'sale_price' => 'required|numeric',

            'discount_percent' => 'nullable|numeric',

            'paid_amount' => 'nullable|numeric'

        ]);


        DB::beginTransaction();

        try {

            $trip = Trip::findOrFail($request->trip_id);


            /*
            |--------------------------------------------------------------------------
            | الحسابات المالية
            |--------------------------------------------------------------------------
            */

            $purchase = $trip->purchase_price;

            $sale = $request->sale_price;

            $discountPercent = $request->discount_percent ?? 0;

            $totalBeforeDiscount = $purchase + $sale;

            $discountAmount = ($totalBeforeDiscount * $discountPercent) / 100;

            $finalPrice = $totalBeforeDiscount - $discountAmount;

            $paid = $request->paid_amount ?? 0;

            $remaining = $finalPrice - $paid;



            /*
            |--------------------------------------------------------------------------
            | إنشاء الحجز
            |--------------------------------------------------------------------------
            */

            $booking = Booking::create([

                'branch_id' => $branchId,

                'client_id' => $request->client_id,

                'trip_id' => $request->trip_id,

                'purchase_price' => $purchase,

                'sale_price' => $sale,

                'discount_percent' => $discountPercent,

                'discount_amount' => $discountAmount,

                'total_before_discount' => $totalBeforeDiscount,

                'final_price' => $finalPrice,

                'currency_id' => $trip->currency_id,

                'status' => 'confirmed',

                'created_by' => auth()->id()

            ]);



            /*
            |--------------------------------------------------------------------------
            | إنشاء الفاتورة
            |--------------------------------------------------------------------------
            */

            $invoice = Invoice::create([

                'branch_id' => $branchId,

                'client_id' => $request->client_id,

                'reference_type' => 'booking',

                'reference_id' => $booking->id,

                'total_amount' => $finalPrice,

                'paid_amount' => $paid,

                'remaining_amount' => $remaining,

                'currency_id' => $trip->currency_id,

                'cost' => $purchase,

                'status' => $paid == 0 ? 'unpaid' : ($remaining > 0 ? 'partial' : 'paid')

            ]);



            /*
            |--------------------------------------------------------------------------
            | إنشاء الدفع إذا كان هناك مبلغ مدفوع
            |--------------------------------------------------------------------------
            */

            if ($paid > 0) {

                Payment::create([

                    'branch_id' => $branchId,

                    'client_id' => $request->client_id,

                    'invoice_id' => $invoice->id,

                    'amount' => $paid,

                    'currency_id' => $trip->currency_id,

                    'payment_method' => 'cash',

                    'created_by' => auth()->id()

                ]);

            }



            DB::commit();

            return redirect()
                ->route('bookings.index')
                ->with('success', 'تم إنشاء الحجز والفاتورة بنجاح');

        }

        catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'حدث خطأ أثناء إنشاء الحجز'
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | البحث عن العميل بالحرف
    |--------------------------------------------------------------------------
    */

    public function searchClient(Request $request)
    {

        $q = $request->q;

        $clients = Client::where('full_name', 'LIKE', "%{$q}%")

            ->limit(10)

            ->get([
                'id',
                'full_name',
                'phone',
                'passport_number',
                'national_id'
            ]);

        return response()->json($clients);

    }



    /*
    |--------------------------------------------------------------------------
    | جلب بيانات الرحلة
    |--------------------------------------------------------------------------
    */

    public function getTrip($id)
    {

        $trip = Trip::with('bus', 'currency')

            ->findOrFail($id);

        return response()->json([

            'purchase_price' => $trip->purchase_price,

            'sale_price' => $trip->sale_price,

            'currency_id' => $trip->currency_id,

            'currency' => $trip->currency->symbol ?? '',

            'bus' => $trip->bus->plate_number ?? '',

            'from_city' => $trip->from_city,

            'to_city' => $trip->to_city

        ]);

    }



    /*
    |--------------------------------------------------------------------------
    | عرض الحجز
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

            ->where('branch_id', $branchId)

            ->findOrFail($id);


        return view(

            'frontend.bookings.show',

            compact('booking')

        );

    }

}
