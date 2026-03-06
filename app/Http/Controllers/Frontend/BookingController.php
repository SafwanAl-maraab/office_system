<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Client;
use App\Models\TripGroup;
use App\Models\TripGroupBus;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\BranchCashbox;
use Illuminate\Support\Facades\DB;



class BookingController extends Controller
{
public function index(Request $request)
{

$query = Booking::with([
'client',
'tripGroup',
'bus'
]);

if($request->search){

$query->where(function($q) use ($request){

$q->where('booking_number','like','%'.$request->search.'%')

->orWhereHas('client',function($c) use ($request){

$c->where('full_name','like','%'.$request->search.'%');

});

});

}

if($request->status){

$query->where('status',$request->status);

}

if($request->trip_group_id){

$query->where('trip_group_id',$request->trip_group_id);

}

$bookings = $query->latest()->paginate(20);

$clients = Client::all();
$tripGroups = TripGroup::all();
$buses = TripGroupBus::all();
$currencies = Currency::all();

$stats = [

'total'=>Booking::count(),

'pending'=>Booking::where('status','pending')->count(),

'confirmed'=>Booking::where('status','confirmed')->count(),

'cancelled'=>Booking::where('status','cancelled')->count(),

'revenue'=>Invoice::where('reference_type','booking')->sum('paid_amount')

];

return view('frontend.bookings.index',compact(

'bookings',
'clients',
'tripGroups',
'buses',
'currencies',
'stats'

));

}
    public function store(Request $request)
    {

        $request->validate([

            'client_id'=>'required',

            'price'=>'required|numeric',

            'currency_id'=>'required'

        ]);

        DB::beginTransaction();

        try{

            if($request->seat_number && $request->trip_group_bus_id){

                $seatExists = Booking::where('trip_group_bus_id',$request->trip_group_bus_id)

                ->where('seat_number',$request->seat_number)

                ->exists();

                if($seatExists){

                    return back()->withErrors([

                        'seat_number'=>'هذا المقعد محجوز'

                    ]);

                }

            }

            $bookingNumber = 'BOOK-'.date('Y').'-'.str_pad(

                Booking::count()+1,

                5,

                '0',

                STR_PAD_LEFT

            );

            $booking = Booking::create([

                'branch_id'=>auth()->user()->employee->branch_id,

                'client_id'=>$request->client_id,

                'trip_group_id'=>$request->trip_group_id,

                'trip_group_bus_id'=>$request->trip_group_bus_id,

                'seat_number'=>$request->seat_number,

                'booking_number'=>$bookingNumber,

                'price'=>$request->price,

                'currency_id'=>$request->currency_id,

                'notes'=>$request->notes,

                'created_by'=>auth()->user()->employee->id

            ]);

            Invoice::create([

                'branch_id'=>$booking->branch_id,

                'client_id'=>$booking->client_id,

                'reference_type'=>'booking',

                'reference_id'=>$booking->id,

                'total_amount'=>$booking->price,

                'paid_amount'=>0,

                'remaining_amount'=>$booking->price,

                'currency_id'=>$booking->currency_id,

                'status'=>'unpaid'

            ]);

            DB::commit();

            return back()->with('success','تم إنشاء الحجز');

        }

        catch(\Exception $e){

            DB::rollback();

            return back()->withErrors($e->getMessage());

        }

    }



    public function destroy($id)
    {

        $booking = Booking::findOrFail($id);

        $booking->delete();

        return back()->with('success','تم حذف الحجز');

    }
    public function show($id)
{

    $booking = Booking::with([

        'client',
        'tripGroup',
        'bus',
        'invoice.payments'

    ])->findOrFail($id);

    return view('frontend.bookings.show',compact('booking'));

}


public function addPayment(Request $request, Booking $booking)
{

    $request->validate([
        'amount'=>'required|numeric|min:1'
    ]);

    $invoice = $booking->invoice;

    if($request->amount > $invoice->remaining_amount){

        return back()->withErrors([
            'amount'=>'المبلغ أكبر من المتبقي'
        ]);

    }

    DB::beginTransaction();

    try{

        Payment::create([

            'branch_id'=>$booking->branch_id,

            'client_id'=>$booking->client_id,

            'invoice_id'=>$invoice->id,

            'amount'=>$request->amount,

            'currency_id'=>$invoice->currency_id,

            'payment_method'=>'cash'

        ]);


        $invoice->paid_amount += $request->amount;

        $invoice->remaining_amount -= $request->amount;


        if($invoice->remaining_amount == 0){

            $invoice->status = 'paid';

        }

        else{

            $invoice->status = 'partial';

        }

        $invoice->save();


        $cashbox = BranchCashbox::where('branch_id',$booking->branch_id)

        ->where('currency_id',$invoice->currency_id)

        ->first();


        $cashbox->balance += $request->amount;

        $cashbox->save();


        DB::commit();

        return back()->with('success','تم تسجيل الدفع');

    }

    catch(\Exception $e){

        DB::rollback();

        return back()->withErrors($e->getMessage());

    }

}

public function dashboard()
{

$totalBookings = Booking::count();

$todayBookings = Booking::whereDate('created_at',today())->count();

$totalRevenue = Invoice::where('reference_type','booking')
->sum('paid_amount');

$remainingRevenue = Invoice::where('reference_type','booking')
->sum('remaining_amount');

$latestBookings = Booking::with('client')
->latest()
->take(5)
->get();

return view('frontend.bookings.dashboard',compact(

'totalBookings',
'todayBookings',
'totalRevenue',
'remainingRevenue',
'latestBookings'

));

}
public function create()
{

$clients = \App\Models\Client::all();

$tripGroups = \App\Models\TripGroup::all();

$currencies = \App\Models\Currency::all();

return view('frontend.bookings.partials.create_modal',compact(

'clients',
'tripGroups',
'currencies'

));

}


}