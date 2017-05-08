<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Element;
use App\User;

class ElementPolicy
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

    public function update(User $user, Element $element)
    {
        return false;
    }

    public function create(User $user, Element $element)
    {
        return false;
    }

    public function show(User $user, Element $element){
        return false;
    }

    public function delete(User $user, Element $element)
    {
        return false;
    }
}
