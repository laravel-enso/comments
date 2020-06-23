<?php

namespace LaravelEnso\Comments;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Comments\DynamicsRelations\Comments;
use LaravelEnso\Comments\DynamicsRelations\CommentTags;
use LaravelEnso\Core\App\Models\User;
use LaravelEnso\DynamicMethods\Services\Methods;

class UserServiceProvider extends ServiceProvider
{
    protected $methods = [
        Comments::class,
        CommentTags::class,
    ];

    public function boot()
    {
        Methods::bind(User::class, $this->methods);
    }
}
