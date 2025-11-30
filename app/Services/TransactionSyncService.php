<?php

namespace App\Services;

use App\DTOs\TransactionData;
use App\Models\Transaction;

class TransactionSyncService
{
    public function sync(TransactionData $dto): Transaction
    {
        // 1. Regra de Negócio: Cálculo de Comissão (Simulação)
        // Se valor > 100, taxa é 5%. Senão, 10%.
        $feePercentage = $dto->amount > 100 ? 0.05 : 0.10;
        $calculatedFee = $dto->amount * $feePercentage;

        // 2. Persistência (Idempotente)
        // Procura pelo 'stripe_id'. Se achar, atualiza. Se não, cria.
        return Transaction::updateOrCreate(
            ['stripe_id' => $dto->stripeId], // Chave de busca
            [
                'amount'          => $dto->amount,
                'amount_captured' => $dto->amountCaptured,
                'platform_fee'    => $calculatedFee, // Salvamos o valor calculado
                'currency'        => $dto->currency,
                'status'          => $dto->status,
                'description'     => $dto->description,
            ]
        );
    }
}