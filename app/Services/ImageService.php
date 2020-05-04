<?php


namespace App\Services;


use App\Image;
use Illuminate\Support\Facades\App;

class ImageService
{
    /**
     * Uploads source image and it's thumbnails to s3 bucket
     * @param $path
     * @param $parent
     */
    public function upload($path, $parent)
    {
        $s3Client = App::make('aws')->createClient('s3');

        $imagesToUpload = Image::where('parent_id','=', $parent->id)
            ->orWhere('id', '=',$parent->id)
            ->get();

        foreach($imagesToUpload as $image) {
            $s3Client->putObject([
                'Bucket'     => config('aws.credentials.bucket'),
                'Key'        => $image->id,
                'SourceFile' => $path.$image->name,
            ]);
        }
    }

    /**
     * Generates thumbnails from source image
     * @param $localStoragePath
     * @param $image
     */
    public function resize($localStoragePath, $image)
    {
        $source = $localStoragePath . $image->name;

        //get size of existing images for imagecopyresampled method later
        $size = getimagesize($source);
        $width = $size[0];
        $height = $size[1];

        //get extention of source image
        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        // Create new resized image
        $fn = "imagecreatefrom" . ($ext == "jpg" ? "jpeg" : $ext);
        $original = $fn($source);

        //todo: put these in config file
        $new_sizes = [20, 40, 100];
        foreach ($new_sizes as $new_size) {
            $resize = imagecreatetruecolor($new_size, $new_size);
            imagecopyresampled($resize, $original, 0, 0, 0, 0, $new_size, $new_size, $width, $height);

            // Save resized to file
            $fn = "image" . ($ext == "jpg" ? "jpeg" : $ext);
            $filename = $new_size . "x" . "$new_size-$image->name";
            $fn($resize, $localStoragePath . $filename) ? [' path' => $localStoragePath, 'filename' => $filename] : false;
            $thumbnail = new Image([
                    'name' => $filename,
                    'parent_id' => $image->id
                ]
            );
            $thumbnail->save();
        }
    }

}
