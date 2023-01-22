<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class OrderController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * Start Order session by trip_id expire after 2 minutes
     */
    public function session(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id', 'numeric'],
            'expire' => ['required', 'numeric', 'min:60', 'max:7200', 'integer'],
        ]);

        $order = Order::create([
            'trip_id' => $validated['trip_id'],
            'expire_at' => now()->addSeconds($validated['expire']),
            'token' => Str::random(32),
            'status' => 'open'
        ]);

        // Get available seats for this trip
        $order->trip->bus->availableSeats;
        return new OrderResource($order);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'max:255', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'max:255', 'string'],
            'payment_type' => ['required', 'in:cash,card'],
        ]);

        $order = Order::whereToken($request->token)->first();

        // Check if session has been expired or not
        if ($order->expire_at->isPast() && ($order->status === 'open' || $order->status === 'expired')) {
            return response()->json([
                'message' => 'Session has been expired'
            ], 422);
        }

        // Check if order is not open
        if ($order->status !== 'open') {
            return response()->json([
                "message" => "Order has been {$order->status}"
            ], 422);
        }

        $order->update($validated);

        // Update total and subtotal and tax in order
        $order->calculateTotal();

        return new OrderResource($order);
    }
}
