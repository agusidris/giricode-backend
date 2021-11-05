<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // check role
        $tags = Tag::with('color')->latest()->get();

        // return with Api Resource
        return new TagResource(true, 'List Data Tags', $tags);
    }

    /**
     * show
     *
     * @param  mixed $slug
     * @return void
     */
    public function show($slug)
    {
        $tag = Tag::with('posts.category', 'posts.tags.color', 'posts.user', 'posts.post_series.posts', 'posts.likes.user', 'posts.comments', 'posts.comments.replies', 'posts.views')
            ->where('slug', $slug)->first();

        if ($tag) {
            // return success with Api Resource
            return new TagResource(true, 'Data Post By Tags : '.$tag->name.'', $tag);
        }

        // return failed with Api Resource
        return new TagResource(false, 'Detail Data Tags Tidak Ditemukan!', null);
    }
}
