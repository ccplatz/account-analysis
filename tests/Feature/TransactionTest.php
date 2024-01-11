<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    private Transaction $transaction;

    public function setUp(): void
    {
        parent::setUp();
        $account = Account::factory()->create();
        $this->transaction = Transaction::factory()->create(['account_id' => $account->id]);
    }

    public function testDeleteTransaction(): void
    {
        $response = $this->post(route('transactions.destroy', $this->transaction));

        $this->assertDatabaseMissing('transactions', $this->transaction->toArray());
    }

    public function testUpdateCategoryViaApi(): void
    {
        $newCategory = Category::where('id', '!=', $this->transaction->category_id)->first();

        $response = $this->post(route('api.transactions.update', $this->transaction), ['category' => $newCategory->id]);

        $this->assertEquals($newCategory->id, $this->transaction->refresh()->category_id);
    }
}