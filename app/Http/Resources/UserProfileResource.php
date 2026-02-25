<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserProfileResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        $this->loadMissing(['wallets', 'wallets.transactions']);

        $wallets = $this->wallets->map(fn ($w) => [
            'id' => $w->id,
            'name' => $w->name,
            'balance' => $w->balance,
        ]);

        $totalBalance = round((float) $this->wallets->sum(fn ($w) => $w->balance), 2);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'wallets' => $wallets,
            'total_balance' => $totalBalance,
        ];
    }
}
