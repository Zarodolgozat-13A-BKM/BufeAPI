<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Status;
use App\Services\StripeService;
use Illuminate\Console\Command;

class DeleteUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:delete-unpaid';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes orders that have been unpaid for more than 30 minutes by changing their status to "Törölve"';


    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        parent::__construct();
        $this->stripeService = $stripeService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ordersToDelete = Order::where('status_id', Status::where('name', 'Fizetésre vár')->first()->id)
            ->where('created_at', '<', now()->subMinutes(30))
            ->get();
        foreach ($ordersToDelete as $order) {
            if ($order->payment_intent_id) {
                $this->stripeService->cancelPaymentIntent($order->payment_intent_id);
            } else {
                $order->update(['status_id' => Status::where('name', 'Törölve')->first()->id]);
            }
            $this->info("Order ID: {$order->id} has been marked as 'Törölve'");
        }
    }
}
