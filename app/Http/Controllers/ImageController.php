<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use victorycto\ImageStore\Facades\ImageStore;
use victorycto\ImageStore\Models\Image;

class ImageController extends Controller
{
    public function index()
    {
        return view('images.index')->with('images', Image::all());
    }

    public function create()
    {
        return view('images.create');
    }

    public function store(Request $request)
    {
        if ($file = $request->file('image')) {

            $image = ImageStore::upload($file);

            return redirect('images')->with('message', $image ? 'Image successfully uploaded' : 'Something went wrong');
        }


        return redirect('image/create');
    }
}
