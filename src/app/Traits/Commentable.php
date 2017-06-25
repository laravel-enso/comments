<?php

namespace LaravelEnso\CommentsManager\app\Traits;

trait Commentable
{
    public function comments()
    {
        return $this->morphMany('LaravelEnso\CommentsManager\app\Models\Comment', 'commentable');
    }
}
