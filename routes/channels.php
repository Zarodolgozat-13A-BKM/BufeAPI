<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('ordersOfUser.{userId}', function ($user, $userId) {
    return ($user->id === $userId) || $user->isAdmin();
});
Broadcast::channel('orders_admin', function ($user) {
    return $user->isAdmin();
});
