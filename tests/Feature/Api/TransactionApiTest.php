<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; 


uses(TestCase::class, RefreshDatabase::class);

test('o endpoint da API deve listar transações com a estrutura correta', function () {
    
    Transaction::create([
        'stripe_id' => 'ch_api_test_123',
        'amount' => 150.00,
        'amount_captured' => 150.00,
        'platform_fee' => 7.50,
        'currency' => 'BRL',
        'status' => 'succeeded',
        'description' => 'Teste via API'
    ]);

    
    $response = $this->getJson('/api/transactions');

    
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         'valor',
                         'comissao',
                         'moeda',
                         'status',
                         'data_criacao'
                     ]
                 ],
                 'links',
                 'meta'
             ])
             ->assertJsonFragment([
                 'id' => 'ch_api_test_123',
                 'valor' => 150,
             ]);
});