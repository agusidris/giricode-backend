<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
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
            $menus = Menu::when(request()->q, function($menus) {
                $menus = $menus->where('name', 'like', '%'. request()->q . '%');
            })->orderByDesc('id')->paginate(5);

            // return with Api Resource
            return new MenuResource(true, 'List Data Menu', $menus);
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

            // check validator $request
            $validator = Validator::make($request->all(), [
                'name'  => 'required|unique:menus',
                'url'   => 'required'
            ]);

            if($validator->fails()) {
                return response()->json($validator->errors(),
                422);
            }

            // create menu
            $menu = Menu::create([
                'name'  => $request->name,
                'url'   => $request->url
            ]);

            if ($menu) {
                // return success with Api Resource
                return new MenuResource(true, 'Data Menu Berhasil Disimpan!', $menu);
            }

            // return failed with Api Resource
            return new MenuResource(false, 'Data Menu Gagal Disimpan!', null);

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

            $menu = Menu::whereId($id)->first();

            if ($menu) {
                // return success with Api Resource
                return new MenuResource(true, 'Detail Data Menu!', $menu);
            }

            // return failed with Api Resource
            return new MenuResource(false, 'Detail Data Menu Tidak Ditemukan!', null);
        }
    }

    /**
     * update
     *
     * @param  mixed $menu
     * @param  mixed $request
     * @return void
     */
    public function update(Menu $menu, Request $request)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            $validator = Validator::make($request->all(), [
                'name'  => 'required|unique:menus,name,'.$menu->id,
                'url'   => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $menu = Menu::findOrFail($menu->id);

            // update menu
            $menu->update([
                'name'  => $request->name,
                'url'   => $request->url
            ]);

            if ($menu) {
                // return success with Api Resource
                return new MenuResource(true, 'Data Menu Berhasil Diupdate!', $menu);
            }

            // return failed with Api Resource
            return new MenuResource(false, 'Data Menu Gagal Diupdate!', null);

        }
    }

    /**
     * destroy
     *
     * @param  mixed $menu
     * @return void
     */
    public function destroy(Menu $menu)
    {
        $role = auth()->user()->role;

        // check role
        if ($role === 'programmer' || $role === 'admin' || $role === 'operator') {

            if ($menu->delete()) {
                // return success with Api Resource
                return new MenuResource(true, 'Data Menu Berhasil Dihapus!', null);
            }

            // return failed with Api Resource
            return new MenuResource(false, 'Data Menu Gagal Dihapus!', null);
        }
    }
}
