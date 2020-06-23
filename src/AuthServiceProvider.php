<?php

namespace LaravelEnso\Comments;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Comments\Models\Comment;
use LaravelEnso\Comments\Policies\Comment as Policy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Comment::class => Policy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
