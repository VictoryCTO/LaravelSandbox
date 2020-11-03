<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;
use Tests\CreatesApplication;
use victorycto\ImageStore\Models\Image;

class ImageTableTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $full_url;
    protected $small_url;
    protected $thumbnail_url;

    protected $image_options;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../../database/factories');

        $faker = Faker\Factory::create();
        $this->image_options = config('imagestore.options');

        $this->full_url = $faker->imageUrl();
        $this->small_url = $faker->imageUrl($this->image_options['small']['size']);
        $this->thumbnail_url = $faker->imageUrl($this->image_options['thumbnail']['size']);
    }


    /** @test */
    public function an_image_store_urls_correctly()
    {
        $image = factory(Image::class)->create([
            'full_url' => $this->full_url,
            'small_url' => $this->small_url,
            'thumbnail_url' => $this->thumbnail_url
        ]);

        $this->assertEquals($this->full_url, $image->full_url);
        $this->assertEquals($this->small_url, $image->small_url);
        $this->assertEquals($this->thumbnail_url, $image->thumbnail_url);
    }

}
