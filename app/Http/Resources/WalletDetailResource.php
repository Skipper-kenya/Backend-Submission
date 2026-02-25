<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Wallet */
class WalletDetailResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        $this->loadMissing('transactions');

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'balance' => $this->balance,
            'transactions' => TransactionResource::collection($this->transactions),
        ];
    }
}
