<?php

namespace LaravelEnso\Comments;

use LaravelEnso\Comments\app\Models\Comment;
use LaravelEnso\Comments\app\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Comment::class => CommentPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
