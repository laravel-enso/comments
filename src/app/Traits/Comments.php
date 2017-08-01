<?php

namespace LaravelEnso\CommentsManager\app\Traits;

use LaravelEnso\CommentsManager\app\Models\Comment;

trait Comments
{
    public function comments()
    {
        return $this->hasMany(Comment::class, 'created_by');
    }

    public function comment_tags()
    {
        return $this->belongsToMany(Comment::class);
    }
}
