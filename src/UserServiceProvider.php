<?php

namespace LaravelEnso\Comments;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Comments\DynamicRelations\Comments;
use LaravelEnso\Comments\DynamicRelations\CommentTags;
use LaravelEnso\DynamicMethods\Services\Methods;
use LaravelEnso\Users\Models\User;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Methods::bind(User::class, [Comments::class, CommentTags::class]);
    }
}
