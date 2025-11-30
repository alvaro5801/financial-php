<?php

use App\DTOs\TransactionData;
use App\Services\TransactionSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('deve calcular 5% de comissão quando o valor for maior que 100 reais', function () {
    $dto = new TransactionData(
        stripeId: 'test_123',
        amount: 150.00,
        amountCaptured: 150.00,
        currency: 'BRL',
        status: 'succeeded',
        description: 'Teste Unitário'
    );

    $service = new TransactionSyncService();
    $transaction = $service->sync($dto);

    expect($transaction->platform_fee)->toBe(7.50);
});

test('deve calcular 10% de comissão quando o valor for menor ou igual a 100 reais', function () {
    $dto = new TransactionData(
        stripeId: 'test_456',
        amount: 50.00,
        amountCaptured: 50.00,
        currency: 'BRL',
        status: 'succeeded',
        description: 'Teste Unitário Menor'
    );

    $service = new TransactionSyncService();
    $transaction = $service->sync($dto);

    expect($transaction->platform_fee)->toBe(5.00);
});