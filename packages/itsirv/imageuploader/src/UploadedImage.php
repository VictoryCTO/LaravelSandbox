<?php

namespace itsirv\imageuploader;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use ItsIrv\ImageUploader\Exceptions\InvalidUploadedImageException;
use ItsIrv\ImageUploader\UploadedImageVariation;
use itsirv\imageuploader\Image;

/**
 * This class helps us take an UploadedFile object, and turn it into an image resource that we can manipulate.
 */
class UploadedImage extends Image
{
    /**
     * Default amount of bytes to use when generating names.
     */
    private const DEFAULT_NAME_LENGTH = 10;

    /**
     * Eloquent table name.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * Parsed image.
     *
     * @var resource
     */
    private $image;

    /**
     * Raw UploadedFile.
     */
    private UploadedFile $file;

    /**
     * Image width.
     */
    private int $width = 0;

    /**
     * Image height.
     */
    private int $height = 0;

    /**
     * Creates a new image resource from an UploadedFile, or uses the given image resource.
     *
     * @param UploadedFile $uploadedFile Raw UploadedFile.
     * @param resource $image Image resource to use.
     */
    public function __construct(UploadedFile $uploadedFile, $image = null)
    {
        if ($image && get_resource_type($image) !== 'gd') {
            throw new InvalidUploadedImageException('invalid image');
        }

        $uploadedImage = $this;
        $uploadedImage->file = $uploadedFile;
        $uploadedImage->image = $image ?? imagecreatefromstring(file_get_contents($uploadedFile->path()));

        if (!$uploadedImage->image) {
            throw new InvalidUploadedImageException('invalid file');
        }

        $uploadedImage->width = imagesx($uploadedImage->image);
        $uploadedImage->height = imagesy($uploadedImage->image);
    }

    /**
     * Resizes image to specified dimensions.
     *
     * @param int $width Image width.
     * @param int $height Image height.
     * @return resource
     */
    public function resizeImage(int $width, int $height)
    {
        return imagescale($this->image, $width, $height);
    }

    /**
     * Gets image width.
     *
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Gets image height.
     *
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Gets image resource.
     *
     * @return resource
     */
    public function image()
    {
        return $this->image;
    }

    /**
     * Gets original file upload object.
     *
     * @return UploadedFile
     */
    public function file(): UploadedFile
    {
        return $this->file;
    }

    /**
     * Returns the image resized into the given sizes.
     *
     * @param array ...$sizes All size variations to make.
     * @return array
     */
    public function createSizeVariations(array ...$sizes): array
    {
        $uploadedImage = $this;
        $variations = [];
        $sizesCount = count($sizes);

        if ($sizesCount > 0) {
            for($i = 0; $i < $sizesCount; $i++) {
                $size = $sizes[$i];
                $width = $size[0] ?? 0;
                $height = $size[1] ?? 0;

                $resizedImage = $uploadedImage->resizeImage($width, $height);

                $variations[$i] = $uploadedImage->createFromVariation($resizedImage);
            }
        }

        return $variations;
    }

    /**
     * Generates a random image name or uses the base seed, and appends the image dimensions.
     *
     * @param string $name Base name to use.
     * @param integer $length Length to pass to random_bytes().
     * @return string
     */
    public function nameWithSizeAppended(string $name = null, int $length = self::DEFAULT_NAME_LENGTH): string
    {
        $uploadedImage = $this;
        $name = $name ?? str_replace('.png', '', $uploadedImage->generateRandomName($length));

        return "{$name}_{$uploadedImage->width}_{$uploadedImage->height}.png";
    }

    /**
     * Generates a random image name.
     *
     * @param integer $length Length to pass to random_bytes().
     * @return string
     */
    public function generateRandomName(int $length = self::DEFAULT_NAME_LENGTH): string
    {
        return bin2hex(random_bytes($length)) . '.png';
    }

    /**
     * Gets image as a PNG image string.
     *
     * @return string
     */
    public function toBuffer()
    {
        $uploadedImage = $this;

        ob_start();

        imagepng($uploadedImage->image());

        $imageBuffer = ob_get_contents();

        ob_end_clean();

        return $imageBuffer;
    }

    /**
     * Creates an UploadedImage object from a GD image resource.
     * This function assumes that we want to maintain the original UploadedFile state.
     *
     * @param resource $variation GD resource.
     * @return UploadedImage
     */
    private function createFromVariation($variation): UploadedImage
    {
        return new self($this->file, $variation);
    }
}
