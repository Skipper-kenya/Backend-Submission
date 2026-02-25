<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoneyTrackerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Jane Doe')
            ->assertJsonPath('data.email', 'jane@example.com');
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }

    public function test_user_creation_validates_required_fields(): void
    {
        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_user_creation_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->postJson('/api/users', [
            'name' => 'Other',
            'email' => 'taken@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_create_wallet(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/wallets', [
            'user_id' => $user->id,
            'name' => 'Business',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Business');
        $this->assertDatabaseHas('wallets', ['user_id' => $user->id, 'name' => 'Business']);
    }

    public function test_can_add_income_and_expense_and_view_wallet(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet->id,
            'type' => 'income',
            'amount' => 100.50,
            'description' => 'Sale',
        ])->assertStatus(201);

        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet->id,
            'type' => 'expense',
            'amount' => 30.25,
        ])->assertStatus(201);

        $response = $this->getJson("/api/wallets/{$wallet->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.balance', 70.25)
            ->assertJsonCount(2, 'data.transactions.data');
    }

    public function test_profile_shows_wallets_and_total_balance(): void
    {
        $user = User::factory()->create();
        Wallet::factory()->create(['user_id' => $user->id, 'name' => 'A']);
        Wallet::factory()->create(['user_id' => $user->id, 'name' => 'B']);

        $response = $this->getJson("/api/users/{$user->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.name', $user->name)
            ->assertJsonCount(2, 'data.wallets');
    }

    public function test_transaction_validates_type_and_positive_amount(): void
    {
        $wallet = Wallet::factory()->create();

        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet->id,
            'type' => 'invalid',
            'amount' => -10,
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'amount']);
    }

    public function test_wallet_show_returns_404_for_missing_wallet(): void
    {
        $response = $this->getJson('/api/wallets/99999');
        $response->assertStatus(404);
    }
}
