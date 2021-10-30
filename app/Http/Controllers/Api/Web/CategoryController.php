<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CategoryController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // check role
        $categories = Category::first()->get();

        // return with Api Resource
        return new CategoryResource(true, 'List Data Category', $categories);
    }

    public function show($slug)
    {
        $category = Category::with('posts.category', 'posts.tags.color', 'posts.user', 'posts.post_series.posts', 'posts.likes.user', 'posts.comments', 'posts.comments.replies', 'posts.views')
            // ->withCount('likes', 'commentcount as comments_count', 'views')
            // ->withCount('posts.category')
            ->where('slug', $slug)->first();

        if ($category) {
            // return success with Api Resource
            return new CategoryResource(true, 'Data Post By Category : '.$category->name.'', $category);
        }

        // return failed with Api Resource
        return new CategoryResource(false, 'Detail Data Category Tidak Ditemukan!', null);
    }
}
