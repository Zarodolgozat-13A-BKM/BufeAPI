<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_identifier_number' => $this->order_identifier_number,
            'user_username' => $this->user->username,
            'status' => $this->status->name,
            'delivery_date' => $this->delivery_date,
            'items' => OrderItemResource::collection($this->items),
            'total_price' => $this->items->sum(fn($item) => $item->pivot->quantity * $item->price),
            'default_completion_time' => $this->items->sum(fn($item) => $item->default_time_to_deliver),
            'comment' => $this->comment,
            'payment_intent_id' => $this->payment_intent_id,
            'kiosk_order' => $this->kiosk_order
        ];
    }
}
