<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Contracts\View\View as ReturnView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ImageDisplayController extends Controller
{
    public function displayImages(ImageService $imageService, Request $request): ReturnView
    {
        $imageData = $imageService->getImageData($request);

        return View::make('display-images', [
            'keyName' => $request->query('key'),
            'images' => $imageData
        ]);
    }
}
