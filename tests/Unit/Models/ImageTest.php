<?php

namespace Tests\Unit\Models;

use App\Image;
use Tests\TestCase;

class ImageTest extends TestCase
{
    private $image;

    public function setUp(): void
    {
        parent::setUp();
        $this->image = new Image([
            'key_name' => 'My Name',
            'size' => '400x600',
            'cdn_url' => 'https://d2w9f3kbrlp6tb.cloudfront.net/MyImage.png',
        ]);
    }

    public function testMemberHasName(): void
    {
        self::assertEquals('My Name', $this->image->key_name);
    }

    public function testMemberHasSize(): void
    {
        self::assertEquals('400x600', $this->image->size);
    }

    public function testMemberHasCdnUrl(): void
    {
        self::assertStringContainsString('MyImage.png', $this->image->cdn_url);
    }

}
