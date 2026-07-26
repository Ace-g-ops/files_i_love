<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class conversionContollertest extends TestCase
{

    use RefreshDatabase; //refresh database between tests so they dont interfere.
    /**
     * A basic feature test example.
     */
    public function test_document_conversion_and_dispatch_job(): void
    {
        Storage::fake('local');
        Queue::fake();

        $file = UploadedFile::fake()->create('test.pdf', 500, 'application/pdf'); //creates a dummy file instance

        $reponse = $this->postJson('/api/convert', [

            'file' => $file,
            'target_format' => 'docx',
        ]); 

        $reponse->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'message',
            ]);

        $this->assertDatabaseHas('conversions', [
            'original_filename' => 'test.pdf',
            'source_format' => 'pdf',
            'target_format' => 'docx',
            'status' => 'pending',
        ]);

        Queue::assertPushed(ConvertDocumentJob::class); // this confirms that the controlle actually dispatched the right job.

    }

    public function test_invalid_conversion_request(): void
    {
        $file = UploadedFile::fake()->create('test.pdf', 500, 'application/pdf');

        $response = $this-postJson('/api/convert', [
            'file' => $file,
            'target_format' => 'mp.3',
        ]);

        $response->assertStatuus(422)->assertJson([
            'erorr' => 'Unsupported Conversion',
        ]);
    }

}
