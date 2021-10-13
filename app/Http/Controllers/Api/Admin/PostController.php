<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.role:admin,operator,programmer');
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            // get Posts
            $posts = Post::when(request()->q, function($posts) {
                $posts = $posts->where('title', 'like', '%'. request()->q . '%');
            })->with('category', 'tags', 'user')->orderByDesc('id')->paginate(5);

            // return with Api Resource
            return new PostResource(true, 'List Data Post', $posts);
        }
    }

    /**
     * allCategories
     *
     * @return void
     */
    public function allCategories()
    {
        $userId = auth()->user()->id;
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            // get Posts
            $categories = Category::when(request()->q, function($categories) {
                $categories = $categories->where('title', 'like', '%'. request()->q . '%');
            })->first()->get();

            // return with Api Resource
            return new PostResource(true, 'List Data Categories', $categories);
        }
    }

    public function allTags()
    {
        $userId = auth()->user()->id;
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            // get Posts
            $tags = Tag::when(request()->q, function($tags) {
                $tags = $tags->where('title', 'like', '%'. request()->q . '%');
            })->first()->with('color')->get();

            // return with Api Resource
            return new PostResource(true, 'List Data Tags', $tags);
        }
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $userId = auth()->user()->id;
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            // check validator $request
            $validator = Validator::make($request->all(), [
                'image'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
                'title'         => 'required|unique:posts',
                'category_id'   => 'required',
                'content'       => 'required',
                'description'   => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            // create post
            $post = Post::create([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'slug'          => Str::slug($request->title, '-'),
                'category_id'   => $request->category_id,
                'user_id'       => $userId,
                'content'       => $request->content,
                'description'   => $request->description
            ]);

            $post->tags()->attach($request->tags);
            $post->save();

            if ($post) {
                // return success with Api Resource
                return new PostResource(true, 'Data Post Berhasil Disimpan!', $post);
            }

            // return failed with Api Resource
            return new PostResource(false, 'Data Post Gagal Disimpan!', null);
        }
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            $post = Post::whereId($id)->with('category', 'tags')->first();

            if ($post) {
                // return success with Api Resource
                return new PostResource(true, 'Detail Data Post!', $post);
            }

            // return failed with Api Resource
            return new PostResource(false, 'Detail Data Post Tidak Ditemukan!', null);
        }
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, Post $post)
    {
        $userId = auth()->user()->id;
        $role = auth()->user()->role;

        // create post
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $validator = Validator::make($request->all(), [
                'title'         => 'required|unique:posts,title,'.$post->id,
                'category_id'   => 'required',
                'content'       => 'required',
                'description'   => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $post = Post::findOrFail($post->id);

            // check image update
            if($request->file('image')) {

                // remove old image
                Storage::disk('local')->delete('public/posts/'.basename($post->image));

                // upload new image
                $image = $request->file('image');
                $image->storeAs('public/posts', $image->hashName());

                // update post with new image
                $post->update([
                    'image'         => $image->hashName(),
                    'title'         => $request->title,
                    'slug'          => Str::slug($request->title, '-'),
                    'category_id'   => $request->category_id,
                    'user_id'       => $userId,
                    'content'       => $request->content,
                    'description'   => $request->description
                ]);
            }

            // update post without image
            $post->update([
                'title'         => $request->title,
                'slug'          => Str::slug($request->title, '-'),
                'category_id'   => $request->category_id,
                'user_id'       => $userId,
                'content'       => $request->content,
                'description'   => $request->description
            ]);

            $post->tags()->sync($request->tags);

            if ($post) {
                // return success with Api Resource
                return new PostResource(true, 'Data Post Berhasil Diupdate!', $post);
            }

            // return failed with Api Resource
            return new PostResource(false, 'Data Post Gagal Diupdate!', null);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(Post $post)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            // remove image
            Storage::disk('local')->delete('public/posts/'.basename($post->image));

            if ($post->delete()) {
                $post->tags()->sync(null);
                // return success with Api Resource
                return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
            }

            // return failed with Api Resource
            return new PostResource(false, 'Data Post Gagal Dihapus!', null);
        }
    }
}
