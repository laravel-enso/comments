<?php

namespace LaravelEnso\Comments\App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Comments\App\Notifications\CommentTagNotification;
use LaravelEnso\Core\App\Models\User;
use LaravelEnso\Helpers\App\Traits\UpdatesOnTouch;
use LaravelEnso\TrackWho\App\Traits\CreatedBy;
use LaravelEnso\TrackWho\App\Traits\UpdatedBy;

class Comment extends Model
{
    use CreatedBy, UpdatedBy, UpdatesOnTouch;

    protected $fillable = ['commentable_id', 'commentable_type', 'body'];

    protected $touches = ['commentable'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function taggedUsers()
    {
        return $this->belongsToMany(User::class);
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

    public function syncTags(array $taggedUsers)
    {
        $this->taggedUsers()->sync(
            collect($taggedUsers)->pluck('id')
        );

        return $this;
    }

    public function notify(string $path)
    {
        $this->taggedUsers->each(fn ($user) => $user->notify(
            (new CommentTagNotification($this->body, $path))
                ->locale($user->lang())
                ->onQueue('notifications')
        ));
    }

    public function getLoggableMorph()
    {
        return config('enso.comments.loggableMorph');
    }
}
