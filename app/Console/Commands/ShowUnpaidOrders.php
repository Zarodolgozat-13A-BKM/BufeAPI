<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Console\Command;

class ShowUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:show-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ordersToDelete = Order::where('status_id', Status::where('name', 'Fizetésre vár')->first()->id)
            ->where('created_at', '<', now()->subMinutes(30))
            ->get();
        foreach ($ordersToDelete as $order) {
            $this->info("Order ID: {$order->id}, Created At: {$order->created_at}");
        }
        if ($ordersToDelete->isEmpty()) {
            $this->info("There are no unpaid orders older than 30 minutes.");
        }
    }
}
