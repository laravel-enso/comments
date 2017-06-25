<?php

namespace LaravelEnso\CommentsManager\app\Traits;

trait Comments
{
	public function comments()
    {
        return $this->hasMany('LaravelEnso\CommentsManager\app\Models\Comment', 'created_by');
    }

    public function comment_tags()
	{
	    return $this->belongsToMany('LaravelEnso\CommentsManager\app\Models\Comment');
	}
}


