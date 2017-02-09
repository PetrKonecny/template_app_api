<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Template;
use App\User;

class TemplatePolicy
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

    public function update(User $user, Template $template)
    {
        return $user->id === $template->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function show(User $user, Template $template){
        return $user->id === $template->user_id || $template->public;
    }

    public function delete(User $user, Template $template)
    {
        return $user->id === $template->user_id;
    }
}
