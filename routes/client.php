<?php

use App\Http\Controllers\Api\Client\OrderController;
use App\Http\Controllers\Api\Client\TripController;
use App\Http\Controllers\Api\Client\StationController;





Route::get('/stations', [StationController::class, 'index'])->name('stations');
Route::get('/trips/search', [TripController::class, 'index'])->name('trips.search');
Route::get('/orders/session', [OrderController::class, 'getSession'])->name('orders.session.index');
Route::post('/orders/session', [OrderController::class, 'session'])->name('orders.session.store');
Route::post('/orders/update/seat', [OrderController::class, 'updateSeat'])->name('orders.update.seat');
Route::post('/orders/update', [OrderController::class, 'update'])->name('orders.update');
