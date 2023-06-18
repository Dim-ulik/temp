<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/auth')->controller('\App\Http\Controllers\AuthController')->group(function () {
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::middleware('check.token')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        Route::get('/check_authorization', function () {
            return response(['message' => 'The user is authorized'], 200);
        });
    });
});

Route::prefix('/banner')->controller('App\Http\Controllers\BannerController')->group(function () {
    Route::get('', 'getAllBanners');
    Route::get('/{id}', 'getBanner');
    Route::middleware('check.token')->group(function () {
        Route::post('', 'createBanner');
        Route::delete('/{id}', 'deleteBanner');
        Route::post('/{id}', 'updateBanner');
    });
});

Route::prefix('/post')->controller('App\Http\Controllers\PostController')->group(function () {
    Route::get('', 'getAllPosts');
    Route::get('/last_post', 'getTheLastPost');
    Route::get('/{id}', 'getPost');
    Route::middleware('check.token')->group(function () {
        Route::post('', 'createPost');
        Route::delete('/{id}', 'deletePost');
        Route::post('/{id}', 'updatePost');
    });
});

Route::prefix('/mail')->controller('App\Http\Controllers\MailController')->group(function () {
    Route::post('/send_form', 'sendForm');
});

Route::prefix('/price')->group(function () {
    Route::prefix('/{item}')->controller('App\Http\Controllers\CalculationController')->group(function () {
        Route::get('', 'getPrice');
        Route::put('', 'changePrice')->middleware('check.token');
    });
});

Route::fallback(function () {
    return response(["message" => "Undefined route"], 404);
});
