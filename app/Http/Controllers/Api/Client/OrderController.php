<?php

namespace App\Http\Controllers\Api\Client;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Discount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Models\Passenger;
use Exception;

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
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $order = Order::create([
            'trip_id' => $validated['trip_id'],
            'expire_at' => now()->addSeconds($validated['expire']),
            'token' => Str::random(32),
            'status' => 'open',
            'date' => $validated['date'],
        ]);

        return [
            'order' => new OrderResource($order),
            'available_seats' => $order->getAvailableSeats(),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * Get session by token
     */
    public function getSession(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'exists:orders,token'],
        ]);

        $order = Order::whereToken($validated['token'])->first();

        // Check if session has been expired or not
        if ($order->expire_at->isPast() && ($order->status === 'open' || $order->status === 'expired')) {
            return response()->json([
                'message' => 'Session has been expired'
            ], 422);
        }

        return [
            'order' => new OrderResource($order),
            'available_seats' => $order->getAvailableSeats(),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * Update seat availability for this order until the order is closed or expired by seat_id for each click on seat
     */
    public function updateSeat(Request $request)
    {
        $validated = $request->validate([
            'seat_id' => ['required', 'exists:seats,id', 'numeric'],
            'token' => ['required', 'exists:orders,token'],
        ]);
        $order = Order::whereToken($request->token)->first();

        $this->validateSeat($validated['seat_id'], $order);


        $order->passengers()->create([
            'seat_id' => $request->seat_id,
            "type" => $order->passengers->count() === 0 ? 'user' : 'guest',
            "user_id" => $order->passengers->count() === 0 ? auth()->id() : null,
        ]);

        return new OrderResource($order);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * Validate seat data
     */
    private function validateSeat($seat_id, $order)
    {

        // Check if session has been expired or not
        if ($order->expire_at->isPast() && ($order->status === 'open' || $order->status === 'expired')) {
            return response()->json([
                'message' => 'Session has been expired'
            ], 422)->throwResponse();
        }

        // Check if the selected seats are available
        $availableSeats = Passenger::whereHas('order', function ($query) use ($order) {
            $query->where('date', $order->date)->notExpire()->where('id', '<>', $order->id);
        })->where('seat_id', $seat_id)->get();


        if ($availableSeats->count() > 0) {
            return response()->json([
                'message' => 'Selected seat is not available',
            ], 422)->throwResponse();
        }

        // Check if order is not open
        if ($order->status !== 'open') {
            return response()->json([
                "message" => "Order has been {$order->status}"
            ], 422)->throwResponse();
        }

        // Check if seat is already selected
        if ($order->passengers->where('seat_id', $seat_id)->count() > 0) {
            return response()->json([
                'message' => 'Seat is already selected, please select another seat or remove seat_ids from request'
            ], 422)->throwResponse();
        }

        // Check if seat is already selected
        if ($order->passengers->count() >= $order->trip->bus->capacity) {
            return response()->json([
                'message' => 'All seats are selected'
            ], 422)->throwResponse();
        }

        return true;
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
            'token' => ['required', 'exists:orders,token'],
            'passengers' => ['array', 'min:1', 'size:' . $request->seat_count],
            // if has passengers array requred fields
            'passengers.*.type' => ['required', 'in:user,guest'],
            // if type is user
            'passengers.*.user_id' => ['required_if:passengers.*.type,user', 'exists:users,id'],
            // passenger seat_id
            'passengers.*.seat_id' => ['required', 'exists:seats,id', 'numeric'],
        ]);

        $order = Order::whereToken($request->token)->first();

        // Get seat count from order passengers if exists or from request
        $seatCount = $order->passengers->count();

        // Get seat ids from order passengers if exists or from request
        $seatIds = $order->passengers->pluck('seat_id')->toArray();

        // Check if the selected seats are available
        $availableSeats = Passenger::whereHas('order', function ($query) use ($order) {
            $query->where('date', $order->date)->notExpire();
        })->whereIn('seat_id', $seatIds)->where('order_id', '<>', $order->id)->get();

        if ($availableSeats->count() > 0) {
            return response()->json([
                'message' => 'Selected seat(s) are not available',
                'seats' => $availableSeats->pluck('seat_id')->unique()->values()
            ], 422);
        }

        // Get Discount record if exists from number of seats
        $discount = Discount::whereNumberOfSeats($seatCount)->first();

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

        if (!$request->has('seat_ids') && $order->passengers->count() === 0) {
            return response()->json([
                'message' => 'Please select at least one seat'
            ], 422);
        }

        if ($request->has('seat_ids')) {
            foreach ($request->seat_ids as $seat_id) {
                $this->validateSeat($seat_id, $order);
            }
        }

        DB::beginTransaction();

        try {

            // Update order
            $order->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'payment_type' => $request->payment_type,
                'discount_id' => $discount->id ?? null,
                'status' => 'closed',
            ]);

            // Send request to updateSeat method to update passengers
            if ($request->has('seat_ids')) {
                foreach ($request->seat_ids as $seat_id) {
                    $order->passengers()->updateOrCreate(['seat_id' => $seat_id], [
                        'seat_id' => $seat_id,
                    ]);
                }
            }

            // Update total and subtotal and tax in order
            $order->calculateTotal();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => "Some thing went wrong"
            ], 422);
        }

        return new OrderResource($order);
    }
}
