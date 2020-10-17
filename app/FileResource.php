<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileResource extends Model
{
  protected $primaryKey = "resource_id";
  
  // Fetch a file by ID
  public static function fetchById($resource_id) {
    return FileResource::where('resource_id', $resource_id)
      ->first();
  }
  
  // Fetch a resource within a dataset based on a hash of its contents
  public static function fetchResourceByHash($hash_identifier, $resource_type, $file_extension) {
    return FileResource::where('hash_identifier', $hash_identifier)
      ->where('resource_type', $resource_type)
      ->where('file_extension', $file_extension)
      ->first();
  }
  
  // Create a file
  public static function createResource($hash_identifier, $resource_type, $file_extension, $filesize_bytes) {
    $resource = new FileResource();
    $resource->hash_identifier = $hash_identifier;
    $resource->resource_type = $resource_type;
    $resource->file_extension = $file_extension;
    $resource->filesize_bytes = $filesize_bytes;
    $resource->resource_key = \Str::random(20);
    $resource->save();
    
    return $resource;
  }
  
  // Similar function to createResource, but avoids ever creating duplicate files
  public static function createOrFetchResource($hash_identifier, $resource_type, $file_extension, $filesize_bytes) {
    $resource = FileResource::fetchResourceByHash($hash_identifier, $resource_type, $file_extension);
    
    if ($resource) return [$resource, false];
    else return [FileResource::createResource($hash_identifier, $resource_type, $file_extension, $filesize_bytes), true];
  }
}
