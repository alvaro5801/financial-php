<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportTransactionsToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // 1. Pega todas as transações
        $transactions = Transaction::all();
        
        // 2. Converte para JSON bonito
        $content = json_encode($transactions, JSON_PRETTY_PRINT);
        
        // 3. Define nome do arquivo com data (ex: backup_2023-10-20.json)
        $fileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.json';

        // 4. Envia para o S3 (MinIO)
        Storage::disk('s3')->put($fileName, $content);

        logger()->info("Arquivo {$fileName} enviado para o S3 com sucesso!");
    }
}