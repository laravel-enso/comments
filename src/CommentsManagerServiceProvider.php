<?php

namespace LaravelEnso\CommentsManager;

use Illuminate\Support\ServiceProvider;

class CommentsManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'comments-migration');

        $this->publishes([
            __DIR__.'/../resources/assets/js/components' => resource_path('assets/js/components/laravel-enso'),
        ], 'comments-component');

        $this->publishes([
            __DIR__.'/notifications' => app_path('notifications/vendor/laravel-enso'),
        ], 'comments-notification');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
