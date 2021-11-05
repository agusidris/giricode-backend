<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuestBookResource;
use App\Models\GuestBook as Guest;
use Illuminate\Http\Request;

class GuestBookController extends Controller
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
            $guests = Guest::when(request()->q, function($guests) {
                $guests = $guests->where('name', 'like', '%'. request()->q . '%');
            })->orderBy('id')->paginate(5);

            // return with Api Resource
            return new GuestBookResource(true, 'List Data GuestBook', $guests);
        }
    }
}
