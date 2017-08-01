<?php

namespace LaravelEnso\CommentsManager\app\Traits;

use LaravelEnso\CommentsManager\app\Models\Comment;

trait Commentable
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
