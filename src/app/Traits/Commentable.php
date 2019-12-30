<?php

namespace LaravelEnso\Comments\App\Traits;

use LaravelEnso\Comments\App\Exceptions\CommentConflict;
use LaravelEnso\Comments\App\Models\Comment;

trait Commentable
{
    public static function bootCommentable()
    {
        self::deleting(fn ($model) => $model->attemptDelete());

        self::deleted(fn ($model) => $model->cascadeDelete());
    }

    public function comment()
    {
        return $this->morphOne(Comment::class, 'commentable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    private function attemptDelete()
    {
        if (config('enso.comments.onDelete') === 'restrict'
            && $this->comments()->first() !== null) {
            throw CommentConflict::delete();
        }
    }

    private function cascadeDelete()
    {
        if (config('enso.comments.onDelete') === 'cascade') {
            $this->comments()->delete();
        }
    }
}
