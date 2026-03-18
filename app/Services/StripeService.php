<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent($amount, $currency = 'huf')
    {
        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => "never",
            ],
        ]);
    }
}
