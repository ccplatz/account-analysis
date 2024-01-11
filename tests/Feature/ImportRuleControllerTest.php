<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\ImportRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImportRuleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testThatImportRulesAreOnTheMenu(): void
    {
        $response = $this->get('/');

        $response->assertSee('Import rules');
    }

    public function testThatImportRulesAreReachable(): void
    {
        $response = $this->get('/import-rules');

        $response->assertSee('Import Rules');
    }

    public function testThatUserCanAddImportRule(): void
    {
        $account = Account::factory()->create();
        $category = Category::factory()->create();
        $importRule = ImportRule::factory()->make();

        $response = $this->post(route('import-rules.store'), $importRule->toArray());

        $response->assertRedirect(route('import-rules.index'));
        $this->assertDatabaseHas('import_rules', $importRule->toArray());
    }
}
