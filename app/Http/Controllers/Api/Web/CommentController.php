<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\Post;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function comment(Post $post, Request $request)
    {
        $userId = auth()->user();

        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user()->associate($userId);
        $post->comments()->save($comment);

        return new CommentResource(true, 'Comment berhasil', null);
    }

    public function reply(Post $post, Request $request)
    {
        $userId = auth()->user();

        $reply = new Comment;
        $reply->comment = $request->comment;
        $reply->user()->associate($userId);
        $reply->parent_id=$post->id;
        $post->comments()->save($reply);

        return new CommentResource(true, 'Reply berhasil', null);
    }
}
