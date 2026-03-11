<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    $order = Order::find($orderId);
    return ($order && $user->id === $order->user_id) || $user->isAdmin();
});
