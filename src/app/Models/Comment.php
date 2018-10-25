<?php

namespace LaravelEnso\CommentsManager\app\Models;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;
use LaravelEnso\ActivityLog\app\Traits\LogsActivity;
use LaravelEnso\CommentsManager\app\Contracts\NotifiesTaggedUsers;

class Comment extends Model
{
    use CreatedBy, UpdatedBy, LogsActivity;

    protected $fillable = ['commentable_id', 'commentable_type', 'body'];

    protected $loggableLabel = 'body';

    protected $loggable = ['body'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function taggedUsers()
    {
        return $this->belongsToMany(User::class);
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
        $this->taggedUsers()
            ->sync(
                collect($attributes['taggedUsers'])->pluck('id')
            );

        $this->notify($attributes['path']);

        return $this;
    }

    public function notify($path)
    {
        $this->fresh()
            ->taggedUsers
            ->each
            ->notify(
                app()->makeWith(NotifiesTaggedUsers::class, [
                    'commentable' => $this->commentable, 'body' => $this->body, 'path' => $path,
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
