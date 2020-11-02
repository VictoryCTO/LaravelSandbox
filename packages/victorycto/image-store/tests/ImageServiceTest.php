<?php

use Illuminate\Http\UploadedFile;
use Orchestra\Testbench\TestCase;
use victorycto\ImageStore\Services\ImageService;

class ImageServiceTest extends TestCase
{

    protected $options;
    protected $width;
    protected $uploadedFile;


    protected function setUp(): void
    {
        parent::setUp();

        $this->options = config('imagestore.options');
        $this->width = !empty($this->options['small']['size']) ? $this->options['small']['size'] : 600;
        $this->uploadedFile = UploadedFile::fake()->image('photo.jpg');
    }

    /** @test */
    public function it_can_accept_uploaded_instance()
    {
        $handler = ImageService::takeFile($this->uploadedFile);

        $this->assertSame($this->uploadedFile, $handler->getFile());
    }

    /** @test */
    public function it_trows_exception_when_receives_not_image()
    {
        $this->expectException(InvalidArgumentException::class);

        ImageService::takeFile(UploadedFile::fake()->create('file.txt'));
    }

    /** @test */
    public function it_can_manipulate_with_image()
    {
        $resizedImage = ImageService::takeFile($this->uploadedFile)->resize($this->width);
        //todo: make test for compression
        $compressedImage = ImageService::takeFile($this->uploadedFile)->compressQuality();

        $this->assertEquals($this->width, $resizedImage->getImage()->getWidth());
    }

}
