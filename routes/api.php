<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\PostCondition;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//get all post
Route::get('/all/posts', [PostController::class, 'getAllPost']);
//get single post
Route::get('/single/post/{post_id}', [PostController::class, 'getSinglePost']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);

    //blog post apis
    Route::post('/add/post', [PostController::class, 'addNewPost']);
    //edit the post 1
    Route::post('/edit1/post', [PostController::class, 'editPost1']);
    //edit the post 2 method
    Route::post('/edit2/post/{post_id}', [PostController::class, 'editPost2']);
    //delete the post
    Route::post('/delete/post/{post_id}', [PostController::class, 'deletePost']);

    //comment
    Route::post('/comment', [CommentController::class, 'postComment']);
    Route::post('/like', [LikeController::class, 'likePost']);
});