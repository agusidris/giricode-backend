<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
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
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            // get tags
            $tags = Tag::when(request()->q, function($tags) {
                $tags = $tags->where('name', 'like', '%'. request()->q . '%');
            })->latest()->paginate(5);

            // return with Api Resource
            return new TagResource(true, 'List Data Tags', $tags);
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
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $validator = Validator::make($request->all(), [
                'name'      => 'required|unique:tags',
                'color'     => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $tag = Tag::create([
                'name'      => $request->name,
                'slug'      => Str::slug($request->name, '-'),
                'color'     => $request->color
            ]);

            if($tag) {
                // return success with Api Resource
                return new TagResource(true, 'Data Tag Berhasil Disimpan!', $tag);
            }

            // return failed with Api Resource
            return new TagResource(false, 'Data Tag Gagal Disimpan', null);
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

            $tag = Tag::whereId($id)->first();

            if($tag) {
                // return success with Api Resource
                return new TagResource(true, 'Detail Data Tag!', $tag);
            }

            // return failed with Api Resource
            return new TagResource(false, 'Detail Data Tag Tidak Ditemukan!', null);
        }
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $tag
     * @return void
     */
    public function update(Request $request, Tag $tag)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $validator = Validator::make($request->all(), [
                'name'      => 'required|unique:tags,name,'.$tag->id,
                'color'     => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // update tag
            $tag->update([
                'name'      => $request->name,
                'slug'      => Str::slug($request->name, '-'),
                'color'     => $request->color
            ]);

            if ($tag) {
                // return success with Api Resource
                return new TagResource(true, 'Data Tag Berhasil Diupdate!', $tag);
            }

            // return failed with Api Resource
            return new TagResource(false, 'Data Tag Gagal Diupdate', null);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $tag
     * @return void
     */
    public function destroy(Tag $tag)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            if($tag->delete()) {
                // return success with Api Resource
                return new TagResource(true, 'Data Tag Berhasil Dihapus!', null);
            }

            // return failed with Api Resource
            return new TagResource(false, 'Data Tag Gaga Dihapus!', null);
        }
    }

}
