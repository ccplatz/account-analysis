<?php

namespace Tests\Feature;

use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function testFilesIsMenuItem(): void
    {
        $response = $this->get(route('files.index'));

        $response->assertSee('Files');
    }

    public function testFileUpload()
    {
        Storage::fake('local');

        $response = $this->post(route('files.store'), [
            'file' => UploadedFile::fake()->create('test.csv')
        ]);

        $file = File::where('name', 'test.csv')->first();

        Storage::disk('local')->assertExists($file->path);
    }
}