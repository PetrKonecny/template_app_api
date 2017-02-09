<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Image;
use App\User;

class ImagePolicy
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

    public function update(User $user, Image $image)
    {
        return $user->id === $image->user_id;
    }

    public function create(User $user, Image $image)
    {
        return true;
    }

    public function show(User $user, Image $image){
        return $user->id === $image->user_id || $image->public;
    }

    public function delete(User $user, Image $image)
    {
        return $user->id === $image->user_id;
    }
}
