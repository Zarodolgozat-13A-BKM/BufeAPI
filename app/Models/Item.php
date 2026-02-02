<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use App\Policies\ItemPolicy;

#[UsePolicy(ItemPolicy::class)]
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
