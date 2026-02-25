<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $fillable = ['wallet_id', 'type', 'amount', 'description'];

    /** @var array<string, string> */
    protected $casts = [
        'amount' => 'decimal:2',
        'type' => TransactionType::class,
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
