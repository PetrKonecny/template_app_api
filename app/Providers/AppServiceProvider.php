<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('League\Glide\Server', function($app){
                $filesystem = $app->make('Illuminate\Contracts\Filesystem\Filesystem');
                return \League\Glide\ServerFactory::create([
                    'source' => $filesystem->getDriver(),
                    'cache' => $filesystem->getDriver(),
                    'source_path_prefix' => 'images',
                    'cache_path_prefix' => 'images/.cache',
                    'base_url' => 'img',
                    'response' => new \League\Glide\Responses\LaravelResponseFactory(),
                ]);            
        });
        $this->app->singleton('SkautIS\SkautIS',function($app){
            return \skautIS\SkautIS::getInstance("291fb631-97cf-4a2e-ad6b-1b3b14b9d9a2", $isTestMode = TRUE);
        });

        $this->app->singleton('Services\TemplateService',function($app){
            return new Services\TemplateService();
        });

        $this->app->singleton('Services\TemplateInstanceService',function($app){
            return new Services\TemplateInstanceService();
        });

        $this->app->singleton('Services\ImageService',function($app){
            return new Services\ImageService();
        });

        $this->app->singleton('Services\FontService',function($app){
            return new Services\FontService();
        });

        $this->app->singleton('Services\PageService',function($app){
            return new Services\PageService();
        });

        $this->app->singleton('Services\ContentService',function($app){
            return new Services\ContentService();
        });

        $this->app->singleton('Services\ElementService',function($app){
            return new Services\ElementService();
        });
    }
}
