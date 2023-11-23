<?php

namespace LaravelEnso\Comments\Dynamics;

use Closure;
use LaravelEnso\Comments\Models\Comment;
use LaravelEnso\DynamicMethods\Contracts\Relation;
use LaravelEnso\Users\Models\User;

class CommentTags implements Relation
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
        return fn (User $user) => $user->belongsToMany(Comment::class);
    }
}
