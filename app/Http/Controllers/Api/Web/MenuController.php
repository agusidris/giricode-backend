<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // check role
        $menus = Menu::first()->get();

        // return with Api Resource
        return new MenuResource(true, 'List Data Menu', $menus);
    }
}
