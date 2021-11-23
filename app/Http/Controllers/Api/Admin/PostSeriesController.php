<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\PostSeries;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PostSeriesResource;
use Illuminate\Support\Facades\Validator;

class PostSeriesController extends Controller
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
            $postseries = PostSeries::when(request()->q, function($postseries) {
                $postseries = $postseries->where('title', 'like', '%'. request()->q . '%');
            })->with('posts.category', 'posts.tags', 'user')->orderByDesc('id')->paginate(5);

            // return with Api Resource
            return new PostSeriesResource(true, 'List Data Post Series Series', $postseries);
        }
    }

    public function allPosts()
    {
        $userId = auth()->user()->id;
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            // get Posts
            $posts = Post::when(request()->q, function($posts) {
                $posts = $posts->where('title', 'like', '%'. request()->q . '%');
            })->with('category', 'tags', 'user')->orderByDesc('id')->get();

            // return with Api Resource
            return new PostSeriesResource(true, 'List Data Post', $posts);
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
                'title'         => 'required|unique:post_series',
                'description'   => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // upload image
            $image = $request->file('image');
            $image->storeAs('public/postseries', $image->hashName());

            // create post
            $post = PostSeries::create([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'slug'          => Str::slug($request->title, '-'),
                'user_id'       => $userId,
                'description'   => $request->description
            ]);

            $post->posts()->attach($request->posts);
            $post->save();

            if ($post) {
                // return success with Api Resource
                return new PostSeriesResource(true, 'Data Post Series Berhasil Disimpan!', $post);
            }

            // return failed with Api Resource
            return new PostSeriesResource(false, 'Data Post Series Gagal Disimpan!', null);
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
            $post = PostSeries::whereId($id)->with('posts.category', 'posts.tags', 'user')->first();

            if ($post) {
                // return success with Api Resource
                return new PostSeriesResource(true, 'Detail Data Post Series!', $post);
            }

            // return failed with Api Resource
            return new PostSeriesResource(false, 'Detail Data Post Series Tidak Ditemukan!', null);
        }
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $postseries
     * @return void
     */
    public function update(Request $request, PostSeries $postseries)
    {
        $userId = auth()->user()->id;
        $role = auth()->user()->role;

        // create postseries
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $validator = Validator::make($request->all(), [
                'title'         => 'required|unique:post_series,title,'.$postseries->id,
                'description'   => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $postseries = PostSeries::findOrFail($postseries->id);

            // check image update
            if($request->file('image')) {

                // remove old image
                Storage::disk('local')->delete('public/postseries/'.basename($postseries->image));

                // upload new image
                $image = $request->file('image');
                $image->storeAs('public/postseries', $image->hashName());

                // update postseries with new image
                $postseries->update([
                    'image'         => $image->hashName(),
                    'title'         => $request->title,
                    'slug'          => Str::slug($request->title, '-'),
                    'description'   => $request->description,
                    'user_id'       => $userId
                ]);
            }

            // update postseries without image
            $postseries->update([
                'title'         => $request->title,
                'slug'          => Str::slug($request->title, '-'),
                'description'   => $request->description,
                'user_id'       => $userId
            ]);

            $postseries->posts()->sync($request->posts);

            if ($postseries) {
                // return success with Api Resource
                return new PostSeriesResource(true, 'Data Post Series Berhasil Diupdate!', $postseries);
            }

            // return failed with Api Resource
            return new PostSeriesResource(false, 'Data Post Series Gagal Diupdate!', null);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $postseries
     * @return void
     */
    public function destroy(PostSeries $postseries)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            // remove image
            Storage::disk('local')->delete('public/postseries/'.basename($postseries->image));

            if ($postseries->delete()) {
                $postseries->posts()->sync(null);
                // return success with Api Resource
                return new PostSeriesResource(true, 'Data Post Series Berhasil Dihapus!', null);
            }

            // return failed with Api Resource
            return new PostSeriesResource(false, 'Data Post Series Gagal Dihapus!', null);
        }
    }

}
