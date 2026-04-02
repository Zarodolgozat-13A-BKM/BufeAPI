<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('ordersOfUser.{userEmail}', function ($user, $userEmail) {
    error_log("Checking channel access for user: " . $user->email . " against channel email: " . $userEmail);
    return ($user->email === $userEmail) || $user->isAdmin();
});
Broadcast::channel('orders_admin', function ($user) {
    return $user->isAdmin();
});
