<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'picture_url',
        'description',
        'price',
        'is_active',
        'default_time_to_deliver',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(OrderItem::class)->withPivot('quantity');
    }
}
