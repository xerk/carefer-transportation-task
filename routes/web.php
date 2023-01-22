<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\GovernorateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', function () {
        return view('dashboard');
    })
    ->name('dashboard');


/**
 * Client Routes
 */
// Route::prefix('/')
//     ->middleware(['auth:sanctum', 'verified'])
//     ->group(function () {
//         Route::resource('users', UserController::class);
//         Route::resource('buses', BusController::class);
//         Route::resource('discounts', DiscountController::class);
//         Route::resource('governorates', GovernorateController::class);
//         Route::resource('orders', OrderController::class);
//         Route::resource('seats', SeatController::class);
//         Route::resource('cities', CityController::class);
//         Route::resource('stations', StationController::class);
//         Route::resource('trips', TripController::class);
//     });
