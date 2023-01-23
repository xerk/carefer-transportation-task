<?php

/**
 * App\Models\Order
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $trip_id
 * @property int $discount_id
 * @property string $payment_type
 * @property float $tax
 * @property float $subtotal_amount
 * @property float $total_amount
 * @property string $token
 * @property string $expire_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Discount $discount
 * @property Trip $trip
 * @property Passenger[] $passengers
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'trip_id',
        'discount_id',
        'payment_type',
        'payment_status',
        'date',

        'tax',
        'subtotal_amount',
        'total_amount',

        'token',
        'expire_at',
    ];

    protected $searchableFields = ['*'];

    /**
     * @var string[]
     */
    protected $casts = [
        'date' => 'datetime',
        'expire_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * @return BelongsTo
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * @return HasMany
     */
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    // Not expire orders
    public function scopeNotExpire($query)
    {
        return $query->where('expire_at', '>', now())->where('status', '!=', 'expired')->orWhere('status', 'closed');
    }

    public function calculateTotal()
    {
        // Update order total
        $this->total_amount = $this->trip->price * $this->passengers()->count();

        // Update order discount
        if ($this->discount) {
            $percentage = $this->discount->percentage;
            $this->total_amount = $this->total_amount - ($this->total_amount * ($percentage / 100));
        }

        // Update order subtotal
        $this->subtotal_amount = number_format($this->total_amount, 2, '.', '');

        // Update order tax
        $this->tax = number_format(($this->total_amount * (14 / 100)), 2, '.', '');

        // Update order total
        $this->total_amount = number_format($this->subtotal_amount + $this->tax, 2, '.', '');

        $this->save();
    }

    /**
     * Get the available seats from the bus of the trip where a seat not in the order passengers where the order date and status is closed
     *
     * @return array
     */
    public function getAvailableSeats() : array
    {
        $seats = $this->trip->bus->availableSeats;

        $passengers = Passenger::whereHas('order', function ($query) {
            $query->where('date', $this->date)->where('status', '<>','expired');
        })->get();

        $passengersSeats = $passengers->pluck('seat_id');

        // Return the available seats not available in the passengers seats
        return $seats->whereNotIn('id', $passengersSeats)->pluck('id')->toArray();
    }

}
