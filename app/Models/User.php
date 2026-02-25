<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $fillable = ['name', 'email'];

    /** One user has many wallets (e.g. different businesses). */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }
}
