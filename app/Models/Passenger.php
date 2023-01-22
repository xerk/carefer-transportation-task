<?php

/**
 * App\Models\Passenger
 * @property int $id
 * @property string $type
 * @property int $user_id
 * @property int $seat_id
 * @property int $order_id
 * @property User $user
 * @property Seat $seat
 * @property Order $order
 */

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passenger extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['type', 'user_id', 'seat_id', 'order_id'];

    protected $searchableFields = ['*'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
