<?php

namespace LaravelEnso\CommentsManager;

use Illuminate\Support\ServiceProvider;

class CommentsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishesAll();
        $this->loadDependencies();
    }

    private function publishesAll()
    {
        $this->publishes([
            __DIR__ . '/config' => config_path(),
        ], 'comments-config');

        $this->publishes([
            __DIR__ . '/resources/assets/js/components' => resource_path('assets/js/vendor/laravel-enso/components'),
        ], 'comments-component');

        $this->publishes([
            __DIR__ . '/resources/Notifications' => app_path('Notifications'),
        ], 'comments-notification');

        $this->publishes([
            __DIR__ . '/resources/assets/js/components' => resource_path('assets/js/vendor/laravel-enso/components'),
        ], 'update');
    }

    private function loadDependencies()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    public function register()
    {
        $this->app->register(CommentsAuthServiceProvider::class);
    }
}
