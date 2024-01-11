<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\ImportRule;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImportRuleTest extends TestCase
{
    use RefreshDatabase;

    public function testThatRuleNotAppliesOnTransactionWithDifferentAccount(): void
    {
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();
        $category = Category::factory()->create();
        $rule = ImportRule::factory()->create(
            [
                'account_id' => $account1->id,
                'category_id' => $category->id,
                'pattern' => 'test123',
                'exact_match' => 1,
            ]
        );
        $transaction = Transaction::factory()->make(
            [
                'account_id' => $account2->id,
                $rule->field_name => $rule->pattern,
            ]
        );

        $this->assertFalse($rule->applies($transaction));
    }

    public function testThatRuleWithExactMatchAppliesOnTransaction(): void
    {
        $account = Account::factory()->create();
        $category = Category::factory()->create();
        $rule = ImportRule::factory()->create(
            [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'pattern' => 'test123',
                'exact_match' => 1,
            ]
        );
        $transaction = Transaction::factory()->make(
            [
                'account_id' => $account->id,
                $rule->field_name => $rule->pattern,
            ]
        );

        $this->assertTrue($rule->applies($transaction));
    }

    public function testThatRuleWithNotExactMatchNotAppliesOnTransaction(): void
    {
        $account = Account::factory()->create();
        $category = Category::factory()->create();
        $rule = ImportRule::factory()->create(
            [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'pattern' => 'test123',
                'exact_match' => 1,
            ]
        );
        $transaction = Transaction::factory()->make(
            [
                'account_id' => $account->id,
                $rule->field_name => $rule->pattern . '4',
            ]
        );

        $this->assertFalse($rule->applies($transaction));
    }

    public function testThatRuleWithPartialMatchAppliesOnTransaction(): void
    {
        $account = Account::factory()->create();
        $category = Category::factory()->create();
        $rule = ImportRule::factory()->create(
            [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'pattern' => 'test123',
                'exact_match' => 0,
            ]
        );
        $transaction = Transaction::factory()->make(
            [
                'account_id' => $account->id,
                $rule->field_name => $rule->pattern . '4',
            ]
        );

        $this->assertTrue($rule->applies($transaction));
    }

    public function testThatRuleWithNoPartialMatchNotAppliesOnTransaction(): void
    {
        $account = Account::factory()->create();
        $category = Category::factory()->create();
        $rule = ImportRule::factory()->create(
            [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'pattern' => 'test123',
                'exact_match' => 0,
            ]
        );
        $transaction = Transaction::factory()->make(
            [
                'account_id' => $account->id,
                $rule->field_name => 'abc1234',
            ]
        );

        $this->assertFalse($rule->applies($transaction));
    }

    public function testThatRuleUpdatesTransactionCategory(): void
    {
        $account = Account::factory()->create();
        $category = Category::factory()->create();
        $rule = ImportRule::factory()->create(
            [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'pattern' => 'test123',
                'exact_match' => 0,
            ]
        );
        $transaction = Transaction::factory()->create(
            [
                'account_id' => $account->id,
            ]
        );

        $rule->apply($transaction);

        $this->assertEquals($category->id, $transaction->category->id);
    }


}
