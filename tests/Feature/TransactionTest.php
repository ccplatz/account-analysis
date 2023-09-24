<?php

namespace Tests\Feature;

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
        $this->transaction = Transaction::first();
    }

    public function testDeleteTransaction(): void
    {
        $response = $this->post(route('transactions.destroy', $this->transaction));

        $this->assertDatabaseMissing('transactions', $this->transaction->toArray());
    }
}