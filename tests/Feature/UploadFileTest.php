<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\UploadedFile;
use itsirv\imageuploader\Image;
use Tests\TestCase;

class UploadFileTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    private function postToImageApi($fakeImage = null): TestResponse
    {
        $payload = [];

        if ($fakeImage) {
            $payload['image'] = $fakeImage;
        }

        return $this->json('POST', '/api/images', $payload);
    }

    public function testImageUploadWithNoImage(): void
    {
        $response = $this->postToImageApi(null);

        $response->assertStatus(400);
    }

    public function testImageUploadWithInvalidImage(): void
    {
        $response = $this->postToImageApi(UploadedFile::fake()->image('avatar.jpg', 800, 800) . 'invalid');

        $response->assertStatus(422);
    }

    public function testImageUploadWithInvalidImageMimeType(): void
    {
        $response = $this->postToImageApi(UploadedFile::fake()->image('avatar.gif'));

        $response->assertStatus(422);
    }

    public function testImageUploadWithImage(): void
    {
        $response = $this->postToImageApi(UploadedFile::fake()->image('avatar.jpg', 800, 800));

        $response->assertStatus(200);
    }
}
