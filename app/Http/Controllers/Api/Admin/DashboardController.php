<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
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
        if ($role === 'programmer') {
            // count User
            $user = User::latest()->get()->count();
        } else if ($role === 'admin') {
            // count User
            $user = User::whereNotIn('role', ['programmer'])->count();
        } else if ($role === 'operator') {
            // count User
            $user = User::whereNotIn('role', ['programmer', 'admin'])->count();
        }
        $category = Category::latest()->get()->count();
        $post = Post::latest()->get()->count();

        // response
        return response()->json([
            'success'   => true,
            'message'   => 'Statistik Data',
            'data'      => [
                'count' => [
                    'user'      => $user,
                    'category'  => $category,
                    'post'      => $post
                ]
            ]
        ], 200);
    }
}
