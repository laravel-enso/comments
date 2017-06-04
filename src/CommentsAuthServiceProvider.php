<?php

namespace LaravelEnso\CommentsManager;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Policies\CommentPolicy;

class CommentsAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies;

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->policies = [
            Comment::class => CommentPolicy::class,
        ];

        $this->registerPolicies();
    }
}
