<?php

/**
 * App\Models\Station
 * @property int $id
 * @property string $name
 * @property int $city_id
 * @property float $longitude
 * @property float $latitude
 * @property boolean $active
 * @property string $created_at
 * @property string $updated_at
 * @property City $city
 * @property Trip[] $destinations
 * @property Trip[] $pickups
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Station extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'city_id',
        'longitude',
        'latitude',
        'active',
    ];

    protected $searchableFields = ['name'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function destinations()
    {
        return $this->hasMany(Trip::class, 'destination_id');
    }

    public function pickups()
    {
        return $this->hasMany(Trip::class, 'pickup_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
