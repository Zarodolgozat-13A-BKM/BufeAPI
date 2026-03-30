<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('ordersOfUser.{userEmail}', function ($user, $userEmail) {
    return ($user->email === $userEmail) || $user->isAdmin();
});
Broadcast::channel('orders_admin', function ($user) {
    return $user->isAdmin();
});
