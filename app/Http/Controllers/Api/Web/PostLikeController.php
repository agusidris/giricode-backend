<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostLikeResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.role:guest,operator,admin,programmer');
    }

    /**
     * like
     *
     * @param  mixed $post
     * @return void
     */
    public function like($slug) {

        $userId = auth()->user();

        $post = Post::where('slug', $slug)->first();
        // $this->authorize('like', $post);
        // check
        if ($userId->hasLikedPost($post)) {

            // create like model
            $like = new Like;
            $like->user()->dissociate();
            $post->likes()->delete($like);

            return new PostLikeResource(true, 'unLike Post berhasil', null);
        }

        // create like model
        $like = new Like;
        $like->user()->associate($userId);
        $post->likes()->save($like);

        return new PostLikeResource(true, 'Like Post berhasil', null);
    }
}
