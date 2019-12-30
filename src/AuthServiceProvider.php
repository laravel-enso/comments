<?php

namespace LaravelEnso\Comments;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Comments\App\Models\Comment;
use LaravelEnso\Comments\App\Policies\Comment as Policy;

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
