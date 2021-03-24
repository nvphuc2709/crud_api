<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\TagController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/tags', TagController::class);
    Route::apiResource('/posts', PostController::class);

    Route::get('post/filter',[PostController::class, 'filter'])->name('posts.filter');
    Route::get('post/search',[PostController::class, 'search'])->name('posts.search');
});

