<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Page;
use App\User;

class PagePolicy
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

    public function update(User $user, Page $page)
    {
        return false;
    }

    public function create(User $user, Page $page)
    {
        return false;
    }

    public function show(User $user, Page $page){
        return false;
    }

    public function delete(User $user, Page $page)
    {
        return false;
    }
}
