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

    
    public $tries = 3;

    public function handle(TransactionSyncService $service): void
    {
        
        $stripe = new StripeClient(config('services.stripe.secret', env('STRIPE_SECRET')));

        
        $charges = $stripe->charges->all(['limit' => 10]);

        
        foreach ($charges->data as $charge) {
            
            $dto = TransactionData::fromStripeObject($charge);
            
            
            $service->sync($dto);
        }
    }
}