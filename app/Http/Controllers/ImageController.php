<?php

namespace App\Http\Controllers;

use App\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /** @var ImageService service for Image s3 operations */
    private $imageService;

    //todo: place in config file
    /** @var string local storage path */
    private $localStoragePath = "/vagrant/storage/app/images/";
    public function __construct()
    {
        $this->imageService = new ImageService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in s3 bucket.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {
                $name = $image->getClientOriginalName();
                $image->storeAs('images', $name);

                $originalImage = new Image([
                        'name'    => $name,
                        'parent_id' => 0
                    ]
                );
                $originalImage->save();

                $this->imageService->resize($this->localStoragePath, $originalImage);
                $this->imageService->upload($this->localStoragePath, $originalImage);
            }

        }
        return back()->withSuccess('Image(s) uploaded successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return view('images.show', compact('image'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        //
    }
}
