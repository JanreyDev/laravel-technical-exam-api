<?php

use App\Http\Controllers\Api\V1\PlaceholderAlbumController;
use App\Http\Controllers\Api\V1\PlaceholderCommentController;
use App\Http\Controllers\Api\V1\PlaceholderPhotoController;
use App\Http\Controllers\Api\V1\PlaceholderPostController;
use App\Http\Controllers\Api\V1\PlaceholderTodoController;
use App\Http\Controllers\Api\V1\PlaceholderUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('auth.basic')
    ->group(function (): void {
        Route::get('/users', [PlaceholderUserController::class, 'index']);
        Route::get('/users/{externalId}', [PlaceholderUserController::class, 'show']);

        Route::get('/posts', [PlaceholderPostController::class, 'index']);
        Route::get('/posts/{externalId}', [PlaceholderPostController::class, 'show']);

        Route::get('/comments', [PlaceholderCommentController::class, 'index']);
        Route::get('/comments/{externalId}', [PlaceholderCommentController::class, 'show']);

        Route::get('/albums', [PlaceholderAlbumController::class, 'index']);
        Route::get('/albums/{externalId}', [PlaceholderAlbumController::class, 'show']);

        Route::get('/photos', [PlaceholderPhotoController::class, 'index']);
        Route::get('/photos/{externalId}', [PlaceholderPhotoController::class, 'show']);

        Route::get('/todos', [PlaceholderTodoController::class, 'index']);
        Route::get('/todos/{externalId}', [PlaceholderTodoController::class, 'show']);
    });
