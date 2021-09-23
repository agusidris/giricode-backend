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
            })->latest()->paginate(5);

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

    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {

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

    }

    /**
     * destroy
     *
     * @param  mixed $menu
     * @return void
     */
    public function destroy(Menu $menu)
    {

    }
}
