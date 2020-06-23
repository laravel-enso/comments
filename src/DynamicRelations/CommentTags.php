<?php

namespace LaravelEnso\Comments\DynamicsRelations;

use Closure;
use LaravelEnso\Comments\Models\Comment;
use LaravelEnso\DynamicMethods\Contracts\Method;

class CommentTags implements Method
{
    public function name(): string
    {
        return 'comments';
    }

    public function closure(): Closure
    {
        return fn () => $this->belongsToMany(Comment::class);
    }
}
