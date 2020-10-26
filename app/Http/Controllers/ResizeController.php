<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use App\Models\Images;

use Image;

class ResizeController extends Controller
{
    function index()
    {
        $images = Images::all();
        return view('resize', ["images" => $images]);
    }

    public function resize_image(Request $request)
    {
        if ($request->has('image')) {

            $avatar = $request->file('image');
            $extension = $request->file('image')->getClientOriginalExtension();

            $filename = md5(time()) . '_' . $avatar->getClientOriginalName();

            $normal = Image::make($avatar)->resize(600, 600)->encode($extension);
            $medium = Image::make($avatar)->resize(250, 250)->encode($extension);
            $small = Image::make($avatar)->resize(80, 80)->encode($extension);

            Storage::disk('s3')->put('/avatar/normal/' . $filename, (string)$normal, 'public');
            Storage::disk('s3')->put('/avatar/medium/' . $filename, (string)$medium, 'public');
            Storage::disk('s3')->put('/avatar/small/' . $filename, (string)$small, 'public');

            $data = array(
                ['image' => 'normal/' . $filename, 'size' => 'Normal'],
                ['image' => 'medium/' . $filename, 'size' => 'Medium'],
                ['image' => 'small/' . $filename, 'size' => 'Small']
            );

            $img = new Images();
            $img->saveImage($data);

            return redirect()->back();
        }
    }
}
