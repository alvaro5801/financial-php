<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // ID único do Stripe (Importante para evitar duplicidade)
            $table->string('stripe_id')->unique();

            // Valores Financeiros (Decimal é obrigatório para dinheiro)
            $table->decimal('amount', 10, 2);          // Valor Total
            $table->decimal('amount_captured', 10, 2); // Valor Pago
            $table->decimal('platform_fee', 10, 2);    // Nossa Comissão

            // Metadados
            $table->string('currency', 3);             // BRL, USD
            $table->string('status');                  // succeeded, failed
            $table->text('description')->nullable();   // Descrição opcional

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Se precisarmos desfazer a migration, apagamos a tabela
        Schema::dropIfExists('transactions');
    }
};