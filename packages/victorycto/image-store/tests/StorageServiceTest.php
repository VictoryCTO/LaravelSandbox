<?php


use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Orchestra\Testbench\TestCase;
use victorycto\ImageStore\Services\StorageService;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use victorycto\ImageStore\Models\Image;

class StorageServiceTest extends TestCase
{
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

        $storage = Storage::cloud();
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
}
