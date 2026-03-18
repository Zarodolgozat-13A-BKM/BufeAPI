<?php

namespace App\Http\Controllers;

use App\Events\NewOrderSubmitted;
use App\Events\OrderStateChanged;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Mail\ReceiptMail;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Services\StripeService;
use Illuminate\Support\Facades\Mail;
use Stripe\Webhook;

class PaymentController extends Controller
{
    protected $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function checkout(Request $request)
    {

        $data = $request->validate([
            'delivery_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'comment' => 'nullable|string|max:255'
        ]);

        $user = $request->user();
        $amount = collect($request['items'])->sum(function ($item) {
            $itemModel = Item::find($item['item_id']);
            return $itemModel->price * $item['quantity'];
        });
        if ($amount <= 200) {
            $amount = 200;
        }
        $amount *= 100;
        $intent = $this->stripe->createPaymentIntent($amount);

        $lastNumber = Order::all()->sortBy('timestamp')?->last()->order_identifier_number ?? 0;
        $number = 1;
        if ($lastNumber <= 100) {
            $number = $lastNumber + 1;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'order_identifier_number' => $number,
            'status_id' => Status::where('name', 'Fizetésre vár')->first()->id,
            'comment' => $data['comment'] ?? null,
            'delivery_date' => $data['delivery_date'] ?? null,
            'payment_intent_id' => $intent->id
        ]);

        foreach ($data['items'] as $itemData) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
            ]);
        }
        Mail::to($user->email)->send(new ReceiptMail($order));

        // broadcast(new NewOrderSubmitted($order))->toOthers();

        return response()->json([
            'client_secret' => $intent->client_secret,
            'order' => OrderResource::make($order)
        ]);
    }
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        $event = Webhook::constructEvent(
            $payload,
            $sigHeader,
            $endpointSecret
        );

        switch ($event->type) {

            case 'payment_intent.succeeded':

                $paymentIntent = $event->data->object;
                $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
                $order->update(['status_id' => Status::where('name', 'Fizetve')->first()->id]);
                broadcast(new OrderStateChanged($order));
                break;

            case 'payment_intent.payment_failed':

                $paymentIntent = $event->data->object;
                $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
                $order->update(['status_id' => Status::where('name', 'Törölve')->first()->id]);
                broadcast(new OrderStateChanged($order));
                break;

            case 'charge.refunded':

                $charge = $event->data->object;

                break;

            default:
                return response()->json(['message' => 'Unhandled event type'], 400);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}
