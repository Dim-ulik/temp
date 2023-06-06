<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Services\ImagesService;
use Throwable;

class BannerController extends Controller
{
    function createBanner(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|max:6000'
            ]);

            $path = ImagesService::saveImage($request, 'image');

            Banner::create([
                'image' => $path
            ]);

            return response('', 200);
        } catch (ValidationException $e) {
            return $this->returnBadResponse(400, $e->errors());
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function updateBanner(Request $request, $id)
    {
        try {
            $banner = Banner::findOrFail($id);

            $urlParts = explode('/', $banner->image);
            Storage::disk('public')->delete('uploads/' . $urlParts[5]);

            $path = ImagesService::saveImage($request, 'image');

            $banner->update([
                'image' => $path
            ]);

            return response('', 200);
        } catch (ModelNotFoundException $e) {
            return $this->returnBadResponse(404, 'Undefined banner with id: ' . $id);
        } catch (ValidationException $e) {
            return $this->returnBadResponse(400, $e->errors());
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function deleteBanner($id)
    {
        try {
            $banner = Banner::findOrFail($id);

            ImagesService::deletePhoto(ImagesService::getImageNameFromUrl($banner->image));
            $banner->delete();

            return response('', 200);
        } catch (ModelNotFoundException $e) {
            return $this->returnBadResponse(404, 'Undefined banner with id: ' . $id);
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function getBanner($id)
    {
        try {
            return Banner::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->returnBadResponse(404, 'Undefined banner with id: ' . $id);
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function getAllBanners()
    {
        try {
            return Banner::all();
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }
}
