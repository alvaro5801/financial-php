<?php

namespace App\Jobs;

use App\DTOs\TransactionData;
use App\Services\TransactionSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\StripeClient;

class SyncStripeTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Configuração de Resiliência (Tenta 3 vezes se a API falhar)
    public $tries = 3;

    public function handle(TransactionSyncService $service): void
    {
        // 1. Conecta na API do Stripe usando a chave do .env
        $stripe = new StripeClient(config('services.stripe.secret', env('STRIPE_SECRET')));

        // 2. Busca as últimas 10 transações (Charges)
        // Expandimos o 'data' para garantir que venha tudo
        $charges = $stripe->charges->all(['limit' => 10]);

        // 3. Itera e Salva
        foreach ($charges->data as $charge) {
            // Converte JSON do Stripe para nosso DTO
            $dto = TransactionData::fromStripeObject($charge);
            
            // Manda o Service salvar
            $service->sync($dto);
        }
    }
}