<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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
Route::get('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
Route::get('/check-auth', [AuthController::class, 'checkauth'])->middleware(['auth:sanctum']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::post('/post', [PostController::class, 'store'])->middleware(['auth:sanctum']);
Route::patch('/post/{id}', [PostController::class, 'update'])->middleware(['auth:sanctum', 'post-owner']);
Route::delete('/post/{id}', [PostController::class, 'destroy'])->middleware(['auth:sanctum', 'post-owner']);

Route::post('/comment', [CommentController::class, 'store'])->middleware(['auth:sanctum']);
Route::patch('/comment/{id}', [CommentController::class, 'update'])->middleware(['auth:sanctum', 'comment-owner']);
Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->middleware(['auth:sanctum', 'comment-owner']);
