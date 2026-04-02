<?php

use App\Models\Log;
use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('ordersOfUser.{userEmailInBase64}', function ($user, $userEmailInBase64) {
    $userEmail = base64_decode($userEmailInBase64);
    return $user->email === $userEmail;
    // Log::create(['message' => "Checking channel access for user: " . $user->email . " against channel email: " . $userEmail]);
});
Broadcast::channel('orders_admin', function ($user) {
    return $user->isAdmin();
});
