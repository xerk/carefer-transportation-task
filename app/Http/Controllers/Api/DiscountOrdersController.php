<?php

namespace App\Http\Controllers\Api;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class DiscountOrdersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Discount $discount)
    {
        $this->authorize('view', $discount);

        $search = $request->get('search', '');

        $orders = $discount
            ->orders()
            ->search($search)
            ->latest()
            ->paginate();

        return new OrderCollection($orders);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Discount $discount)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'name' => ['nullable', 'max:255', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'max:255', 'string'],
            'trip_id' => ['required', 'exists:trips,id'],
            'payment_type' => ['required', 'in:cash,card'],
            'tax' => ['nullable', 'max:255', 'string'],
            'subtotal_amount' => ['nullable', 'max:255', 'string'],
            'total_amount' => ['nullable', 'max:255', 'string'],
        ]);

        $order = $discount->orders()->create($validated);

        return new OrderResource($order);
    }
}
