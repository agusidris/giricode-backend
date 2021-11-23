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


//group route with prefix "web"
Route::prefix('web')->group(function () {

    //route web sliders
    Route::get('/sliders', [App\Http\Controllers\Api\Web\SliderController::class, 'index']);

    //route web Posts
    Route::resource('/posts', App\Http\Controllers\Api\Web\PostController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);

    //route web Post Series
    Route::resource('/postseries', App\Http\Controllers\Api\Web\PostSeriesController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);

    //route web menus
    Route::get('/menus', [App\Http\Controllers\Api\Web\MenuController::class, 'index']);

    //route web categories
    Route::resource('/categories', App\Http\Controllers\Api\Web\CategoryController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);

    //route web tags
    Route::resource('/tags', App\Http\Controllers\Api\Web\TagController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);

    Route::post('/post/{slug}/likes', [App\Http\Controllers\Api\Web\PostLikeController::class, 'like']);

    Route::post('/post/{slug}/comment', [App\Http\Controllers\Api\Web\CommentController::class, 'comment']);

    Route::post('/post/{slug}/reply/{id}', [App\Http\Controllers\Api\Web\CommentController::class, 'reply']);

    Route::post('/visitor/{slug}', [App\Http\Controllers\Api\Web\VisitorController::class, 'getUserIp']);

    Route::post('/guestbook', [App\Http\Controllers\Api\Web\GuestBookController::class, 'store']);
});

//group route with prefix "admin"
Route::prefix('admin')->group(function () {

    //dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\Admin\DashboardController::class, 'index']);

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

    Route::apiResource('/postseries', App\Http\Controllers\Api\Admin\PostSeriesController::class);

    Route::get('/postseri/posts', [App\Http\Controllers\Api\Admin\PostSeriesController::class, 'allPosts']);

    Route::apiResource('/guestbook', App\Http\Controllers\Api\Admin\GuestBookController::class);

    Route::get('/post/categories', [App\Http\Controllers\Api\Admin\PostController::class, 'allCategories']);

    Route::get('/post/tags', [App\Http\Controllers\Api\Admin\PostController::class, 'allTags']);

    // Menu resource
    Route::apiResource('/menus', App\Http\Controllers\Api\Admin\MenuController::class);

    // Slider resource
    Route::apiResource('/sliders', App\Http\Controllers\Api\Admin\SliderController::class);

    Route::post('/images', [App\Http\Controllers\Api\Admin\ImageController::class, 'store'])->name('image.store');
});

//group route with prefix "guest"
Route::prefix('guest')->group(function () {

    //route register
    Route::post('/register', [App\Http\Controllers\Api\Guest\RegisterController::class, 'store']);

});
