<?php

namespace LaravelEnso\Comments;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->publish();
    }

    private function load()
    {
        $this->mergeConfigFrom(__DIR__.'/config/comments.php', 'enso.comments');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-enso/comments');

        return $this;
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
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/comments'),
        ], 'comments-email-template');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/comments'),
        ], 'enso-mail');

        $this->publishes([
            __DIR__.'/database/factories' => database_path('factories'),
        ], 'comments-factory');

        $this->publishes([
            __DIR__.'/database/factories' => database_path('factories'),
        ], 'enso-factories');
    }
}
