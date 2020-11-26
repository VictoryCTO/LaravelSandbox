<?php

namespace itsirv\imageuploader;

use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;
use ItsIrv\ImageUploader\UploadedImage;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\StorageObject;


/**
 * This class helps us get images from the HTTP request, and upload them to Goodle Cloud Drive.
 */
class imageuploader
{
    /**
     * HTTP request object.
     */
    private Request $request;

    /**
     * Google Drive bucket name.
     */
    private string $bucketName;

    /**
     *  Google Drive Project Id.
     */
    private string $projectId;

    public function __construct()
    {
        $this->bucketName = env('GOOGLE_APPLICATION_BUCKET');
        $this->projectId = env('GOOGLE_APPLICATION_ID');
        $this->request = app(Request::class);
    }

    /**
     * Validate and return an image from input.
     * Image must be JPEG, BMP, or PNG format, and less than 5mb in size.
     *
     * @param string $fieldName Form name of the image.
     * @return UploadedImage
     */
    public function getImage(string $fieldName): ?UploadedImage
    {
        $imageUploader = $this;

        $uploadedFile = $imageUploader->request->validate([
            $fieldName => 'image|mimes:jpeg,bmp,png|max:5120'
        ]);

        if (! $uploadedFile) {
            return null;
        }

        return new UploadedImage($uploadedFile[$fieldName]);
    }

    /**
     * Validate and return an image from input or abort with a 404.
     *
     * @param string $fieldName Form name of the image.
     * @return UploadedImage
     */
    public function getImageOrFail(string $fieldName): ?UploadedImage
    {
        $imageUploader = $this;
        $uploadedImage = $imageUploader->getImage($fieldName);

        if (!$uploadedImage) {
            abort(400, 'Image not found.');
        }

        return $uploadedImage;
    }

    /**
     * Uploads image to Google Cloud Drive.
     *
     * @param UploadedImage $uploadedImage Image to upload.
     * @param string $name Name to upload the image as.
     * @return StorageObject
     */
    public function uploadImageToDrive(UploadedImage $uploadedImage, string $name = null): StorageObject
    {
        $imageUploader = $this;

        $imageBuffer = $uploadedImage->toBuffer();

        $storage = new StorageClient([
            'projectId' => $imageUploader->projectId
        ]);

        $bucket = $storage->bucket($imageUploader->bucketName);

        $object = $bucket->upload($imageBuffer, [
            'name' => $name ?? "{$uploadedImage->nameWithSizeAppended($name)}"
        ]);

        return $object;
    }
}
