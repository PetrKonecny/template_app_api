<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Content;
use App\User;

class ContentPolicy
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

    public function update(User $user, Content $content)
    {
        return false;
    }

    public function create(User $user, Content $content)
    {
        return false;
    }

    public function show(User $user, Content $content){
        return false;
    }

    public function delete(User $user, Content $content)
    {
        return false;
    }
}
