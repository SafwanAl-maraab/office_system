<?php


use App\Http\Controllers\Frontend\BusController;
use App\Http\Controllers\Frontend\CashboxController;
use App\Http\Controllers\Frontend\CashboxExchangeController;
use App\Http\Controllers\Frontend\ClientVoucherController;
use App\Http\Controllers\Frontend\ExchangeRateController;
use App\Http\Controllers\Frontend\ExpenseController;
use App\Http\Controllers\Frontend\FinancialReportController;
use App\Http\Controllers\Frontend\VoucherSettlementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ClientController;
use App\Http\Controllers\Frontend\SettingsController;
use App\Http\Controllers\Frontend\VisaController;
use App\Http\Controllers\Frontend\TripGroupController;
use App\Http\Controllers\Frontend\BookingController;


use App\Http\Controllers\Auth\AuthenticatedSessionController;

// تحويل الرابط الرئيسي مباشرة إلى صفحة تسجيل الدخول
Route::redirect('/', '/login');

// مسار تسجيل الدخول الخاص بك
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get('/settings', [SettingsController::class, 'index'])
        ->middleware('permission:view.settings')
        ->name('settings.index');

    Route::post('/settings', [SettingsController::class, 'update'])
        ->middleware('permission:update.settings')
        ->name('settings.update');


});

use App\Http\Controllers\Frontend\DashboardController;

Route::middleware(['auth'])->group(function(){


Route::get('/dashboard',[DashboardController::class,'index'])
    ->middleware('permission:view.dashboard')
    ->name('dashboard');


});

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get('/clients', [ClientController::class,'index'])
        ->middleware('permission:view.clients')
        ->name('clients.index');

    Route::post('/clients', [ClientController::class,'store'])
        ->middleware('permission:create.clients')
        ->name('clients.store');

    Route::put('/clients/{id}', [ClientController::class,'update'])
        ->middleware('permission:update.clients')
        ->name('clients.update');

    Route::delete('/clients/{id}', [ClientController::class,'destroy'])
        ->middleware('permission:delete.clients')
        ->name('clients.destroy');

});


use App\Http\Controllers\Frontend\EmployeeController;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get('/employees', [EmployeeController::class,'index'])
        ->middleware('permission:view.employees')
        ->name('employees.index');

    Route::post('/employees', [EmployeeController::class,'store'])
        ->middleware('permission:create.employees')
        ->name('employees.store');

    Route::put('/employees/{id}', [EmployeeController::class,'update'])
        ->middleware('permission:update.employees')
        ->name('employees.update');

    Route::delete('/employees/{id}', [EmployeeController::class,'destroy'])
        ->middleware('permission:delete.employees')
        ->name('employees.destroy');


});

use App\Http\Controllers\Frontend\RequestsController;
use App\Http\Controllers\Frontend\TravelController;
use App\Http\Controllers\Frontend\InvoiceController;
use App\Http\Controllers\Frontend\PaymentController;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Request Types
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'request-types',
        \App\Http\Controllers\Frontend\RequestTypeController::class
    )->middleware([
        'index'   => 'permission:view.request-types',
        'show'    => 'permission:view.request-types',
        'create'  => 'permission:create.request-types',
        'store'   => 'permission:create.request-types',
        'edit'    => 'permission:update.request-types',
        'update'  => 'permission:update.request-types',
        'destroy' => 'permission:delete.request-types',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Travels
    |--------------------------------------------------------------------------
    */

    Route::resource('travels', TravelController::class)
        ->middleware([
            'index'   => 'permission:view.travels',
            'show'    => 'permission:view.travels',
            'create'  => 'permission:create.travels',
            'store'   => 'permission:create.travels',
            'edit'    => 'permission:update.travels',
            'update'  => 'permission:update.travels',
            'destroy' => 'permission:delete.travels',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    Route::post(
        'payments/{invoice}',
        [PaymentController::class, 'store']
    )
        ->middleware('permission:create.payments')
        ->name('payments.store');

    /*
    |--------------------------------------------------------------------------
    | Invoices
    |--------------------------------------------------------------------------
    */

    Route::resource('invoices', InvoiceController::class)
        ->only(['index','show'])
        ->middleware('permission:view.invoices');

    Route::get(
        'invoices/{invoice}/pdf',
        [InvoiceController::class, 'generatePDF']
    )
        ->middleware('permission:pdf.invoices')
        ->name('invoices.pdf');

    Route::post(
        'invoices/{invoice}/refund',
        [InvoiceController::class,'createRefund']
    )
        ->middleware('permission:refund.invoices')
        ->name('invoices.refund');

    /*
    |--------------------------------------------------------------------------
    | Add Invoice Payment
    |--------------------------------------------------------------------------
    */

    Route::post(
        'addInvoice',
        [PaymentController::class, 'store']
    )
        ->middleware('permission:create.payments')
        ->name('addInvoice');

    /*
    |--------------------------------------------------------------------------
    | Payments Resource
    |--------------------------------------------------------------------------
    */

    Route::resource('payments', PaymentController::class)
        ->only(['index','store','destroy']);

    /*
    |--------------------------------------------------------------------------
    | Expenses
    |--------------------------------------------------------------------------
    */

    Route::resource('expenses', ExpenseController::class)
        ->only(['index','store']);

    /*
    |--------------------------------------------------------------------------
    | Requests
    |--------------------------------------------------------------------------
    */

    Route::prefix('requests')
        ->name('requests.')
        ->group(function () {

            Route::get(
                'travels/{travel}',
                [TravelController::class, 'show']
            )
                ->middleware('permission:view.travels')
                ->name('travels.show');

            Route::get(
                '/',
                [RequestsController::class, 'index']
            )
                ->middleware('permission:view.requests')
                ->name('index');

            Route::post(
                '/',
                [RequestsController::class, 'store']
            )
                ->middleware('permission:create.requests')
                ->name('store');

            Route::put(
                '/{request}',
                [RequestsController::class, 'update']
            )
                ->middleware('permission:update.requests')
                ->name('update');

            Route::delete(
                '/{request}',
                [RequestsController::class, 'destroy']
            )
                ->middleware('permission:delete.requests')
                ->name('destroy');

            Route::get(
                '/{request}',
                [RequestsController::class, 'show']
            )
                ->middleware('permission:view.requests')
                ->name('show');

            Route::post(
                '/{request}/change-status',
                [RequestsController::class, 'changeStatus']
            )
                ->middleware('permission:change-status.requests')
                ->name('changeStatus');

            Route::post(
                '/{request}/attach-travel',
                [RequestsController::class, 'attachTravel']
            )
                ->middleware('permission:attach-travel.requests')
                ->name('attachTravel');

            Route::delete(
                '/{request}/detach-travel',
                [RequestsController::class, 'detachTravel']
            )
                ->middleware('permission:attach-travel.requests')
                ->name('detachTravel');

        });

});


Route::middleware(['auth'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {


    Route::get('/bookings', [BookingController::class,'index'])
        ->middleware('permission:view.bookings')
        ->name('bookings.index');

    Route::post('/bookings', [BookingController::class,'store'])
        ->middleware('permission:create.bookings')
        ->name('bookings.store');

    Route::put('/bookings/{booking}', [BookingController::class,'update'])
        ->middleware('permission:update.bookings')
        ->name('bookings.update');

    Route::delete('/bookings/{booking}', [BookingController::class,'destroy'])
        ->middleware('permission:delete.bookings')
        ->name('bookings.destroy');

    Route::get('/bookings/{booking}/invoice', [BookingController::class,'show'])
        ->middleware('permission:view.bookings')
        ->name('bookings.show');

    Route::patch(
        '/bookings/{booking}/status',
        [BookingController::class,'changeStatus']
    )
        ->middleware('permission:change-status.bookings')
        ->name('bookings.changeStatus');

});



    /*
    ==========================
    تسجيل دفعة
    ==========================
    */

    Route::post(
        '/bookings/{booking}/payment',
        [BookingController::class,'payment']
    )
        ->middleware('permission:payment.bookings')
        ->name('bookings.payment');


    Route::get(
        '/trips/{trip}/seats',
        [BookingController::class,'tripSeats']
    )
        ->middleware('permission:view.trips')
        ->name('trips.seats');

    /*
    |--------------------------------
    | إدارة الخزنة
    |--------------------------------
    */
    Route::prefix('cashboxes')
        ->name('cashboxes.')
        ->group(function () {

            Route::get(
                '/',
                [\App\Http\Controllers\Frontend\CashboxController::class, 'index']
            )
                ->middleware('permission:view.cashboxes')
                ->name('index');

            Route::post(
                '/store',
                [\App\Http\Controllers\Frontend\CashboxController::class, 'store']
            )
                ->middleware('permission:create.cashboxes')
                ->name('store');

            Route::put(
                '/update/{id}',
                [\App\Http\Controllers\Frontend\CashboxController::class, 'update']
            )
                ->middleware('permission:update.cashboxes')
                ->name('update');

        });



use App\Http\Controllers\Frontend\TripController;

Route::middleware(['auth'])
    ->prefix('frontend')
    ->group(function () {


    /*
    |--------------------------------------------------------------------------
    | TRIPS
    |--------------------------------------------------------------------------
    */

    Route::get('/trips', [TripController::class, 'index'])
        ->middleware('permission:view.trips')
        ->name('trips.index');

    Route::post('/trips', [TripController::class, 'store'])
        ->middleware('permission:create.trips')
        ->name('trips.store');

    Route::put('/trips/{trip}', [TripController::class, 'update'])
        ->middleware('permission:update.trips')
        ->name('trips.update');

    Route::delete('/trips/{trip}', [TripController::class, 'destroy'])
        ->middleware('permission:delete.trips')
        ->name('trips.destroy');

    Route::get('/trips/{trip}/info', [TripController::class, 'info'])
        ->middleware('permission:view.trips')
        ->name('trips.info');

});


Route::get(
    '/dashboard/bookings/{booking}',
    [BookingController::class,'show']
)
    ->middleware([
        'auth',
        'permission:view.bookings'
    ])
    ->name('bookings.show');

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    /*
    ==========================
    عرض الحجوزات
    ==========================
    */

    /*
    ==========================
    إنشاء حجز
    ==========================
    */

});


Route::middleware(['auth'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {


    /*
    |--------------------------------
    | المدفوعات
    |--------------------------------
    */

    Route::prefix('payments')
        ->name('payments.')
        ->group(function () {

            Route::get(
                '/',
                [\App\Http\Controllers\Frontend\PaymentController::class, 'index']
            )
                ->middleware('permission:view.payments')
                ->name('index');

            Route::post(
                '/invoice/add-payment',
                [VisaController::class,'store']
            )
                ->middleware('permission:create.payments')
                ->name('invoice.addPayment');

            Route::get(
                '/{payment}',
                [\App\Http\Controllers\Frontend\PaymentController::class, 'show']
            )
                ->middleware('permission:view.payments')
                ->name('show');

        });

});


use App\Http\Controllers\Frontend\BusAssignmentController;

Route::prefix('dashboard')
    ->middleware(['auth'])
    ->name('dashboard.')
    ->group(function(){


    Route::get(
        '/bus-assignments',
        [BusAssignmentController::class,'index']
    )
        ->middleware('permission:view.bus-assignments')
        ->name('bus_assignments.index');

    Route::post(
        '/bus-assignments',
        [BusAssignmentController::class,'store']
    )
        ->middleware('permission:create.bus-assignments')
        ->name('bus_assignments.store');

    Route::put(
        '/bus-assignments/{id}',
        [BusAssignmentController::class,'update']
    )
        ->middleware('permission:update.bus-assignments')
        ->name('bus_assignments.update');

    Route::delete(
        '/bus-assignments/{id}',
        [BusAssignmentController::class,'destroy']
    )
        ->middleware('permission:delete.bus-assignments')
        ->name('bus_assignments.destroy');

});

Route::prefix('dashboard')
    ->middleware(['auth'])
    ->group(function(){


    Route::get(
        '/drivers',
        [\App\Http\Controllers\Frontend\DriverController::class,'index']
    )
        ->middleware('permission:view.drivers')
        ->name('drivers.index');

    Route::post(
        '/drivers',
        [\App\Http\Controllers\Frontend\DriverController::class,'store']
    )
        ->middleware('permission:create.drivers')
        ->name('drivers.store');

    Route::put(
        '/drivers/{id}',
        [\App\Http\Controllers\Frontend\DriverController::class,'update']
    )
        ->middleware('permission:update.drivers')
        ->name('drivers.update');

    Route::delete(
        '/drivers/{id}',
        [\App\Http\Controllers\Frontend\DriverController::class,'destroy']
    )
        ->middleware('permission:delete.drivers')
        ->name('drivers.destroy');

});


Route::prefix('dashboard')
    ->middleware(['auth'])
    ->group(function() {


    Route::get(
        '/buses',
        [BusController::class, 'index']
    )
        ->middleware('permission:view.buses')
        ->name('buses.index');

    Route::post(
        '/buses',
        [BusController::class, 'store']
    )
        ->middleware('permission:create.buses')
        ->name('buses.store');

    Route::put(
        '/buses/{id}',
        [BusController::class, 'update']
    )
        ->middleware('permission:update.buses')
        ->name('buses.update');

    Route::delete(
        '/buses/{id}',
        [BusController::class, 'destroy']
    )
        ->middleware('permission:delete.buses')
        ->name('buses.destroy');

});



Route::middleware(['auth'])
    ->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Client Vouchers
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/client-vouchers',
        [ClientVoucherController::class,'index']
    )
        ->middleware('permission:view.client-vouchers')
        ->name('client-vouchers.index');

    Route::post(
        '/client-vouchers',
        [ClientVoucherController::class,'store']
    )
        ->middleware('permission:create.client-vouchers')
        ->name('client-vouchers.store');

    Route::get(
        '/client-vouchers/client-info/{id}',
        [ClientVoucherController::class,'clientInfo']
    )
        ->middleware('permission:view.client-vouchers')
        ->name('client-vouchers.client-info');

    Route::get(
        '/clients/search',
        [ClientController::class,'search']
    )
        ->middleware('permission:view.clients');

    Route::get(
        '/clients/{client}/statement',
        [ClientController::class,'statement']
    )
        ->middleware('permission:statement.clients')
        ->name('clients.statement');

    /*
    |--------------------------------------------------------------------------
    | Exchange Rates
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/exchange-rates',
        [ExchangeRateController::class,'index']
    )
        ->middleware('permission:view.exchange-rates')
        ->name('exchange-rates.index');

    Route::post(
        '/exchange-rates',
        [ExchangeRateController::class,'store']
    )
        ->middleware('permission:create.exchange-rates')
        ->name('exchange-rates.store');

    Route::put(
        '/exchange-rates/{id}',
        [ExchangeRateController::class,'update']
    )
        ->middleware('permission:update.exchange-rates')
        ->name('exchange-rates.update');

    Route::get(
        '/exchange-rates/find',
        [ExchangeRateController::class,'findRate']
    )
        ->middleware('permission:view.exchange-rates')
        ->name('exchange-rates.find');

    /*
    |--------------------------------------------------------------------------
    | Voucher Settlements
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/voucher-settlements/settle',
        [VoucherSettlementController::class,'settle']
    )
        ->middleware('permission:create.voucher-settlements')
        ->name('voucher-settlements.settle');

    Route::get(
        '/client-vouchers/{voucher}',
        [ClientVoucherController::class,'show']
    )
        ->middleware('permission:view.client-vouchers')
        ->name('client-vouchers.show');

});


Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get(
        '/voucher-settlements',
        [VoucherSettlementController::class,'index']
    )
        ->middleware('permission:view.voucher-settlements')
        ->name('voucher-settlements.index');

    Route::get(
        '/voucher-settlements/client/{id}',
        [VoucherSettlementController::class,'clientData']
    )
        ->middleware('permission:view.voucher-settlements')
        ->name('voucher-settlements.client');

});


Route::middleware(['auth'])
    ->group(function () {


    Route::post(
        '/invoices/{invoice}/cancel-operation',
        [InvoiceController::class,'cancelOperation']
    )
        ->middleware('permission:cancel.invoices')
        ->name('invoices.cancel-operation');

});


Route::middleware(['auth'])
    ->group(function () {


    Route::get(
        '/cashboxes/{currency}/transactions',
        [CashboxController::class,'transactions']
    )
        ->middleware('permission:transactions.cashboxes')
        ->name('cashboxes.transactions');

});


Route::middleware(['auth'])
    ->prefix('cashbox-exchanges')
    ->name('cashbox-exchanges.')
    ->group(function () {


    Route::get(
        '/',
        [CashboxExchangeController::class,'index']
    )
        ->middleware('permission:view.cashbox-exchanges')
        ->name('index');

    Route::post(
        '/',
        [CashboxExchangeController::class,'store']
    )
        ->middleware('permission:create.cashbox-exchanges')
        ->name('store');

    Route::get(
        '/get-balances',
        [CashboxExchangeController::class,'getBalances']
    )
        ->middleware('permission:view.cashbox-exchanges')
        ->name('get-balances');

    Route::get(
        '/get-rate',
        [CashboxExchangeController::class,'getRate']
    )
        ->middleware('permission:view.cashbox-exchanges')
        ->name('get-rate');

    Route::post(
        '/{exchange}/reverse',
        [CashboxExchangeController::class,'reverse']
    )
        ->middleware('permission:reverse.cashbox-exchanges')
        ->name('reverse');

});




use App\Http\Controllers\Frontend\RoleController;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/roles',
        [RoleController::class,'index']
    )
        ->middleware('permission:view.users')
        ->name('roles.index');

    Route::post(
        '/roles',
        [RoleController::class,'store']
    )
        ->middleware('permission:create.users')
        ->name('roles.store');

    Route::put(
        '/roles/{role}',
        [RoleController::class,'update']
    )
        ->middleware('permission:update.users')
        ->name('roles.update');

    Route::delete(
        '/roles/{role}',
        [RoleController::class,'destroy']
    )
        ->middleware('permission:delete.users')
        ->name('roles.destroy');

});






Route::prefix('dashboard')
    ->middleware(['auth'])
    ->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Incomes
    |--------------------------------------------------------------------------
    */
        Route::get(
            '/incomes',
            [\App\Http\Controllers\Frontend\IncomeController::class,'index']
        )
            ->middleware('permission:view.incomes')
            ->name('incomes.index');

        Route::post(
            '/incomes',
            [\App\Http\Controllers\Frontend\IncomeController::class,'store']
        )
            ->middleware('permission:create.incomes')
            ->name('incomes.store');
    /*
    |--------------------------------------------------------------------------
    | Financial Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/financial',
        [FinancialReportController::class,'index']
    )
        ->middleware('permission:view.financial-reports')
        ->name('reports.financial');

    Route::get(
        '/reports/financial/pdf',
        [FinancialReportController::class,'exportPdf']
    )
        ->middleware('permission:export.financial-reports')
        ->name('financial-report.pdf');

    Route::get(
        '/reports/profit-analysis',
        [FinancialReportController::class,'profitAnalysis']
    )
        ->middleware('permission:view.profit-analysis')
        ->name('reports.profit-analysis');

    Route::get(
        '/reports/profit-analysis/pdf',
        [FinancialReportController::class,'profitAnalysisPdf']
    )
        ->middleware('permission:export.profit-analysis')
        ->name('reports.profit-analysis.pdf');

});


// Profile

Route::middleware('auth')->group(function () {


Route::get('/profile', [ProfileController::class, 'edit'])
    ->middleware('permission:view.profile')
    ->name('profile.edit');

Route::patch('/profile', [ProfileController::class, 'update'])
    ->middleware('permission:update.profile')
    ->name('profile.update');

Route::delete('/profile', [ProfileController::class, 'destroy'])
    ->middleware('permission:delete.profile')
    ->name('profile.destroy');


});

require __DIR__.'/auth.php';

// Users

use App\Http\Controllers\Frontend\UserController;

Route::middleware(['auth'])
    ->group(function () {


    Route::resource('users', UserController::class)
        ->middleware([
            'index'   => 'permission:view.users',
            'show'    => 'permission:view.users',
            'create'  => 'permission:create.users',
            'store'   => 'permission:create.users',
            'edit'    => 'permission:update.users',
            'update'  => 'permission:update.users',
            'destroy' => 'permission:delete.users',
        ]);

});


// Visas

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get(
        '/visas/search-clients',
        [VisaController::class,'searchClients']
    )
        ->middleware('permission:view.visas')
        ->name('visas.searchClients');

});


Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get('/visas', [VisaController::class,'index'])
        ->middleware('permission:view.visas')
        ->name('visas.index');

    Route::post('/visas', [VisaController::class,'store'])
        ->middleware('permission:create.visas')
        ->name('visas.store');

    Route::put('/visas/{id}', [VisaController::class,'update'])
        ->middleware('permission:update.visas')
        ->name('visas.update');

    Route::delete('/visas/{id}', [VisaController::class,'destroy'])
        ->middleware('permission:delete.visas')
        ->name('visas.destroy');

    Route::get('/visas/{id}', [VisaController::class,'show'])
        ->middleware('permission:view.visas')
        ->name('visas.show');

    Route::post('/visas/{id}/change-status', [VisaController::class,'changeStatus'])
        ->middleware('permission:change-status.visas')
        ->name('visas.changeStatus');

    Route::post('/visas/{id}/attach-trip-group', [VisaController::class,'attachTripGroup'])
        ->middleware('permission:attach-trip-group.visas')
        ->name('visas.attachTripGroup');

    Route::post('/visas/{id}/attach-package', [VisaController::class,'attachPackage'])
        ->middleware('permission:attach-package.visas')
        ->name('visas.attachPackage');

    Route::post('/visas/{id}/add-payment', [VisaController::class,'storePayment'])
        ->middleware('permission:payment.visas')
        ->name('visas.addPayment');

    Route::get('/trip-groups/search', [VisaController::class,'searchTripGroups'])
        ->middleware('permission:view.trip-groups')
        ->name('trip-groups.search');

    Route::get('/trip-groups/{id}/seats', [VisaController::class,'getAvailableSeats'])
        ->middleware('permission:view.trip-groups')
        ->name('trip-groups.seats');

});


// Visa Types

use App\Http\Controllers\Frontend\VisaTypeController;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get('/visa-types', [VisaTypeController::class,'index'])
        ->middleware('permission:view.visa-types')
        ->name('visa-types.index');

    Route::post('/visa-types', [VisaTypeController::class,'store'])
        ->middleware('permission:create.visa-types')
        ->name('visa-types.store');

    Route::put('/visa-types/{id}', [VisaTypeController::class,'update'])
        ->middleware('permission:update.visa-types')
        ->name('visa-types.update');

    Route::delete('/visa-types/{id}', [VisaTypeController::class,'destroy'])
        ->middleware('permission:delete.visa-types')
        ->name('visa-types.destroy');

    Route::get('/clients/search',[ClientController::class,'search'])
        ->middleware('permission:view.clients');

});


// Trip Groups

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get('/trip-groups', [TripGroupController::class,'index'])
        ->middleware('permission:view.trip-groups')
        ->name('trip-groups.index');

    Route::post('/trip-groups', [TripGroupController::class,'store'])
        ->middleware('permission:create.trip-groups')
        ->name('trip-groups.store');

    Route::post('/trip-groups/attach-bus', [TripGroupController::class,'attachBus'])
        ->middleware('permission:attach-bus.trip-groups')
        ->name('trip-groups.attachBus');

});


// Agents

use App\Http\Controllers\Frontend\AgentController;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {


    Route::get('/agents', [AgentController::class,'index'])
        ->middleware('permission:view.agents')
        ->name('agents.index');

    Route::post('/agents', [AgentController::class,'store'])
        ->middleware('permission:create.agents')
        ->name('agents.store');

    Route::get('/agents/{id}', [AgentController::class,'show'])
        ->middleware('permission:view.agents')
        ->name('agents.show');

    Route::put('/agents/{id}', [AgentController::class,'update'])
        ->middleware('permission:update.agents')
        ->name('agents.update');

    Route::delete('/agents/{id}', [AgentController::class,'destroy'])
        ->middleware('permission:delete.agents')
        ->name('agents.destroy');

    Route::post('/agents/{id}/payment', [AgentController::class,'storePayment'])
        ->middleware('permission:payment.agents')
        ->name('agents.pay');

    Route::get('/agents/{id}/statement-pdf', [AgentController::class,'statementPDF'])
        ->middleware('permission:statement.agents')
        ->name('agents.statement.pdf');

    Route::get('/agents-statement-all', [AgentController::class,'statementAll'])
        ->middleware('permission:statement.agents')
        ->name('agents.statement.all');

    Route::get('/agents-export', [AgentController::class,'statementAll'])
        ->middleware('permission:export.agents')
        ->name('agents.export');

});

