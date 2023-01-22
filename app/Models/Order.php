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
        return $query->where('expire_at', '<', now())->where('status', '!=', 'expired');
    }

    public function calculateTotal()
    {
        $this->subtotal_amount = $this->trip->price;
        $this->tax = $this->subtotal_amount * 0.14; // 14% tax rate of egypt
        $this->total = $this->subtotal_amount + $this->tax;
        $this->save();
    }

}
