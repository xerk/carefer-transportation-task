<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BusController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SeatController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\BusTripsController;
use App\Http\Controllers\Api\BusSeatsController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\UserBusesController;
use App\Http\Controllers\Api\SeatBusesController;
use App\Http\Controllers\Api\TripOrdersController;
use App\Http\Controllers\Api\GovernorateController;
use App\Http\Controllers\Api\CityStationsController;
use App\Http\Controllers\Api\StationTripsController;
use App\Http\Controllers\Api\UserPassengersController;
use App\Http\Controllers\Api\DiscountOrdersController;
use App\Http\Controllers\Api\SeatPassengersController;
use App\Http\Controllers\Api\OrderPassengersController;
use App\Http\Controllers\Api\GovernorateCitiesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');




Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('users', UserController::class);

        // User Buses
        Route::get('/users/{user}/buses', [
            UserBusesController::class,
            'index',
        ])->name('users.buses.index');
        Route::post('/users/{user}/buses', [
            UserBusesController::class,
            'store',
        ])->name('users.buses.store');

        // User Passengers
        Route::get('/users/{user}/passengers', [
            UserPassengersController::class,
            'index',
        ])->name('users.passengers.index');
        Route::post('/users/{user}/passengers', [
            UserPassengersController::class,
            'store',
        ])->name('users.passengers.store');

        Route::apiResource('buses', BusController::class);

        // Bus Trips
        Route::get('/buses/{bus}/trips', [
            BusTripsController::class,
            'index',
        ])->name('buses.trips.index');
        Route::post('/buses/{bus}/trips', [
            BusTripsController::class,
            'store',
        ])->name('buses.trips.store');

        // Bus Seats
        Route::get('/buses/{bus}/seats', [
            BusSeatsController::class,
            'index',
        ])->name('buses.seats.index');
        Route::post('/buses/{bus}/seats/{seat}', [
            BusSeatsController::class,
            'store',
        ])->name('buses.seats.store');
        Route::delete('/buses/{bus}/seats/{seat}', [
            BusSeatsController::class,
            'destroy',
        ])->name('buses.seats.destroy');

        Route::apiResource('discounts', DiscountController::class);

        // Discount Orders
        Route::get('/discounts/{discount}/orders', [
            DiscountOrdersController::class,
            'index',
        ])->name('discounts.orders.index');
        Route::post('/discounts/{discount}/orders', [
            DiscountOrdersController::class,
            'store',
        ])->name('discounts.orders.store');

        Route::apiResource('governorates', GovernorateController::class);

        // Governorate All Cities
        Route::get('/governorates/{governorate}/cities', [
            GovernorateCitiesController::class,
            'index',
        ])->name('governorates.cities.index');
        Route::post('/governorates/{governorate}/cities', [
            GovernorateCitiesController::class,
            'store',
        ])->name('governorates.cities.store');

        Route::apiResource('orders', OrderController::class);

        // Order Passengers
        Route::get('/orders/{order}/passengers', [
            OrderPassengersController::class,
            'index',
        ])->name('orders.passengers.index');
        Route::post('/orders/{order}/passengers', [
            OrderPassengersController::class,
            'store',
        ])->name('orders.passengers.store');

        Route::apiResource('seats', SeatController::class);

        // Seat Passengers
        Route::get('/seats/{seat}/passengers', [
            SeatPassengersController::class,
            'index',
        ])->name('seats.passengers.index');
        Route::post('/seats/{seat}/passengers', [
            SeatPassengersController::class,
            'store',
        ])->name('seats.passengers.store');

        // Seat Buses
        Route::get('/seats/{seat}/buses', [
            SeatBusesController::class,
            'index',
        ])->name('seats.buses.index');
        Route::post('/seats/{seat}/buses/{bus}', [
            SeatBusesController::class,
            'store',
        ])->name('seats.buses.store');
        Route::delete('/seats/{seat}/buses/{bus}', [
            SeatBusesController::class,
            'destroy',
        ])->name('seats.buses.destroy');

        Route::apiResource('cities', CityController::class);

        // City Stations
        Route::get('/cities/{city}/stations', [
            CityStationsController::class,
            'index',
        ])->name('cities.stations.index');
        Route::post('/cities/{city}/stations', [
            CityStationsController::class,
            'store',
        ])->name('cities.stations.store');

        Route::apiResource('stations', StationController::class);

        // Station Destinations
        Route::get('/stations/{station}/trips', [
            StationTripsController::class,
            'index',
        ])->name('stations.trips.index');
        Route::post('/stations/{station}/trips', [
            StationTripsController::class,
            'store',
        ])->name('stations.trips.store');

        // Station Pickups
        Route::get('/stations/{station}/trips', [
            StationTripsController::class,
            'index',
        ])->name('stations.trips.index');
        Route::post('/stations/{station}/trips', [
            StationTripsController::class,
            'store',
        ])->name('stations.trips.store');

        Route::apiResource('trips', TripController::class);

        // Trip Orders
        Route::get('/trips/{trip}/orders', [
            TripOrdersController::class,
            'index',
        ])->name('trips.orders.index');
        Route::post('/trips/{trip}/orders', [
            TripOrdersController::class,
            'store',
        ])->name('trips.orders.store');
    });
