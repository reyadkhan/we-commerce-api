<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'orderId' => $this->order_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'products' => OrderProductResource::collection($this->products)
        ];
    }
}
