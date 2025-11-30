<?php

use App\DTOs\TransactionData;
use App\Services\TransactionSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// AQUI ESTÁ A CORREÇÃO:
// Adicionamos TestCase para carregar a aplicação corretamente
uses(TestCase::class, RefreshDatabase::class);

test('o serviço deve salvar uma nova transação no banco de dados', function () {
    $dto = new TransactionData(
        stripeId: 'ch_integration_test',
        amount: 200.00,
        amountCaptured: 200.00,
        currency: 'BRL',
        status: 'succeeded',
        description: 'Teste de Integração'
    );

    (new TransactionSyncService())->sync($dto);

    $this->assertDatabaseHas('transactions', [
        'stripe_id' => 'ch_integration_test',
        'amount' => 200.00,
        'platform_fee' => 10.00,
    ]);
});

test('o serviço deve atualizar a transação se o ID já existir', function () {
    $dto = new TransactionData(
        stripeId: 'ch_update_test',
        amount: 100.00,
        amountCaptured: 100.00,
        currency: 'BRL',
        status: 'pending',
        description: 'Original'
    );
    (new TransactionSyncService())->sync($dto);

    $dtoAtualizado = new TransactionData(
        stripeId: 'ch_update_test',
        amount: 100.00,
        amountCaptured: 100.00,
        currency: 'BRL',
        status: 'succeeded',
        description: 'Atualizado'
    );
    
    (new TransactionSyncService())->sync($dtoAtualizado);

    $this->assertDatabaseCount('transactions', 1);
    $this->assertDatabaseHas('transactions', [
        'stripe_id' => 'ch_update_test',
        'status' => 'succeeded',
    ]);
});