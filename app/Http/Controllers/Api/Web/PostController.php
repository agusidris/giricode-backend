<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // get Posts
        $posts = Post::when(request()->q, function($posts) {
            $posts = $posts->where('title', 'like', '%'. request()->q . '%');
        })->with('post_series.posts', 'category', 'tags.color', 'user', 'likes.user')->withCount('likes', 'commentcount as comments_count', 'views')->latest()->paginate(6);

        // return with Api Resource
        return new PostResource(true, 'List Data Post', $posts);
    }

    public function show($slug)
    {
        $post = Post::with('post_series.posts', 'category', 'tags.color', 'user', 'likes.user', 'comments.user', 'comments.replies.user', 'views')->withCount('likes', 'commentcount as comments_count', 'views')
        ->where('slug', $slug)->first();

        if($post) {
            //return success with Api Resource
            return new PostResource(true, 'Detail Data Post!', $post);
        }

        //return failed with Api Resource
        return new PostResource(false, 'Detail Data Post Tidak Ditemukan!', null);
    }
}
