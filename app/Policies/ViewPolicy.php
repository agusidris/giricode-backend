<?php

namespace App\Policies;

use App\Models\User;
use App\Models\View;
use Illuminate\Auth\Access\HandlesAuthorization;

class ViewPolicy
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

    public function view(User $user, View $view) {
        return $user->ownsPost($view);
    }
}
