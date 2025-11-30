<?php

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/transactions', function () {
    
    return TransactionResource::collection(Transaction::paginate(10));
});