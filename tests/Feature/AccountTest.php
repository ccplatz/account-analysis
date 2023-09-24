<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{

    public function testAccountsIsMenuItem(): void
    {
        $response = $this->get(route('accounts.index'));

        $response->assertSee('Accounts');
    }
}