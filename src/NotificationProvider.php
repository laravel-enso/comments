<?php

namespace LaravelEnso\CommentsManager;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\CommentsManager\app\Contracts\NotifiesTaggedUsers;
use LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification;

class NotificationProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind(
            NotifiesTaggedUsers::class, CommentTagNotification::class
        );
    }

    public function provides()
    {
        return [NotifiesTaggedUsers::class];
    }
}
