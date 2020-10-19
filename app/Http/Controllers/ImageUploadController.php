<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ReturnView;
use Illuminate\Support\Facades\View;

class ImageUploadController extends Controller
{
    public function showForm(): ReturnView
    {
        return View::make('upload-form', ['formAction' => '/process']);
    }

    public function processForm(ImageService $imageService, Request $request): string
    {
        $this->validate($request, [
            'upload_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageService->processUpload($request)
            ->save([
                ImageService::IMAGE_SMALL_WIDTH,
                ImageService::IMAGE_THUMBNAIL_WIDTH
            ])->uploadToS3Bucket();

        return 'Did it!';
    }
}
