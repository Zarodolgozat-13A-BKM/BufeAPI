<?php

namespace App\Models;

use App\Observers\OrderItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ObservedBy(OrderItemObserver::class)]
class OrderItem extends Pivot
{
    //
    protected $table = 'item_order';
    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
    ];
}
