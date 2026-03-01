<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ClientController;
use App\Http\Controllers\Frontend\SettingsController;


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

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {



        Route::resource('request-types',
            \App\Http\Controllers\Frontend\RequestTypeController::class);


        Route::prefix('requests')
            ->name('requests.')
            ->group(function () {

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
