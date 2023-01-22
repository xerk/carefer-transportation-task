<?php

/**
 * App\Models\Bus
 * @property int $id
 * @property int $user_id
 * @property bool $maintenance
 * @property int $capacity
 * @property User $user
 * @property Trip[] $trips
 * @property Seat[] $seats
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['user_id', 'name', 'maintenance', 'capacity'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'maintenance' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class);
    }

    /**
     * Get the available seats for the bus check available_at.
     *
     * @return array
     */
    public function availableSeats()
    {
        return $this->belongsToMany(Seat::class)->withPivot('active', 'available_at')->wherePivot('active', true)->wherePivotNull('available_at')->orWherePivot('available_at', '<=', now());
    }
}
