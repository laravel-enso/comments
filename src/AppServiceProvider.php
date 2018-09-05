<?php

namespace LaravelEnso\CommentsManager;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load();
        $this->publish();
    }

    private function load()
    {
        $this->mergeConfigFrom(__DIR__.'/config/comments.php', 'enso.comments');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-enso/commentsmanager');
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'comments-config');

        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'enso-config');

        $this->publishes([
            __DIR__.'/resources/js' => resource_path('js'),
        ], 'comments-assets');

        $this->publishes([
            __DIR__.'/resources/js' => resource_path('js'),
        ], 'enso-assets');

        $this->publishes([
            __DIR__.'/resources/Notifications' => app_path('Notifications'),
        ], 'comments-notification');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/commentsmanager'),
        ], 'comments-email-template');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/commentsmanager'),
        ], 'enso-mail');
    }

    public function register()
    {
        //
    }
}
