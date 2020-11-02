<?php


use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Orchestra\Testbench\TestCase;
use victorycto\ImageStore\Services\StorageService;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use victorycto\ImageStore\Models\Image;

class StorageServiceTest extends TestCase
{
    //Store the image to S3 or GCP cloud storage and create a public url - ideally with a CDN frontend
    //Save the image data to a table of your design in the local mysql database
    //Make the image available to the frontend

    //Test this package on new laravel application

    protected $uploadedFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploadedFile = UploadedFile::fake()->image('photo.jpg');
    }

    /** @test */
    public function it_can_provide_storage_instance()
    {
        $service = new StorageService;
        $this->assertInstanceOf(FilesystemAdapter::class, $service->getStorage());

        $storage = Storage::disk('local');
        $service->setStorage($storage);
        $this->assertSame($storage, $service->getStorage());
    }

    /** @test */
    public function it_can_upload_image()
    {
        $service = new StorageService;
        $path = $this->uploadedFile->hashName('images');
        $result = $service->uploadImage(InterventionImage::make($this->uploadedFile), $path);

        $this->assertTrue($result);
    }

    //service save cloud url to database
    //service retrieves saved images
}
