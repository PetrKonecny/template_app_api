<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Album;
use App\User;

class AlbumPolicy
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

    public function update(User $user, Album $album)
    {
        return false;
    }

    public function create(User $user, Album $album)
    {
        return false;
    }

    public function show(User $user, Album $album){
        return $album->public || $user->id === $album->user_id;
    }

    public function delete(User $user, Album $album)
    {
        return false;
    }
}
