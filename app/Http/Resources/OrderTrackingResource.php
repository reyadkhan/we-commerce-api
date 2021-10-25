<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingResource extends JsonResource
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
            'status' => $this->status,
            'orderPrice' => $this->order_price,
            'orderQuantity' => $this->order_quantity,
            'details' => $this->details,
            'products' => ProductResource::collection($this->products)
        ];
    }
}
