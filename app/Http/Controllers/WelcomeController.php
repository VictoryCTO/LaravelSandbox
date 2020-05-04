<?php
namespace App\Http\Controllers;
use App\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{

    public function index()
    {
        //get all previously uploaded images that are source (not thumbnail) images
        $images = Image::where('parent_id','=', 0)->get();

        return view('welcome', compact('images'));
    }

}
