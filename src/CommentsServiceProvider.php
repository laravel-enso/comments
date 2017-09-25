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
            __DIR__.'/config' => config_path(),
        ], 'comments-config');

        $this->publishes([
            __DIR__.'/config' => config_path(),
        ], 'enso-config');

        $this->publishes([
            __DIR__.'/resources/Notifications' => app_path('Notifications'),
        ], 'comments-notification');
    }

    private function loadDependencies()
    {
        $this->mergeConfigFrom(__DIR__.'/config/comments.php', 'comments');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
