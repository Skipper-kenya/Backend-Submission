<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * Add a transaction (income or expense) to a wallet.
     * Income increases balance; expense decreases it.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $transaction = Transaction::query()->create($request->validated());

        return (new TransactionResource($transaction))->response()->setStatusCode(201);
    }
}
