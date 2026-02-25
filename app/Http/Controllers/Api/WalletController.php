<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Resources\WalletDetailResource;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    /**
     * Create a wallet for a user.
     */
    public function store(StoreWalletRequest $request): JsonResponse
    {
        $wallet = Wallet::query()->create($request->validated());

        return (new WalletResource($wallet))->response()->setStatusCode(201);
    }

    /**
     * Single wallet: balance plus all transactions.
     */
    public function show(Wallet $wallet): JsonResponse
    {
        return (new WalletDetailResource($wallet))->response();
    }
}
