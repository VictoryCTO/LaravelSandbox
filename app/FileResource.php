<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileResource extends Model
{
  protected $primaryKey = "resource_id";
  
	// Return the file relative path
	public function getFname() {
		return $this->resource_id."_".$this->resource_key.".".$this->file_extension;
	}
  
  // Fetch a file by ID
  public static function fetchById($resource_id) {
    return FileResource::where('resource_id', $resource_id)
      ->first();
  }
  
  // Fetch a file based on the hash of its contents
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
  
	// Save the contents of a file to local disk
	public function saveRawFile(&$raw_file) {
		$fname = $this->getFname();
		
		if (!Storage::disk('public')->exists($fname)) {
			$new_file = Storage::put("public/".$fname, $raw_file);
			$this->locally_saved = 1;
			$this->save();
		}
	}
  
  public static function fetchRecent($qty) {
    return FileResource::limit($qty)->orderBy('created_at', 'desc')->get();
  }
  
  public static function totalQty() {
    return count(FileResource::get());
  }
}
