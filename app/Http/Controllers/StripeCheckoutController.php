<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeCheckoutController extends Controller
{
    public function createSession(Request $request){
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::create(([
            'payment_method'
        ]))
    }
}
