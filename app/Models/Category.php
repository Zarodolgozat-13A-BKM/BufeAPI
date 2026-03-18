<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Policies\CategoryPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    // public function scopeVisibleTo($query, $user)
    // {
    //     if ($user->is_admin) {
    //         return $query;
    //     }
    //     return $query->where(function ($q) use ($user) {
    //         $q->where('user_id', $user->id);
    //     });
    // }
}
