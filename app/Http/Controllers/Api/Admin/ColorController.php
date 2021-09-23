<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Color;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ColorResource;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
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
            // get colors
            $colors = Color::when(request()->q, function($colors) {
                $colors = $colors->where('name', 'like', '%'.request()->q . '%');
            })->get();

            // return with Api Resource
            return new ColorResource(true, 'List Data Color', $colors);
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
                'name'  => 'required|unique:colors',
                'value' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // create color
            $color = Color::create([
                'name'  => $request->name,
                'value' => $request->value
            ]);

            if ($color) {
                // return success with Api Resource
                return new ColorResource(true, 'Data Color Berhasil Disimpan!', $color);
            }

            // return failed with Api Resource
            return new ColorResource(false, 'Data Color Gagal Disimpan!', null);
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

            $color = Color::whereId($id)->first();

            if($color) {
                // return success with Api Resource
                return new ColorResource(true, 'Detail Data Color!', $color);
            }

            // return failed with Api Resource
            return new ColorResource(false, 'Detail Data Color Tidak Ditemukan!', null);
        }
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $color
     * @return void
     */
    public function update(Request $request, Color $color)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $validator = Validator::make($request->all(), [
                'name'  => 'required|unique:colors,name,'.$color->id,
                'value' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            //update color
            $color->update([
                'name' => $request->name,
                'value'=> $request->value
            ]);

            if($color) {
                // return success with Api Resource
                return new ColorResource(true, 'Data Color Berhasil Diupdate!', $color);
            }

            // return failed with Api Resource
            return new ColorResource(false, 'Data Color Gagal Diupdate!', null);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $color
     * @return void
     */
    public function destroy(Color $color)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            if ($color->delete()) {
                // return success with Api Resource
                return new ColorResource(true, 'Data Color Berhasil Dihapus!', null);
            }

            // return failed with Api Resource
            return new ColorResource(false, 'Data Color Gagal Dihapus!', null);
        }
    }
}
