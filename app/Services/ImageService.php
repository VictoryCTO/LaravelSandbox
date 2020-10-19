<?php


namespace App\Services;

use Illuminate\Http\Request;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

class ImageService
{
    public function showForm()
    {
    }

    public function processUpload(Request $request)
    {
        if ($request->hasFile('upload_image')) {
            $image = $request->file('upload_image');
            $ext = $image->getClientOriginalExtension();
            $timestamp = time();
            $filename = str_replace(".{$ext}", "_{$timestamp}.{$ext}", $image->getClientOriginalName());
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
        }
    }
}
