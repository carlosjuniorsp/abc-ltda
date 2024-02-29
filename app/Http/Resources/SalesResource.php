<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tb_client_id' => $this->tb_client_id,
            'tb_product_id' => $this->tb_product_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }
}
