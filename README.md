# Money Tracker API (Backend Assessment)

PHP Laravel API for the Backend Assessment. Backend-only; a frontend consumes these endpoints. No authentication.

## Requirements

- PHP 8.2+
- Composer
- SQLite (default) or MySQL

## Setup

```bash
cd backend-submission
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

API base: `http://localhost:8000/api`

## Tests

```bash
composer install
php artisan test
```

Uses in-memory SQLite; no `.env` needed for the test run.

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/users` | Create user |
| GET | `/api/users/{user}` | Profile: wallets, each balance, total balance |
| POST | `/api/wallets` | Create wallet |
| GET | `/api/wallets/{wallet}` | Wallet balance + all transactions |
| POST | `/api/transactions` | Add income/expense |

All success responses use a `data` key. Validation errors return `422` with `message` and `errors`. Missing resources return `404`.

## Example requests (curl)

**Create user**

```bash
curl -s -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane Doe","email":"jane@example.com"}' | jq
```

**Create wallet**

```bash
curl -s -X POST http://localhost:8000/api/wallets \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"name":"Business"}' | jq
```

**Add transaction**

```bash
curl -s -X POST http://localhost:8000/api/transactions \
  -H "Content-Type: application/json" \
  -d '{"wallet_id":1,"type":"income","amount":100.50,"description":"Sale"}' | jq
```

**Profile (wallets + balances)**

```bash
curl -s http://localhost:8000/api/users/1 | jq
```

**Single wallet (balance + transactions)**

```bash
curl -s http://localhost:8000/api/wallets/1 | jq
```

## Validation

- **Users:** `name` and `email` required; `email` unique.
- **Wallets:** `user_id` (exists), `name` required.
- **Transactions:** `wallet_id` (exists), `type` in `income`|`expense`, `amount` ≥ 0.01; `description` optional.

Balances: income adds, expense subtracts.

## Project structure

```
app/
  Enums/TransactionType.php      # income | expense
  Http/
    Controllers/Api/              # User, Wallet, Transaction
    Requests/                     # StoreUserRequest, StoreWalletRequest, StoreTransactionRequest
    Resources/                    # API response shapes (User, UserProfile, Wallet, WalletDetail, Transaction)
    Traits/RespondsWithJson.php   # Optional consistent error responses
  Models/                         # User, Wallet, Transaction (relationships, casts)
database/
  factories/                      # User, Wallet, Transaction (for tests)
  migrations/
routes/api.php                    # Route model binding: /users/{user}, /wallets/{wallet}
tests/Feature/MoneyTrackerApiTest.php
```