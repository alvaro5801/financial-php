<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Jobs\SyncStripeTransactionsJob; // Importante: Importamos o Job aqui

class DashboardController extends Controller
{
    // Método que exibe o Dashboard
    public function index()
    {
        // 1. Busca os KPIs
        $totalVolume = Transaction::sum('amount');
        $totalProfit = Transaction::sum('platform_fee');

        // 2. Busca as últimas transações
        $transactions = Transaction::latest()->limit(10)->get();

        // 3. Entrega tudo para o React
        return Inertia::render('Dashboard', [
            'kpis' => [
                'volume' => $totalVolume,
                'profit' => $totalProfit,
            ],
            'transactions' => $transactions,
        ]);
    }

    // Método que o botão "Sincronizar" vai chamar
    public function sync()
    {
        // Dispara o Job imediatamente
        SyncStripeTransactionsJob::dispatchSync();
        
        // Recarrega a página para mostrar os dados novos
        return redirect()->back();
    }
}