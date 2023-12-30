<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function testAccountsIsMenuItem(): void
    {
        $response = $this->get(route('accounts.index'));

        $response->assertSee('Accounts');
    }

    public function testCreateNewAccount(): void
    {
        $excpeted = Account::factory()->make();

        $response = $this->post(route('accounts.store', $excpeted->toArray()));

        $this->assertDatabaseHas('accounts', $excpeted->toArray());
    }

    public function testAccountIsShown(): void
    {
        $account = Account::factory()->create();

        $response = $this->get(route('accounts.index'));

        $response->assertSee($account->description);
    }

    public function testAccountCanBeDeleted(): void
    {
        $account = Account::factory()->create();

        $response = $this->delete(route('accounts.destroy', $account));

        $this->assertDatabaseMissing('accounts', $account->toArray());
    }

    public function testTransactionsAreShown(): void
    {
        $account = Account::factory()->create();
        $category = Category::factory()->create();
        $transaction = Transaction::factory()->create(
            [
                'date' => Carbon::now(),
                'account_id' => $account->id,
            ]
        );

        $response = $this->get(route('accounts.show', Account::first()));
        $response->assertSee($transaction->purpose);
    }

    public function testFilterTransactions(): void
    {
        $account = Account::factory()->create();
        $category = Category::factory()->create();

        // Filter by month
        $transactionToHide = Transaction::factory()->create(
            [
                'date' => now()->subMonth()->format('Y-m-d'),
                'account_id' => $account->id,
            ]
        );
        $transactionToShow = Transaction::factory()->create(
            [
                'date' => now(),
                'account_id' => $account->id,
            ]
        );

        $response = $this->get(route('accounts.show', $account));
        $response->assertSee('Select data');
        $response->assertSee('Month');
        // see current year
        $response->assertSee(now()->format('Y'));
        // see current month
        $response->assertSee(now()->format('n'));
        $response->assertSee($transactionToShow->purpose);
        $response->assertDontSee($transactionToHide->purpose);

        // Filter by year
        $transactionToHide = Transaction::factory()->create(
            [
                'date' => now()->subYear()->format('Y-m-d'),
                'account_id' => $account->id,
            ]
        );
        $transactionToShow = Transaction::factory()->create(
            [
                'date' => now(),
                'account_id' => $account->id,
            ]
        );

        $response = $this->get(route('accounts.show', ['account' => $account, 'filter' => 'year', 'year' => now()->year]));
        $response->assertSee($transactionToShow->purpose);
        $response->assertDontSee($transactionToHide->purpose);
    }
}