<?php

/**
 * App\Models\Governorate
 * @property int $id
 * @property string $name
 * @property string $key
 * @property City[] $allCities
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'key'];

    protected $searchableFields = ['*'];

    public function cities()
    {
        return $this->hasMany(City::class, 'governorate_id');
    }
}
