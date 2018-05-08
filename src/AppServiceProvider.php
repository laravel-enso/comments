<?php

namespace LaravelEnso\CommentsManager;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishesAll();
        $this->loadDependencies();
    }

    private function publishesAll()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'comments-config');

        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'enso-config');

        $this->publishes([
            __DIR__.'/resources/assets/js' => resource_path('assets/js'),
        ], 'comments-assets');

        $this->publishes([
            __DIR__.'/resources/assets/js' => resource_path('assets/js'),
        ], 'enso-assets');

        $this->publishes([
            __DIR__.'/resources/Notifications' => app_path('Notifications'),
        ], 'comments-notification');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/commentsmanager'),
        ], 'comments-email-templates');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/commentsmanager'),
        ], 'email-templates');
    }

    private function loadDependencies()
    {
        $this->mergeConfigFrom(__DIR__.'/config/comments.php', 'enso.comments');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-enso/commentsmanager');
    }

    public function register()
    {
        //
    }
}
