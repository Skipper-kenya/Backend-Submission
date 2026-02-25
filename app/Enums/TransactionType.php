<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionType: string
{
    case Income = 'income';
    case Expense = 'expense';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
