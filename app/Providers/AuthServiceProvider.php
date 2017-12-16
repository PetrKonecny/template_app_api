<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Template' => 'App\Policies\TemplatePolicy',
        'App\TemplateInstance' => 'App\Policies\TemplateInstancePolicy',
        'App\Font' => 'App\Policies\FontPolicy',
        'App\Image' => 'App\Policies\ImagePolicy',
        'App\Content' => 'App\Policies\ContentPolicy',
        'App\Element' => 'App\Policies\ElementPolicy',
        'App\ImageElement' => 'App\Policies\ElementPolicy',
        'App\TextElement' => 'App\Policies\ElementPolicy',
        'App\FrameElement' => 'App\Policies\ElementPolicy',
        'App\TableElement' => 'App\Policies\ElementPolicy',
        'App\Album' => 'App\Policies\AlbumPolicy',
        'App\Page' => 'App\Policies\PagePolicy',
        'App\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('access-admin-panel', function ($user) {
            return $user->admin == true;
        });
    }
}
