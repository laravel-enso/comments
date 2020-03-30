<?php

namespace LaravelEnso\Comments\App\Traits;

use LaravelEnso\Comments\App\Models\Comment;

trait Comments
{
    public function comments()
    {
        return $this->hasMany(Comment::class, 'created_by');
    }

    public function commentTags()
    {
        return $this->belongsToMany(Comment::class);
    }
}
