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
        $this->middleware('auth.role:guest,operator,admin,programmer');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function comment($slug, Request $request)
    {
        $userId = auth()->user();
        $post = Post::where('slug', $slug)->first();

        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user()->associate($userId);
        $post->comments()->save($comment);

        if($post) {
            //return success with Api Resource
            return new CommentResource(true, 'Comment Berhasil Disimpan!', null);
        }

        return new CommentResource(false, 'Comment Gagal Disimpan!', null);
    }

    public function reply($slug, $id, Request $request)
    {
        $userId = auth()->user();
        $comment = Comment::whereId($id)->first();
        $post = Post::where('slug', $slug)->first();

        $reply = new Comment;
        $reply->comment = $request->comment;
        $reply->user()->associate($userId);
        $reply->parent_id=$comment->id;
        $post->comments()->save($reply);

        if($post) {
            //return success with Api Resource
            return new CommentResource(true, 'Reply berhasil Disimpan!', null);
        }

        return new CommentResource(true, 'Reply Gagal Disimpan!', null);
    }
}
