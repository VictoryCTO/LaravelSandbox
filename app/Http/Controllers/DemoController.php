<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Joeyfrich\ImageResizer\ImageResizer;
use Joeyfrich\ImageResizer\FileResource;
use Intervention\Image\ImageManager;

class DemoController extends Controller
{
  public function home() {
    return view('welcome')
      ->with('recent_images', FileResource::fetchRecent(20))
      ->with('total_qty', FileResource::totalQty());
  }
  
  public function uploadImage(Request $request) {
    $acceptable_extensions = ["jpg", "jpeg", "png"];
    $acceptable_mime_types = ["image/jpeg", "image/png"];
    $featured_resource = null;
    
    $any_error = false;
    $error_messages = [];
    $success_count = 0;

    ini_set('max_file_uploads', 1000);
    
    foreach ($request->allFiles()['demoFiles'] as $up_file) {
      $original_image_name_parts = explode(".", $up_file->getClientOriginalName());
      $image_extension = strtolower($original_image_name_parts[count($original_image_name_parts)-1]);
      $mime_type = $up_file->getMimeType();
      
      if (in_array($image_extension, $acceptable_extensions)) {
        if (in_array($mime_type, $acceptable_mime_types)) {
          if ($raw_image = @file_get_contents($up_file)) {
            $hash_identifier = ImageResizer::hashImage($raw_image);
            $filesize_bytes = strlen($raw_image);
            
            list($image_resource, $is_new) = FileResource::createOrFetchResource($hash_identifier, "image", $image_extension, $filesize_bytes);
            
            $image_resource->saveRawFile($raw_image);
            
            if (env('SAVE_TO_S3')) {
              $s3_path = $up_file->store('s3');
              
              $image_resource->primary_aws_path = $s3_path;
              $image_resource->primary_aws_url = \Storage::disk('s3')->url($s3_path);
              $image_resource->saved_in_aws = 1;
              $image_resource->save();
            }
            
            $im_manager = new ImageManager(array('driver' => 'imagick'));
            
            foreach (FileResource::imageSizes() as $size_prefix => $size_info) {
              $im_thumb = $im_manager->make($up_file);
              $im_thumb->resize($size_info['max_width'], $size_info['max_height']);
              $im_thumb->save("storage/".$image_resource->resource_id."_".$image_resource->resource_key."_".$size_prefix.".".$image_resource->file_extension);
            }
            
            $featured_resource = $image_resource;
            
            if ($is_new) $success_count++;
          }
          else {
            array_push($error_messages, 'Server failed to read the image.');
            $any_error = true;
          }
        }
        else {
          array_push($error_messages, 'The mime type for the image you uploaded is not allowed.');
          $any_error = true;
        }
      }
      else {
        array_push($error_messages, 'The file extension for the image you uploaded is not allowed.');
        $any_error = true;
      }
    }
    
    array_push($error_messages, number_format($success_count).' images were added');
    
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
