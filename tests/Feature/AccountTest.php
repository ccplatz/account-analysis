<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{
    private Account $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->account = Account::factory()->create();
    }

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
        $response = $this->get(route('accounts.index'));

        $response->assertSee($this->account->description);
    }

    public function testAccountCanBeDeleted(): void
    {
        $response = $this->delete(route('accounts.destroy', $this->account));

        $this->assertDatabaseMissing('accounts', $this->account->toArray());
    }

    public function testFilterTransactions(): void
    {
        $transactionToHide = Transaction::factory()->create(
            [
                'date' => now()->subMonth()->format('Y-m-d'),
                'account_id' => $this->account->id,
            ]
        );
        $transactionToShow = Transaction::factory()->create(
            [
                'date' => now(),
                'account_id' => $this->account->id,
            ]
        );

        $response = $this->get(route('accounts.show', $this->account));
        $response->assertSee('Select data');
        // see at least current year
        $response->assertSee(now()->format('Y'));
        // see at least current month
        $response->assertSee(now()->format('n'));
        $response->assertSee($transactionToShow->purpose);
        $response->assertDontSee($transactionToHide->purpose);
    }

    public function testAddNewMonth(): void
    {
        $response = $this->get(route('accounts.show', $this->account));
        $response->assertSee('New month');
    }
}