<?php

namespace Tests\Feature;

use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FileTest extends TestCase
{
    private File $file;

    public function setup(): void
    {
        parent::setup();
        $this->file = File::factory()->create();
    }

    public function testThatImportedFilesDoNotGetALinkToImport(): void
    {
        $this->file->setToImported();
        $response = $this->get(route('files.index'));

        $response->assertSee('bi-database-check');
    }

    public function testThatNotImportedFilesGetALinkToImport(): void
    {
        $response = $this->get(route('files.index'));

        $response->assertSee('bi-database-add');
    }
}