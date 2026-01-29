<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pivot_Order_Item extends Model
{
    protected $table = 'order_item_pivot';

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
