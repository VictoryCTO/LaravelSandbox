<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Image;

class ResizeController extends Controller
{
    function index()
    {
     return view('resize');
    }

    function resize_image(Request $request)
    {
     $this->validate($request, [
      'image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
     ]);

     $image = $request->file('image');

     $resized_images = array();

     $original_image = 'original_' . time() . '.' . $image->getClientOriginalExtension();
     $thumbnail_image = 'thumbnail_' . time() . '.' . $image->getClientOriginalExtension();
     $small_image = 'small_' . time() . '.' . $image->getClientOriginalExtension();
     $large_image = 'large_' . time() . '.' . $image->getClientOriginalExtension();

     $destinationPath = public_path('/resized_images');

     $resize_image = Image::make($image->getRealPath());

     $resize_image->resize(150, 150, function($constraint){
      $constraint->aspectRatio();
     })->save($destinationPath . '/' . $thumbnail_image);

     $resized_images[] = $thumbnail_image;

     $resize_image->resize(270, 270, function($constraint){
        $constraint->aspectRatio();
       })->save($destinationPath . '/' . $small_image);

       $resized_images[] = $small_image;

       $resize_image->resize(500, 500, function($constraint){
        $constraint->aspectRatio();
       })->save($destinationPath . '/' . $large_image);

       $resized_images[] = $large_image;

     $destinationPath = public_path('/images');

     $image->move($destinationPath, $original_image);

     return back()
       ->with('success', 'Image Upload successful')
       ->with('original_image', $original_image)
       ->with('resized_images', $resized_images);

    }
}