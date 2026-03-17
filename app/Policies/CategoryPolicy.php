<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function view(User $user, Category $category): bool
    {
        return $category->items()->count() > 0 || $user->role === 'admin';
    }
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }
    public function update(User $user, Category $category): bool
    {

        return $user->role === 'admin';
    }
    public function delete(User $user, Category $category): bool
    {
        return $user->role === 'admin';
    }
}
