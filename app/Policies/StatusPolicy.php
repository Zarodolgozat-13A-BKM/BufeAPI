<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusPolicy
{

    public function before(User $user)
    {
        return $user->is_admin;
    }
}
