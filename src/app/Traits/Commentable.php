<?php

namespace LaravelEnso\CommentsManager\app\Traits;

use LaravelEnso\CommentsManager\app\Models\Comment;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

trait Commentable
{
    public static function bootCommentable()
    {
        self::deleting(function ($model) {
            if (config('enso.comments.onDelete') === 'restrict'
                && $model->comments()->first() !== null) {
                throw new ConflictHttpException(
                    __('The entity has comments and cannot be deleted')
                );
            }
        });

        self::deleted(function ($model) {
            if (config('enso.comments.onDelete') === 'cascade') {
                $model->comments()->delete();
            }
        });
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
