<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//group route with prefix "admin"
Route::prefix('auth')->group(function () {

    //route login
    Route::post('/login', [App\Http\Controllers\Api\Auth\AuthController::class, 'index', ['as' => 'admin']]);

    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function() {

        //data user
        Route::get('/me', [App\Http\Controllers\Api\Auth\AuthController::class, 'getUser', ['as' => 'admin']]);

        //refresh token JWT
        Route::get('/refresh', [App\Http\Controllers\Api\Auth\AuthController::class, 'refreshToken', ['as' => 'admin']]);

        //logout
        Route::post('/logout', [App\Http\Controllers\Api\Auth\AuthController::class, 'logout', ['as' => 'admin']]);

    });
});

//group route with prefix "admin"
Route::prefix('admin')->group(function () {

    // User resource
    Route::apiResource('/users', App\Http\Controllers\Api\Admin\UserController::class);

    //categories resource
    Route::apiResource('/categories', App\Http\Controllers\Api\Admin\CategoryController::class);

    // Colors resource
    Route::apiResource('/colors', App\Http\Controllers\Api\Admin\ColorController::class);

    // Tags resource
    Route::apiResource('/tags', App\Http\Controllers\Api\Admin\TagController::class);

    // Post resource
    Route::apiResource('/posts', App\Http\Controllers\Api\Admin\PostController::class);

    // Menu resource
    Route::apiResource('/menus', App\Http\Controllers\Api\Admin\MenuController::class);
});

