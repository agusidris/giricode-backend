<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostSeriesResource;
use App\Models\PostSeries;

class PostSeriesController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // get Posts
        $postseries = PostSeries::when(request()->q, function($postseries) {
            $postseries = $postseries->where('title', 'like', '%'. request()->q . '%');
        })->with('posts.category', 'posts.tags', 'user')->latest()->paginate(9);

        // return with Api Resource
        return new PostSeriesResource(true, 'List Data Post Series', $postseries);
    }

    public function show($slug)
    {
        $post = PostSeries::with('posts.category', 'posts.tags', 'user')
        ->where('slug', $slug)->first();

        if($post) {
            //return success with Api Resource
            return new PostSeriesResource(true, 'Detail Data Post Series!', $post);
        }

        //return failed with Api Resource
        return new PostSeriesResource(false, 'Detail Data Post Series Tidak Ditemukan!', null);
    }
}
