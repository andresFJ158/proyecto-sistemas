<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EvidenceUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_attach_evidence(): void
    {
        Storage::fake('public');
        $student = $this->user();
        $request = $this->serviceRequest($student);
        Sanctum::actingAs($student);

        $response = $this->postJson("/api/v1/requests/{$request->id}/evidences", [
            'file' => UploadedFile::fake()->image('evidencia.jpg'),
        ]);

        $response->assertCreated();
        Storage::disk('public')->assertExists($response->json('data.path'));
    }
}
