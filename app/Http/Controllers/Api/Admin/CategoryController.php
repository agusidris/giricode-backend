<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            //get categories
            $categories = Category::when(request()->q, function($categories) {
                $categories = $categories->where('name', 'like', '%'. request()->q . '%');
            })->orderByDesc('id')->paginate(5);

            //return with Api Resource
            return new CategoryResource(true, 'List Data Categories', $categories);
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
                'image'    => 'required|image|mimes:jpeg,jpg,png,svg|max:2000',
                'name'     => 'required|unique:categories',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            //create category
            $category = Category::create([
                'image'=> $image->hashName(),
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);

            if($category) {
                //return success with Api Resource
                return new CategoryResource(true, 'Data Category Berhasil Disimpan!', $category);
            }

            //return failed with Api Resource
            return new CategoryResource(false, 'Data Category Gagal Disimpan!', null);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $category = Category::whereId($id)->first();

            if($category) {
                //return success with Api Resource
                return new CategoryResource(true, 'Detail Data Category!', $category);
            }

            //return failed with Api Resource
            return new CategoryResource(false, 'Detail Data Category Tidak DItemukan!', null);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $validator = Validator::make($request->all(), [
                'name'     => 'required|unique:categories,name,'.$category->id,
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            //check image update
            if ($request->file('image')) {

                //remove old image
                Storage::disk('local')->delete('public/categories/'.basename($category->image));

                //upload new image
                $image = $request->file('image');
                $image->storeAs('public/categories', $image->hashName());

                //update category with new image
                $category->update([
                    'image'=> $image->hashName(),
                    'name' => $request->name,
                    'slug' => Str::slug($request->name, '-'),
                ]);

            }

            //update category without image
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);

            if($category) {
                //return success with Api Resource
                return new CategoryResource(true, 'Data Category Berhasil Diupdate!', $category);
            }

            //return failed with Api Resource
            return new CategoryResource(false, 'Data Category Gagal Diupdate!', null);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {
            //remove image
            Storage::disk('local')->delete('public/categories/'.basename($category->image));

            if($category->delete()) {
                //return success with Api Resource
                return new CategoryResource(true, 'Data Category Berhasil Dihapus!', null);
            }

            //return failed with Api Resource
            return new CategoryResource(false, 'Data Category Gagal Dihapus!', null);
        }
    }
}
