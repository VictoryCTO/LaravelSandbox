<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
  public function uploadImage(Request $request) {
    $acceptable_extensions = ["jpg","png"];
    $acceptable_mime_types = ["image/jpeg", "image/png"];
    
    $original_image_name_parts = explode(".", $request->file('demoFile')->getClientOriginalName());
    $image_extension = strtolower($original_image_name_parts[count($original_image_name_parts)-1]);
    $mime_type = $request->file('demoFile')->getMimeType();
    
    if (in_array($image_extension, $acceptable_extensions)) {
      if (in_array($mime_type, $acceptable_mime_types)) {
        return view('image_uploaded')
          ->with('image_extension', $image_extension);
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
}
