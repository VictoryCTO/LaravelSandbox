<?php


namespace victorycto\ImageStore\Services;


use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\FileHelpers;
use Intervention\Image\Image;

class StorageService
{
    use FileHelpers;

    protected $storageInstance;

    /**
     * @return FilesystemAdapter
     */
    public function getStorage(): FilesystemAdapter
    {
        return $this->storageInstance ?? \Storage::cloud();
    }

    /**
     * @param FilesystemAdapter $filesystemAdapter
     */
    public function setStorage(FilesystemAdapter $filesystemAdapter):void
    {
       $this->storageInstance = $filesystemAdapter;
    }


    public function uploadImage(Image $image, $path)
    {
        return $this->getStorage()->put($path, $image->stream(), 'public');
    }
}
