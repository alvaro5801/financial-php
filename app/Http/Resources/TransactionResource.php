<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    return [
        'id' => $this->stripe_id, 
        'valor' => (float) $this->amount, 
        'comissao' => (float) $this->platform_fee,
        'moeda' => $this->currency,
        'status' => $this->status,
        'data_criacao' => $this->created_at->format('Y-m-d H:i:s'),
    ];
}
}
