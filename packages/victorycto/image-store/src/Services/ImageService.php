<?php

namespace victorycto\ImageStore\Services;

use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic as ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    protected $fileInstance;
    protected $image;

    protected static $allowed_mime_types = [
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/svg'
    ];

    public static function takeFile(UploadedFile $uploadedFile): ImageService
    {
        $service = new self();
        $service->setFileInstance($uploadedFile);

        return $service;
    }


    public function getFile(): ?UploadedFile
    {
        return $this->fileInstance;
    }

    public function getImage()
    {
        return $this->image ?? $this->image = $this->makeImage($this->getFile());
    }

    protected function makeImage($source)
    {
        return ImageManager::make($source);
    }


    public function setFileInstance(UploadedFile $file)
    {
        if (!in_array($file->getMimeType(), self::$allowed_mime_types)) {
            throw new \InvalidArgumentException(
                'Invalid file MIME-type. Service accept only images with following MIME-types: '
                . implode(', ', self::$allowed_mime_types)
            );
        }

        $this->fileInstance = $file;
    }



    public function processImageOptions(array $options): Image
    {
        if ($options['size']) {
           $this->resize($options['size']);
        }

        if ($options['quality']) {
            $this->compressQuality('jpg', $options['quality']);
        }

        return $this->getImage();
    }

    public function resize($size = null): self
    {
        $this->image = $this->getImage()
            ->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            });

        return $this;
    }

    public function compressQuality($format = null, $quality = 100): self
    {
        $this->image = $this->makeImage($this->getImage()->encode($format, $quality));

        return $this;
    }
}
