<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'item_id' => $this->id,
            'item_name' => $this->name,
            'item_price' => $this->price,
            'picture_url' => env('APP_URL') . '/' . $this->picture_url,
            'quantity' => $this->pivot->quantity,
            'price' => $this->pivot->quantity * $this->price
        ];
    }
}
