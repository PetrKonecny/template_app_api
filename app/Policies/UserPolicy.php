<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Content;
use App\User;

class UserPolicy
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

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function index(){
        return false; 
    }
}
