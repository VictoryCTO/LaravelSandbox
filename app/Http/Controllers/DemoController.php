<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Joeyfrich\ImageResizer\ImageResizer;
use App\FileResource;

class DemoController extends Controller
{
  public function home() {
    return view('welcome')
      ->with('recent_images', FileResource::fetchRecent(5))
      ->with('total_qty', FileResource::totalQty());
  }
  
  public function uploadImage(Request $request) {
    $acceptable_extensions = ["jpg","png"];
    $acceptable_mime_types = ["image/jpeg", "image/png"];
    
    $original_image_name_parts = explode(".", $request->file('demoFile')->getClientOriginalName());
    $image_extension = strtolower($original_image_name_parts[count($original_image_name_parts)-1]);
    $mime_type = $request->file('demoFile')->getMimeType();
    
    if (in_array($image_extension, $acceptable_extensions)) {
      if (in_array($mime_type, $acceptable_mime_types)) {
        if ($raw_image = @file_get_contents($request->all()['demoFile'])) {
          $hash_identifier = ImageResizer::hashImage($raw_image);
          $filesize_bytes = strlen($raw_image);
          
          list($image_resource, $is_new) = FileResource::createOrFetchResource($hash_identifier, "image", $image_extension, $filesize_bytes);
          
          $image_resource->saveRawFile($raw_image);
          
          return view('image_uploaded')
            ->with('image_resource', $image_resource)
            ->with('recent_images', FileResource::fetchRecent(5))
            ->with('total_qty', FileResource::totalQty());
        }
        else {
          return view('simple_message')
            ->with('message', 'Server failed to read the image.')
            ->with('next_action_label', 'Try again')
            ->with('next_action_url', '/');
        }
      }
      else {
        return view('simple_message')
          ->with('message', 'The mime type for the image you uploaded is not allowed.')
          ->with('next_action_label', 'Try again')
          ->with('next_action_url', '/');
      }
    }
    else {
      return view('simple_message')
        ->with('message', 'The file extension for the image you uploaded is not allowed.')
        ->with('next_action_label', 'Try again')
        ->with('next_action_url', '/');
    }
  }
  
  public function deleteImage($resource_id) {
    $im = FileResource::where('resource_id', $resource_id)->first();
    
    if ($im) {
      FileResource::where('resource_id', $resource_id)->delete();
      
      unlink(storage_path('app/public/'.$im->getFname()));
      
      return \Redirect::to('/');
    }
  }
}
