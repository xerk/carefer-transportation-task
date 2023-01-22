<?php

/**
 * App\Models\Discount
 * @property int $id
 * @property string $name
 * @property int $number_of_seats
 * @property int $percentage
 * @property Order[] $orders
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'number_of_seats', 'percentage'];

    protected $searchableFields = ['*'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
