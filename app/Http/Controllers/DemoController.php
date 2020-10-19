<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Joeyfrich\ImageResizer\ImageResizer;
use Joeyfrich\ImageResizer\FileResource;

class DemoController extends Controller
{
  public function home() {
    return view('welcome')
      ->with('recent_images', FileResource::fetchRecent(20))
      ->with('total_qty', FileResource::totalQty());
  }
  
  public function uploadImage(Request $request) {
    list($featured_resource, $quantity_added, $any_error, $error_messages) = ImageResizer::resizeAndSaveImages($request->allFiles()['demoFiles']);
    
    array_push($error_messages, number_format($quantity_added).' images were added');
    
    return view('image_uploaded')
      ->with('featured_resource', $featured_resource)
      ->with('recent_images', FileResource::fetchRecent(20))
      ->with('total_qty', FileResource::totalQty())
      ->with('messages', $error_messages);
  }
  
  public function deleteImage($resource_id) {
    $im = FileResource::where('resource_id', $resource_id)->first();
    
    if ($im) {
      $im->deleteImage();
      
      return \Redirect::to('/');
    }
  }
  
  public function deleteAll() {
    foreach (FileResource::fetchRecent(FileResource::totalQty()) as $im) {
      $im->deleteImage();
    }
    
    return \Redirect::to('/');
  }
  
  public function cloudImage($resource_key, Request $request) {
    $image_resource = FileResource::fetchByKey($resource_key);
    $size_prefix = $request->all()['size'] ?? null;
    if (empty(FileResource::imageSizes()[$size_prefix])) $size_prefix = null;
    
    if ($image_resource) {
      if ($image_resource->saved_to_aws) {
        return \Storage::disk('s3')->response($image_resource->primary_aws_path);
      }
      else if ($image_resource->locally_saved) {
        return response()->file("storage/".$image_resource->getFname($size_prefix));
      }
      else return view('simple_message')
        ->with('message', 'That image is not available.');
    }
    else return view('simple_message')
      ->with('message', 'There was an error loading that image');
  }
}
