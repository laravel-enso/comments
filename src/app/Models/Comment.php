<?php

namespace LaravelEnso\Comments\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;
use LaravelEnso\Helpers\app\Traits\UpdateOnTouch;
use LaravelEnso\Comments\app\Notifications\CommentTagNotification;

class Comment extends Model
{
    use CreatedBy, UpdatedBy, UpdateOnTouch;

    protected $fillable = ['commentable_id', 'commentable_type', 'body'];

    protected $touches = ['commentable'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function taggedUsers()
    {
        return $this->belongsToMany(
            config('auth.providers.users.model')
        );
    }

    public function isEditable()
    {
        return request()->user()
            && request()->user()->can('update', $this);
    }

    public function isDeletable()
    {
        return request()->user()
            && request()->user()->can('destroy', $this);
    }

    public function syncTags($attributes)
    {
        $this->taggedUsers()->sync(
            collect($attributes['taggedUsers'])->pluck('id')
        );

        $this->notify($attributes['path']);

        return $this;
    }

    public function notify($path)
    {
        $this->fresh()
            ->taggedUsers
            ->each->notify(
                app()->makeWith(CommentTagNotification::class, [
                    'commentable' => $this->commentable,
                    'body' => $this->body, 'path' => $path,
                ])
            );
    }

    public function scopeFor($query, array $params)
    {
        $query->whereCommentableId($params['commentable_id'])
            ->whereCommentableType($params['commentable_type']);
    }

    public function scopeOrdered($query)
    {
        $query->orderBy('created_at', 'desc');
    }

    public function getLoggableMorph()
    {
        return config('enso.comments.loggableMorph');
    }
}
