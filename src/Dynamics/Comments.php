<?php

namespace LaravelEnso\Comments\Dynamis;

use Closure;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Comments\Models\Comment;
use LaravelEnso\DynamicMethods\Contracts\Relation;
use LaravelEnso\Users\Models\User;

class Comments implements Relation
{
    public function bindTo(): array
    {
        return [User::class];
    }

    public function name(): string
    {
        return 'comments';
    }

    public function closure(): Closure
    {
        return fn (Model $user) => $user->hasMany(Comment::class, 'created_by');
    }
}
