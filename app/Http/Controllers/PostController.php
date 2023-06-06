<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Post;
use App\Services\ImagesService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class PostController extends Controller
{
    function createPost(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'header' => 'required|string',
                'text' => 'required|string',
                'image1' => 'nullable|max:6000',
                'image2' => 'nullable|max:6000',
                'image3' => 'nullable|max:6000'
            ]);

            $path1 = ImagesService::saveImage($request, 'image1');
            $path2 = ImagesService::saveImage($request, 'image2');
            $path3 = ImagesService::saveImage($request, 'image3');

            Post::create([
                'header' => $validatedData['header'],
                'text' => $validatedData['text'],
                'image1' => $path1 ?? null,
                'image2' => $path2 ?? null,
                'image3' => $path3 ?? null
            ]);

            return response('', 200);
        } catch (ValidationException $e) {
            return $this->returnBadResponse(400, $e->errors());
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function getPost($id)
    {
        try {
            return Post::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->returnBadResponse(404, 'Undefined post with id: ' . $id);
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function getAllPosts()
    {
        try {
            return Post::all();
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function updatePost(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            $validatedData = $request->validate([
                'header' => 'required|string',
                'text' => 'required|string',
                'image1' => 'required|max:6000',
                'image2' => 'required|max:6000',
                'image3' => 'required|max:6000'
            ]);

            if ($request->hasFile('image1')) {
                $path = ImagesService::saveImage($request, 'image1');
                $post->image1 = $path;
            } else {
                if ($validatedData['image1'] == 0) {
                    $post->image1 = null;
                }
            }

            if ($request->hasFile('image2')) {
                $path = ImagesService::saveImage($request, 'image2');
                $post->image2 = $path;
            } else {
                if ($validatedData['image2'] == 0) {
                    $post->image2 = null;
                }
            }

            if ($request->hasFile('image3')) {
                $path = ImagesService::saveImage($request, 'image3');
                $post->image3 = $path;
            } else {
                if ($validatedData['image3'] == 0) {
                    $post->image3 = null;
                }
            }

            $post->header = $validatedData['header'];
            $post->text = $validatedData['text'];

            $post->save();

            return response('', 200);
        } catch (ModelNotFoundException $e) {
            return $this->returnBadResponse(404, 'Undefined banner with id: ' . $id);
        } catch (ValidationException $e) {
            return $this->returnBadResponse(400, $e->errors());
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function deletePost($id)
    {
        try {
            $post = Post::findOrFail($id);

            $post->image1 && ImagesService::deletePhoto(ImagesService::getImageNameFromUrl($post->image1));
            $post->image2 && ImagesService::deletePhoto(ImagesService::getImageNameFromUrl($post->image2));
            $post->image3 && ImagesService::deletePhoto(ImagesService::getImageNameFromUrl($post->image3));
            $post->delete();

            return response('', 200);
        } catch (ModelNotFoundException $e) {
            return $this->returnBadResponse(404, 'Undefined banner with id: ' . $id);
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }
}
