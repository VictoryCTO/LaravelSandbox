<?php


namespace victorycto\ImageStore;


use Illuminate\Http\UploadedFile;
use victorycto\ImageStore\Models\Image;
use victorycto\ImageStore\Services\ImageService;
use victorycto\ImageStore\Services\StorageService;

class ImageStore
{
    public static function upload(UploadedFile $file)
    {
        $attributes = [];
        $storageService = new StorageService;

        foreach (config('imagestore.options') as $sizeType => $options) {

            if (!empty($options['model_attribute'])) {
                $image = ImageService::takeFile($file)->processImageOptions($options);

                $path = $file->hashName($sizeType);
                if ($storageService->uploadImage($image, $path)) {
                    $attributes[$options['model_attribute']] = $storageService->getStorage()->url($path);
                }
            }
        }

        return Image::create($attributes);
    }

    protected static function store()
    {

    }
}
