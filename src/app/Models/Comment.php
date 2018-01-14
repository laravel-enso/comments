<?php

namespace LaravelEnso\CommentsManager\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;

class Comment extends Model
{
    use CreatedBy, UpdatedBy;

    protected $fillable = ['commentable_id', 'commentable_type', 'body'];

    protected $appends = ['taggedUserList', 'owner', 'isEditable', 'isDeletable', 'isEdited'];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by', 'id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function taggedUsers()
    {
        return $this->belongsToMany(config('auth.providers.users.model'));
    }

    public function getIsEditedAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s') !== $this->updated_at->format('Y-m-d H:i:s');
    }

    public function getOwnerAttribute()
    {
        $owner = [
            'fullName' => $this->user->fullName,
            'avatarId' => $this->user->avatarId,
        ];

        unset($this->user);

        return $owner;
    }

    public function getIsEditableAttribute()
    {
        return request()->user()
            ? request()->user()->can('update', $this)
            : false;
    }

    public function getIsDeletableAttribute()
    {
        return request()->user()
            ? request()->user()->can('destroy', $this)
            : false;
    }

    public function getTaggedUserListAttribute()
    {
        $taggedUsers = $this->taggedUsers->map(function ($user) {
            return [
                'id' => $user->id,
                'fullName' => $user->fullName,
            ];
        });

        unset($this->taggedUsers);

        return $taggedUsers;
    }
}
