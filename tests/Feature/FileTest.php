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
    use RefreshDatabase;

    public function testThatImportedFilesDoNotGetALinkToImport(): void
    {
        $file = File::factory()->create();
        $file->setToImported();
        $response = $this->get(route('files.index'));

        $response->assertSee('bi-database-check');
    }

    public function testThatNotImportedFilesGetALinkToImport(): void
    {
        $file = File::factory()->create();

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

    public function testDeleteFile()
    {
        Storage::fake('local');

        $response = $this->post(route('files.store'), [
            'file' => UploadedFile::fake()->create('test.csv')
        ]);

        $file = File::where('name', 'test.csv')->first();

        Storage::disk('local')->assertExists($file->path);

        $response = $this->get(route('files.delete', $file));

        Storage::disk('local')->assertMissing($file->path);
    }
}