<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ItemResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'default_time_to_deliver' => $this->default_time_to_deliver,
            'category_id' => $this->category_id,
            'is_featured' => $this->is_featured,
            'picture_url' => ($this->picture_url != "placeholder.jpg") ? url('/') . Storage::url($this->picture_url) : url('/') . "placeholder.jpg"
        ];
    }
}
