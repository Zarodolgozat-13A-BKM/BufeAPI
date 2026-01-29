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
}
