<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    public function view(User $user, Item $item): bool
    {
        return $item->is_active || $user->isAdmin();
    }
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }
    public function update(User $user, Item $item): bool
    {
        return $user->isAdmin();
    }
    public function delete(User $user, Item $item): bool
    {
        return $user->isAdmin();
    }
}
