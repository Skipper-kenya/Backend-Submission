<?php

declare(strict_types=1);

use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Money Tracker API
|--------------------------------------------------------------------------
| Route model binding: {user} and {wallet} resolve to models; 404 when not found.
*/

Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);

Route::post('/wallets', [WalletController::class, 'store']);
Route::get('/wallets/{wallet}', [WalletController::class, 'show']);

Route::post('/transactions', [TransactionController::class, 'store']);
