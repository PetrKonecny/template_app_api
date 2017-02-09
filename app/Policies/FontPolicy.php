<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Font;
use App\User;

class FontPolicy
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

    public function update(User $user, Font $font)
    {
        return $user->id === $font->user_id;
    }

    public function create(User $user, Font $font)
    {
        return true;
    }

    public function show(User $user, Font $font){
        return $user->id === $font->user_id || $font->public;
    }

    public function delete(User $user, Font $font)
    {
        return $user->id === $font->user_id;
    }
}
