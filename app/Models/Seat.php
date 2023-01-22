<?php

/**
 * App\Models\Seat
 * @property int $id
 * @property string $referance
 * @property int $number
 * @property int $line
 * @property Passenger[] $passengers
 * @property Bus[] $buses
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['referance', 'number', 'line'];

    protected $searchableFields = ['*'];

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function buses()
    {
        return $this->belongsToMany(Bus::class);
    }
}
