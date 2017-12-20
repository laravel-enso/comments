<?php

namespace LaravelEnso\CommentsManager;

use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies;

    public function boot()
    {
        $this->policies = [
            Comment::class => CommentPolicy::class,
        ];

        $this->registerPolicies();
    }
}
