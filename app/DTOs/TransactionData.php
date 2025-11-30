<?php

namespace App\DTOs;

readonly class TransactionData
{
    public function __construct(
        public string $stripeId,
        public float $amount,      // Valor já convertido em Reais
        public float $amountCaptured,
        public string $currency,
        public string $status,
        public ?string $description
    ) {}

    /**
     * Transforma o objeto do Stripe SDK no nosso DTO
     */
    public static function fromStripeObject(object $charge): self
    {
        return new self(
            stripeId: $charge->id,
            // O Stripe envia valores em centavos (integer).
            // Nós convertemos para float (decimal) dividindo por 100.
            amount: $charge->amount / 100,
            amountCaptured: $charge->amount_captured / 100,
            currency: strtoupper($charge->currency),
            status: $charge->status,
            description: $charge->description ?? 'Sem descrição'
        );
    }
}