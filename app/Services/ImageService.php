<?php


namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic as ImageManager;
use App\Image as ImageModel;

class ImageService
{
    public const IMAGE_DEFAULT = 0;
    public const IMAGE_SMALL_WIDTH = 400;
    public const IMAGE_THUMBNAIL_WIDTH = 200;

    /** @var UploadedFile */
    private $image;
    /** @var array|string|null */
    private $keyName;
    /** @var Collection */
    private $localPaths;
    /** @var Collection */
    private $cdnUrls;
    /** @var string */
    private $error;

    public function __construct()
    {
        $this->localPaths = collect();
        $this->cdnUrls = collect();
    }

    public function processUpload(Request $request): ImageService
    {
        if ($request->hasFile('upload_image')) {
            $this->image = $request->file('upload_image');
        }

        $this->keyName = $request->input('key_name');

        return $this;
    }

    public function save(array $sizes = []): ImageService
    {
        $imageObject = ImageManager::make($this->image);

        // Add the default size to the beginning so that it's processed first
        collect($sizes)->prepend(self::IMAGE_DEFAULT)
            ->each(function ($size) use ($imageObject) {
                if ($size > 0) {
                    $imageObject = $this->resize($imageObject, $size);
                }
                $this->saveLocal($this->image, $imageObject);
            });

        return $this;
    }

    protected function saveLocal(UploadedFile $uploadedFile, Image $imageObject, $newWidth = null): ImageService
    {
        if ($newWidth) {
            $imageObject = $this->resize($imageObject, $newWidth);
        }
        $size = $imageObject->getWidth() . 'x' . $imageObject->getHeight();
        $timestamp = time();
        $ext = $uploadedFile->getClientOriginalExtension();
        $filename = str_replace(".{$ext}", "_{$size}_{$timestamp}.{$ext}", $uploadedFile->getClientOriginalName());
        $destinationPath = public_path('/images') . '/' . $filename;

        try {
            $imageObject->save($destinationPath);

            $this->localPaths->push([
                'path' => $destinationPath,
                'filename' => $filename,
                'size' => $size
            ]);

        } catch (NotWritableException $e) {
            $this->setError($e->getMessage());
        }

        return $this;
    }

    protected function resize(Image $imageObject, int $newWidth): Image
    {
        $defaultWidth = $imageObject->getWidth();
        $defaultHeight = $imageObject->getHeight();

        $ratio = $newWidth / $defaultWidth;
        $newHeight = $defaultHeight * $ratio;

        $imageObject->resize($newWidth, $newHeight);

        return $imageObject;
    }

    public function uploadToS3Bucket(): ImageService
    {
        if ($this->localPaths->isEmpty()) {
            return $this;
        }

        $s3Client = $this->getS3Client();
        $this->localPaths->each(function (array $pathParts) use ($s3Client) {
            $result = $s3Client->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $pathParts['filename'],
                'SourceFile' => $pathParts['path']
            ]);
            if ($result->hasKey('ObjectURL')) {
                $this->cdnUrls->push([
                    'url' => $this->getCdnUrl($result->get('ObjectURL')),
                    'size' => $pathParts['size']
                ]);
            }
        });

        return $this;
    }

    public function persistData(): ImageService
    {
        if ($this->cdnUrls->isEmpty()) {
            return $this;
        }

        $this->cdnUrls->each(function (array $urlArray) {
            $image = new ImageModel();

            $image->key_name = $this->keyName;
            $image->size = $urlArray['size'];
            $image->cdn_url = $urlArray['url'];

            $image->save();
        });

        return $this;
    }

    protected function getS3Client(): S3Client
    {
        return new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
    }

    protected function getCdnUrl(string $objectUrl): string
    {
        $pathInfo = pathinfo($objectUrl);

        return env('CLOUDFRONT_DOMAIN') . '/' . $pathInfo['basename'];
    }

    protected function setError(string $error): void
    {
        $this->error = $error;
    }
}
