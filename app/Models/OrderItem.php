<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

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
