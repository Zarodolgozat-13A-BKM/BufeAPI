<?php

namespace App\Models;

use App\Http\Resources\OrderResource;
use App\Services\ReceiptManagementService;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use BroadcastsEvents, HasFactory;
    protected $fillable = [
        'user_id',
        'order_identifier_number',
        'status_id',
        'delivery_date',
        'payment_intent_id',
        'comment'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function totalPrice()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->pivot->quantity;
        });
    }

    public function completionTime()
    {
        return $this->items()->sum('default_time_to_deliver');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->using(OrderItem::class)->withPivot('quantity');
    }

    /**
     * Get the channels that model events should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel|\Illuminate\Database\Eloquent\Model>
     */
    public function broadcastOn()
    {
        return [new PrivateChannel('orders_admin'), new PrivateChannel('order.' . $this->id)];
    }
    public function broadcastAs()
    {
        return 'order.state.changed';
    }

    public function broadcastWith()
    {
        return [
            'order' => new OrderResource($this),
        ];
    }
}
