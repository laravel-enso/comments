<?php

namespace LaravelEnso\Comments\app\Traits;

use LaravelEnso\Comments\app\Models\Comment;

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
