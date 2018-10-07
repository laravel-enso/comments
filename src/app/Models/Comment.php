<?php

namespace LaravelEnso\CommentsManager\app\Models;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;
use LaravelEnso\ActivityLog\app\Traits\LogsActivity;
use LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification;

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

    public function updateWithTags(array $request)
    {
        \DB::transaction(function () use ($request) {
            $this->update(['body' => $request['body']]);
            $this->syncTags(
                collect($request['taggedUsers'])
                    ->pluck('id')
            );
        });

        $this->notifyTaggedUsers($this, $request['path']);
    }

    public function store(array $request)
    {
        $comment = null;

        \DB::transaction(function () use (&$comment, $request) {
            $comment = self::create($request);

            $comment->syncTags(
                collect($request['taggedUsers'])
                    ->pluck('id')
            );
        });

        $this->notifyTaggedUsers($comment, $request['path']);

        return $comment;
    }

    public function syncTags($tags)
    {
        $this->taggedUsers()
            ->sync($tags);
    }

    private function notifyTaggedUsers($comment, $path)
    {
        $notification = class_exists(App\Notifications\CommentTagNotification::class)
            ? App\Notifications\CommentTagNotification::class
            : CommentTagNotification::class;

        $comment->fresh()->taggedUsers
            ->each
            ->notify(new $notification(
                $comment->commentable,
                $comment->body,
                $path
            ));
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
