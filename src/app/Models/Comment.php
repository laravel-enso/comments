<?php

namespace LaravelEnso\CommentsManager\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;
use LaravelEnso\ActivityLog\app\Traits\LogActivity;
use LaravelEnso\CommentsManager\app\Classes\ConfigMapper;
use LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification;

class Comment extends Model
{
    use CreatedBy, UpdatedBy, LogActivity;

    protected $fillable = ['commentable_id', 'commentable_type', 'body'];

    protected $appends = ['taggedUserList', 'owner', 'isEditable', 'isDeletable'];

    protected $loggableLabel = 'body';

    protected $loggable = ['body'];

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

    public function getOwnerAttribute()
    {
        $owner = [
            'fullName' => $this->createdBy->fullName,
            'avatarId' => $this->createdBy->avatarId,
        ];

        unset($this->createdBy);

        return $owner;
    }

    public function getIsEditableAttribute()
    {
        return request()->user()
            && request()->user()->can('update', $this);
    }

    public function getIsDeletableAttribute()
    {
        return request()->user()
            && request()->user()->can('destroy', $this);
    }

    public function getTaggedUserListAttribute()
    {
        $taggedUsers = $this->taggedUsers
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'fullName' => $user->fullName,
                ];
            });

        unset($this->taggedUsers);

        return $taggedUsers;
    }

    public function updateWithTags(array $request)
    {
        \DB::transaction(function () use ($request) {
            $this->update(['body' => $request['body']]);
            $this->syncTags(
                collect($request['taggedUserList'])
                    ->pluck('id')
            );
        });

        $this->notifyTaggedUsers($this, $request['path']);
    }

    public function createWithTags(array $request)
    {
        $comment = null;

        \DB::transaction(function () use (&$comment, $request) {
            $comment = $this->create([
                'body' => $request['body'],
                'commentable_id' => $request['commentable_id'],
                'commentable_type' => (new ConfigMapper($request['commentable_type']))
                                        ->model(),
            ]);

            $comment->syncTags(
                collect($request['taggedUserList'])
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

    public function scopeFor($query, array $request)
    {
        $query->whereCommentableId($request['commentable_id'])
            ->whereCommentableType(
                (new ConfigMapper($request['commentable_type']))
                    ->model()
            );
    }

    public function getLoggableMorph()
    {
        return config('enso.comments.loggableMorph');
    }
}
