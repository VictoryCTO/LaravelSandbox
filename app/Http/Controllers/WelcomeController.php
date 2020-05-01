<?php
namespace App\Http\Controllers;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    private $imageService;

    public function __construct()
    {
        $this->imageService = new ImageService();

    }

    public function index()
    {
        $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
        $images = [];
        $files = Storage::disk('s3')->files('images');
        foreach ($files as $file) {
            $images[] = [
                'name' => str_replace('images/', '', $file),
                'src' => $url . $file
            ];
        }
        return view('welcome', compact('images'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|max:2048'
        ]);
        if ($request->hasFile('image')) {
            $filename = $request->file('image')->getClientOriginalName();
            $fullpath = $request->file('image')->storeAs('images', $filename);

            $this->imageService->upload('/vagrant/storage/app/images/', $filename);

        }
        return back()->withSuccess('Image uploaded successfully');
    }
    public function destroy($image)
    {
        Storage::disk('s3')->delete('images/' . $image);
        return back()->withSuccess('Image was deleted successfully');
    }
}
