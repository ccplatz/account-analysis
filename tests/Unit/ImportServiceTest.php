<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\Category;
use App\Models\ImportRule;
use App\Models\Transaction;
use App\Services\ImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImportServiceTest extends TestCase
{
    use RefreshDatabase;
    private ImportService $importService;
    private array $rawData;
    private array $mappings;
    private Account $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->importService = app(ImportService::class);
        $this->account = Account::factory()->create();
        $this->rawData = [
            [
                'field1' => 'field1',
                'payment_date' => '19.05.2023',
                'payment_partner' => 'Jolly Jumper',
                'iban' => 'DE01234567895445',
                'field2' => 'field2',
                'booking_text' => 'Kreditkarte',
                'purpose_of_booking' => 'Money for nothing',
                'booking_value' => '1.199,56',
                'field3' => 'field3',
                'account_balance' => '-1.987,87',
                'field4' => 'field4',
                'field5' => null,
            ]
        ];
        $this->mappings =
            [
                'date' => 'payment_date',
                'name_other_party' => 'payment_partner',
                'iban_other_party' => 'iban',
                'payment_type' => 'booking_text',
                'purpose' => 'purpose_of_booking',
                'value' => 'booking_value',
                'balance_after' => 'account_balance'
            ];
    }

    public function testThatTransactionsAreStored(): void
    {
        $expected =
            [
                'date' => '2023-05-19',
                'name_other_party' => 'Jolly Jumper',
                'iban_other_party' => 'DE01234567895445',
                'payment_type' => 'Kreditkarte',
                'purpose' => 'Money for nothing',
                'value' => 1199.56,
                'balance_after' => -1987.87,
                'account_id' => $this->account->id
            ];
        $this->importService->storeTransactions($this->rawData, $this->account, $this->mappings);

        $this->assertDatabaseHas('transactions', $expected);
    }

    public function testThatImportRuleWasApplied(): void
    {
        $category = Category::factory()->create();
        $importRule = ImportRule::factory()->create(
            [
                'account_id' => $this->account->id,
                'category_id' => $category->id,
                'field_name' => 'name_other_party',
                'pattern' => 'Jolly Jumper',
            ]
        );
        $expected =
            [
                'date' => '2023-05-19',
                'name_other_party' => 'Jolly Jumper',
                'iban_other_party' => 'DE01234567895445',
                'payment_type' => 'Kreditkarte',
                'purpose' => 'Money for nothing',
                'value' => 1199.56,
                'balance_after' => -1987.87,
                'account_id' => $this->account->id,
                'category_id' => $category->id
            ];
        $this->importService->storeTransactions($this->rawData, $this->account, $this->mappings);

        $this->assertDatabaseHas('transactions', $expected);
    }

    public function testThatImportRuleWithLowerSequenceWasApplied(): void
    {
        $categoryHigh = Category::factory()->create();
        $categoryLow = Category::factory()->create();
        $importRuleHigh = ImportRule::factory()->create(
            [
                'account_id' => $this->account->id,
                'category_id' => $categoryHigh->id,
                'field_name' => 'name_other_party',
                'pattern' => 'Jolly Jumper',
                'sequence' => 5,
            ]
        );
        $importRuleLow = ImportRule::factory()->create(
            [
                'account_id' => $this->account->id,
                'category_id' => $categoryLow->id,
                'field_name' => 'name_other_party',
                'pattern' => 'Jolly Jumper',
                'sequence' => 4,
            ]
        );
        $expected =
            [
                'date' => '2023-05-19',
                'name_other_party' => 'Jolly Jumper',
                'iban_other_party' => 'DE01234567895445',
                'payment_type' => 'Kreditkarte',
                'purpose' => 'Money for nothing',
                'value' => 1199.56,
                'balance_after' => -1987.87,
                'account_id' => $this->account->id,
                'category_id' => $categoryLow->id
            ];
        $this->importService->storeTransactions($this->rawData, $this->account, $this->mappings);

        $this->assertDatabaseHas('transactions', $expected);
    }
}