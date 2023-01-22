<?php

/**
 * App\Models\Trip
 * @property int $id
 * @property string $name
 * @property string $frequent
 * @property int $pickup_id
 * @property int $destination_id
 * @property int $bus_id
 * @property string $type
 * @property float $price
 * @property float $distance
 * @property boolean $active
 * @property string $start_at
 * @property string $end_at
 * @property string $cron_experations
 * @property string $created_at
 * @property string $updated_at
 * @property Bus $bus
 * @property Order[] $orders
 * @property Station $destination
 * @property Station $pickup
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'frequent',
        'pickup_id',
        'destination_id',
        'bus_id',
        'type',
        'price',
        'distance',
        'active',
        'start_at',
        'end_at',
        'cron_experations',
    ];


    protected $searchableFields = ['*'];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the destination that owns the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination()
    {
        return $this->belongsTo(Station::class, 'destination_id');
    }

    /**
     * Get the bus that owns the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get all of the orders for the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the pickup that owns the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pickup()
    {
        return $this->belongsTo(Station::class, 'pickup_id');
    }
}
