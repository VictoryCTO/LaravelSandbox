<?php

namespace Joeyfrich\ImageResizer;

use Intervention\Image\ImageManager;

class ImageResizer {
  public static function hashImage(&$raw_image) {
    return hash("sha256", $raw_image);
  }
  
  public static function resizeAndSaveImages(&$request_images) {
    $acceptable_extensions = ["jpg", "jpeg", "png"];
    $acceptable_mime_types = ["image/jpeg", "image/png"];
    $featured_image = null;
    
    $any_error = false;
    $error_messages = [];
    $success_count = 0;

    ini_set('max_file_uploads', 1000);
    
    foreach ($request_images as $up_file) {
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
            
            $featured_image = $image_resource;
            
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
    
    return [$featured_image, $success_count, $any_error, $error_messages];
  }
}
