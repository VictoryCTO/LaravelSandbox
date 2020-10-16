<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
  public function uploadImage(Request $request) {
    return view('image_uploaded');
  }
}
