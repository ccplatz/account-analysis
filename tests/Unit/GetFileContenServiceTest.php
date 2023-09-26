<?php

namespace Tests\Unit;

use App\Models\File;
use App\Services\GetFileContentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GetFileContenServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_that_return_value_is_array(): void
    {
        $expected = ['first line', 'second line', 'third line'];
        $content = Arr::join($expected, PHP_EOL);
        $file = File::factory()->create();
        Storage::shouldReceive('get')
            ->andReturn($content);

        $getFileContentService = app(GetFileContentService::class);
        $lines = $getFileContentService->getLinesFromFile($file);

        $this->assertIsArray($lines);
        $this->assertEquals($lines, $expected);
    }
}