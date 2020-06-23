<?php

namespace LaravelEnso\Comments\Traits;

use Illuminate\Support\Facades\Config;
use LaravelEnso\Comments\Exceptions\CommentConflict;
use LaravelEnso\Comments\Models\Comment;

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
        $restricted = Config::get('enso.comments.onDelete') === 'restrict';

        if ($restricted && $this->comments()->exists()) {
            throw CommentConflict::delete();
        }
    }

    private function cascadeCommentDeletion()
    {
        if (Config::get('enso.comments.onDelete') === 'cascade') {
            $this->comments()->delete();
        }
    }
}
