<?php

namespace App\DTOs;

readonly class TransactionData
{
    public function __construct(
        public string $stripeId,
        public float $amount,      
        public float $amountCaptured,
        public string $currency,
        public string $status,
        public ?string $description
    ) {}

    
    public static function fromStripeObject(object $charge): self
    {
        return new self(
            stripeId: $charge->id,
           
            amount: $charge->amount / 100,
            amountCaptured: $charge->amount_captured / 100,
            currency: strtoupper($charge->currency),
            status: $charge->status,
            description: $charge->description ?? 'Sem descrição'
        );
    }
}