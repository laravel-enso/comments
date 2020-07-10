<?php

namespace LaravelEnso\Comments;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Comments\DynamicRelations\Comments;
use LaravelEnso\Comments\DynamicRelations\CommentTags;
use LaravelEnso\Core\Models\User;
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
