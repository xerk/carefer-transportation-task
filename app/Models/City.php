<?php

/**
 * App\Models\City
 * @property int $id
 * @property int $governorate_id
 * @property string $name
 * @property string $key
 * @property Governorate $governorate
 * @property Station[] $stations
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['governorate_id', 'name', 'key'];

    protected $searchableFields = ['*'];

    public $timestamps = false;

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
