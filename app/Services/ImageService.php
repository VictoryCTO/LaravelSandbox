<?php


namespace App\Services;


use App\Image;
use Illuminate\Support\Facades\App;

class ImageService
{
    public function upload($path, $filename)
    {
        $s3 = App::make('aws')->createClient('s3');
        $key = time()."_".$filename;
        $s3->putObject([
            'Bucket'     => config('aws.credentials.bucket'),
            'Key'        => $key,
            'SourceFile' => $path.$filename,
        ]);
        $image = new Image([
                'name'    => $filename,
                'location' => $key,
                'parent_id' => 0
            ]
        );
        $image->save();
    }

    public function resize()
    {

    }

    public function get()
    {

    }

    public function save()
    {

    }
}
