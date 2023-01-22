<?php

namespace App\Http\Controllers\Api;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Http\Resources\DiscountCollection;

class DiscountController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Discount::class);

        $search = $request->get('search', '');

        $discounts = Discount::search($search)
            ->latest()
            ->paginate();

        return new DiscountCollection($discounts);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Discount::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'number_of_seats' => ['required', 'numeric'],
            'percentage' => ['required', 'numeric'],
        ]);

        $discount = Discount::create($validated);

        return new DiscountResource($discount);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Discount $discount)
    {
        $this->authorize('view', $discount);

        return new DiscountResource($discount);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {
        $this->authorize('update', $discount);

        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'number_of_seats' => ['required', 'numeric'],
            'percentage' => ['required', 'numeric'],
        ]);

        $discount->update($validated);

        return new DiscountResource($discount);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Discount $discount)
    {
        $this->authorize('delete', $discount);

        $discount->delete();

        return response()->noContent();
    }
}
