<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_identifier_number',
        'status_id',
        'delivery_date',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function totalPrice()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->pivot->quantity;
        });
    }

    public function completionTime()
    {
        return $this->items()->sum('default_time_to_deliver');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->using(OrderItem::class)->withPivot('quantity');
    }
}
