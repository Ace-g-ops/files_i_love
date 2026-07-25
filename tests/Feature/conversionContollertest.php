<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class conversionContollertest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_convert(): void
    {
        Storage::fake();

        $file = $uploadedFile::fake()->create('test.pdf');

        $response = $this->post('/convert',[

            'file' => $file,
            'format' => docx,
        ]);

        $response->assertStatus(200);
    }
}
