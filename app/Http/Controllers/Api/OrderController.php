<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class OrderController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Order::class);

        $search = $request->get('search', '');

        $orders = Order::search($search)
            ->latest()
            ->paginate();

        return new OrderCollection($orders);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'name' => ['nullable', 'max:255', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'max:255', 'string'],
            'trip_id' => ['required', 'exists:trips,id'],
            'discount_id' => ['nullable', 'exists:discounts,id'],
            'payment_type' => ['required', 'in:cash,card'],
            'tax' => ['nullable', 'max:255', 'string'],
            'subtotal_amount' => ['nullable', 'max:255', 'string'],
            'total_amount' => ['nullable', 'max:255', 'string'],
        ]);

        $order = Order::create($validated);

        return new OrderResource($order);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        return new OrderResource($order);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'name' => ['nullable', 'max:255', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'max:255', 'string'],
            'trip_id' => ['required', 'exists:trips,id'],
            'discount_id' => ['nullable', 'exists:discounts,id'],
            'payment_type' => ['required', 'in:cash,card'],
            'tax' => ['nullable', 'max:255', 'string'],
            'subtotal_amount' => ['nullable', 'max:255', 'string'],
            'total_amount' => ['nullable', 'max:255', 'string'],
        ]);

        $order->update($validated);

        return new OrderResource($order);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();

        return response()->noContent();
    }
}
