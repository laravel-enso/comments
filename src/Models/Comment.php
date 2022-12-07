<?php

namespace LaravelEnso\Comments\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use LaravelEnso\Comments\Notifications\CommentTagNotification;
use LaravelEnso\Helpers\Traits\UpdatesOnTouch;
use LaravelEnso\TrackWho\Traits\CreatedBy;
use LaravelEnso\TrackWho\Traits\UpdatedBy;
use LaravelEnso\Users\Models\User;

class Comment extends Model
{
    use CreatedBy;
    use HasFactory;
    use UpdatedBy;
    use UpdatesOnTouch;

    protected $guarded = ['id'];

    protected $touches = ['commentable'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function taggedUsers()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeFor(Builder $query, array $params): Builder
    {
        return $query->whereCommentableId($params['commentable_id'])
            ->whereCommentableType($params['commentable_type']);
    }

    public function syncTags(array $taggedUsers)
    {
        $this->taggedUsers()->sync(
            Collection::wrap($taggedUsers)->pluck('id')
        );

        return $this;
    }

    public function notify(string $path)
    {
        $this->taggedUsers->each
            ->notify((new CommentTagNotification($this->body, $path))
                ->onQueue('notifications'));
    }

    public function getLoggableMorph()
    {
        return config('enso.comments.loggableMorph');
    }
}
