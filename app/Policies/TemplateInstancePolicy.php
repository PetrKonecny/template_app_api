<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\TemplateInstance;
use App\User;

class TemplateInstancePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
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

    public function update(User $user, TemplateInstance $templateInstance)
    {
        return $user->id === $templateInstance->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function show(User $user, TemplateInstance $templateInstance){
        return $user->id === $templateInstance->user_id;
    }

    public function delete(User $user, TemplateInstance $templateInstance)
    {
        return $user->id === $templateInstance->user_id;
    }
}
