<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ItsIrv\ImageUploader\imageuploader as ImageUploader;
use ItsIrv\ImageUploader\Image;

class ImageController extends Controller
{
    public function Upload(Request $request, ImageUploader $imageUploader)
    {
        $image = $imageUploader->getImageOrFail('image');

        $variations = $image->createSizeVariations(
            [100, 100], // thumbnail,
            [400, 400], // small
            [$image->width(), $image->height()], // full
        );

        // We want batches to start with the same base name.
        $baseName = $variations[0]->generateRandomName();
        $baseName = str_replace('.png', '', $baseName);

        $imagesCreated = [];

        foreach($variations as $image) {
            $storageObject = $imageUploader->uploadImageToDrive(
                $image,
                $image->nameWithSizeAppended($baseName)
            );

            if ($storageObject) {
                $image->name = $storageObject->name();
                $image->bucket = $storageObject->identity()['bucket'];

                $image->save();

                array_push($imagesCreated, $image->name);
            }
        }

        return response()->json(['message' => 'OK', 'data' => $imagesCreated]);
    }

    public function GetAll()
    {
        $data = [
            'images' => Image::limit(10)->get()
        ];

        return view('imageloader::images', $data);
    }
}
