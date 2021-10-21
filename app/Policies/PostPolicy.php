<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function like(User $user, Post $post) {
        return $user->ownsPost($post);
    }

    public function comment(User $user, Post $post) {
        return $user->ownsPost($post);
    }

    public function view(User $user, Post $post) {
        return $user->ownsPost($post);
    }
}
