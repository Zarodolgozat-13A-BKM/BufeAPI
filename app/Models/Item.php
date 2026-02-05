<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use App\Policies\ItemPolicy;

class Item extends Model
{
    protected $fillable = [
        'name',
        'picture_url',
        'description',
        'price',
        'is_active',
        'default_time_to_deliver',
        'is_featured',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(OrderItem::class)->withPivot('quantity');
    }
}
