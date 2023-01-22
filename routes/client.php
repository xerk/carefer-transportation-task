<?php

use App\Http\Controllers\Api\Client\OrderController;
use App\Http\Controllers\Api\Client\TripController;
use App\Http\Controllers\Api\Client\StationController;





Route::get('/stations', [StationController::class, 'index'])->name('stations');
Route::get('/trips/search', [TripController::class, 'index'])->name('trips.search');
Route::post('/orders/session', [OrderController::class, 'session'])->name('orders.session');
Route::post('/orders/update', [OrderController::class, 'update'])->name('orders.update');
