<?php

namespace LaravelEnso\Comments\App\Traits;

use LaravelEnso\Comments\App\Exceptions\CommentConflict;
use LaravelEnso\Comments\App\Models\Comment;

trait Commentable
{
    public static function bootCommentable()
    {
        self::deleting(fn ($model) => $model->attemptCommentableDeletion());

        self::deleted(fn ($model) => $model->cascadeCommentDeletion());
    }

    public function comment()
    {
        return $this->morphOne(Comment::class, 'commentable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    private function attemptCommentableDeletion()
    {
        if (config('enso.comments.onDelete') === 'restrict'
            && $this->comments()->exists()) {
            throw CommentConflict::delete();
        }
    }

    private function cascadeCommentDeletion()
    {
        if (config('enso.comments.onDelete') === 'cascade') {
            $this->comments()->delete();
        }
    }
}
