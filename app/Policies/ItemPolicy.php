<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    public function view(User $user, Item $item): bool
    {
        return $item->is_active || $user->role === 'admin';
    }
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }
    public function update(User $user, Item $item): bool
    {
        return $user->role === 'admin';
    }
    public function delete(User $user, Item $item): bool
    {
        return $user->role === 'admin';
    }
    public function toggleItemActiveStatus(User $user, Item $item): bool
    {
        return $user->role === 'admin';
    }
}
