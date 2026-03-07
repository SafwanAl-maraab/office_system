<?php

use App\Http\Controllers\Frontend\ExpenseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ClientController;
use App\Http\Controllers\Frontend\SettingsController;
use App\Http\Controllers\Frontend\VisaController;
use App\Http\Controllers\Frontend\TripGroupController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','role:admin'])->prefix('dashboard')->group(function () {

    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');

    Route::post('/settings', [SettingsController::class, 'update'])
        ->name('settings.update');

});


Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/clients', [ClientController::class,'index'])->name('clients.index');
        Route::post('/clients', [ClientController::class,'store'])->name('clients.store');
        Route::put('/clients/{id}', [ClientController::class,'update'])->name('clients.update');
        Route::delete('/clients/{id}', [ClientController::class,'destroy'])->name('clients.destroy');

    });


use App\Http\Controllers\Frontend\EmployeeController;

Route::middleware(['auth'])->prefix('dashboard')->group(function () {

    Route::get('/employees', [EmployeeController::class,'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class,'store'])->name('employees.store');
    Route::put('/employees/{id}', [EmployeeController::class,'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class,'destroy'])->name('employees.destroy');

});


//الجوازات


use App\Http\Controllers\Frontend\RequestsController;
use App\Http\Controllers\Frontend\TravelController;

use App\Http\Controllers\Frontend\InvoiceController;
use App\Http\Controllers\Frontend\PaymentController;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {


//انواع الطلبات
        Route::resource('request-types',
            \App\Http\Controllers\Frontend\RequestTypeController::class);
//الرحلات

        Route::resource('travels', TravelController::class);

        //payment invois in request

        Route::post('payments/{invoice}',
            [\App\Http\Controllers\Frontend\PaymentController::class, 'store'])
            ->name('payments.store');
//invoice

        Route::resource('invoices', InvoiceController::class)
            ->only(['index','show']);

        Route::get('invoices/{invoice}/pdf',
            [InvoiceController::class, 'generatePDF'])
            ->name('invoices.pdf');


        Route::post('invoices/{invoice}/refund',
            [InvoiceController::class,'createRefund'])
            ->name('invoices.refund');
        // نهاية الفاتورة

        //المدفوعات



        Route::resource('payments', PaymentController::class)
            ->only(['index','store','destroy']);

//        Route::post('payments/refund',
//            [PaymentController::class,'refund'])
//            ->name('payments.refund');
        //نهاية المدفوعات

        //المصروفات
        Route::resource('expenses', ExpenseController::class)
            ->only(['index','store']);


        Route::prefix('requests')
            ->name('requests.')
            ->group(function () {


                Route::get('travels/{travel}',
                    [TravelController::class, 'show'])
                    ->name('travels.show');


                Route::get('/', [RequestsController::class, 'index'])
                    ->name('index');

                Route::post('/', [RequestsController::class, 'store'])
                    ->name('store');

                Route::put('/{request}', [RequestsController::class, 'update'])
                    ->name('update');



                Route::delete('/{request}', [RequestsController::class, 'destroy'])
                    ->name('destroy');
                Route::get('/{request}', [RequestsController::class, 'show'])
                    ->name('show');

                Route::post('/{request}/change-status',
                    [RequestsController::class, 'changeStatus'])
                    ->name('changeStatus');


                Route::post('/{request}/attach-travel',
                    [RequestsController::class, 'attachTravel'])
                    ->name('attachTravel');

                Route::delete('/{request}/detach-travel',
                    [RequestsController::class, 'detachTravel'])
                    ->name('detachTravel');


            });

    });




Route::middleware(['auth'])
    ->prefix('frontend')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        Route::get('/bookings', [BookingController::class, 'index'])
            ->name('bookings.index');

        Route::post('/bookings', [BookingController::class, 'store'])
            ->name('bookings.store');



        /*
        |--------------------------------------------------------------------------
        | SEARCH CLIENT
        |--------------------------------------------------------------------------
        */

        Route::get('/clients/search', [BookingController::class, 'searchClient'])
            ->name('clients.search');



        /*
        |--------------------------------------------------------------------------
        | GET TRIP INFO (for booking form)
        |--------------------------------------------------------------------------
        */

        Route::get('/trips/{trip}', [BookingController::class, 'getTrip'])
            ->name('trips.info');

    });
//end safwan



//
//Route::get('/dashboard', function () {
//    return view('frontend.dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [App\Http\Controllers\dashcontroller::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//محمد المعرب تاشيرات



    
Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/visas/search-clients',
            [VisaController::class,'searchClients'])
            ->name('visas.searchClients');

});


Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {

        // عرض جميع التأشيرات
        Route::get('/visas', [VisaController::class,'index'])
            ->name('visas.index');

        // إنشاء تأشيرة
        Route::post('/visas', [VisaController::class,'store'])
            ->name('visas.store');

        // تحديث تأشيرة
        Route::put('/visas/{id}', [VisaController::class,'update'])
            ->name('visas.update');

        // حذف (لن نستخدمه فعلياً - فقط تغيير حالة)
        Route::delete('/visas/{id}', [VisaController::class,'destroy'])
            ->name('visas.destroy');

        // عرض تفاصيل تأشيرة
        Route::get('/visas/{id}', [VisaController::class,'show'])
            ->name('visas.show');

        // تغيير الحالة
        Route::post('/visas/{id}/change-status', [VisaController::class,'changeStatus'])
            ->name('visas.changeStatus');

        // ربط بحملة
        Route::post('/visas/{id}/attach-trip-group', [VisaController::class,'attachTripGroup'])
            ->name('visas.attachTripGroup');

        // ربط بباقة
        Route::post('/visas/{id}/attach-package', [VisaController::class,'attachPackage'])
            ->name('visas.attachPackage');

    
    Route::post('/visas/{id}/add-payment', [VisaController::class,'storePayment'])
    ->name('visas.addPayment');

    Route::post('/visas/{id}/change-status', [VisaController::class,'changeStatus'])
    ->name('visas.changeStatus');

//////
Route::post('/visas/{id}/attach-trip-group',
    [VisaController::class,'attachTripGroup'])
    ->name('visas.attachTripGroup');

Route::get('/trip-groups/search',
    [VisaController::class,'searchTripGroups'])
    ->name('trip-groups.search');

Route::get('/trip-groups/{id}/seats',
    [VisaController::class,'getAvailableSeats'])
    ->name('trip-groups.seats');

       
    });


    use App\Http\Controllers\Frontend\VisaTypeController;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/visa-types', [VisaTypeController::class,'index'])
            ->name('visa-types.index');

        Route::post('/visa-types', [VisaTypeController::class,'store'])
            ->name('visa-types.store');

        Route::put('/visa-types/{id}', [VisaTypeController::class,'update'])
            ->name('visa-types.update');

        Route::delete('/visa-types/{id}', [VisaTypeController::class,'destroy'])
            ->name('visa-types.destroy');
Route::get('/clients/search',[ClientController::class,'search']);
Route::post('/visas', [VisaController::class,'store'])->name('visas.store');
    });

  

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/trip-groups', [TripGroupController::class,'index'])
            ->name('trip-groups.index');

        Route::post('/trip-groups', [TripGroupController::class,'store'])
            ->name('trip-groups.store');

        Route::post('/trip-groups/attach-bus', [TripGroupController::class,'attachBus'])
            ->name('trip-groups.attachBus');

         
           
    });