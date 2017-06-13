<?php

namespace LaravelEnso\CommentsManager\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;

class Comment extends Model
{
    use CreatedBy, UpdatedBy;

    protected $fillable = ['body'];

    protected $appends = ['tagged_users_list', 'owner', 'is_editable', 'is_deletable', 'is_edited'];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by', 'id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function tagged_users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'));
    }

    public function getIsEditedAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s') !== $this->updated_at->format('Y-m-d H:i:s');
    }

    public function getOwnerAttribute()
    {
        $attribute = [
            'full_name' => $this->user->full_name,
            'avatarId'  => $this->user->avatar ? $this->user->avatar->id : null,
        ];

        unset($this->user);

        return $attribute;
    }

    public function getIsEditableAttribute()
    {
        return request()->user()->can('update', $this);
    }

    public function getIsDeletableAttribute()
    {
        return request()->user()->can('destroy', $this);
    }

    public function getTaggedUsersListAttribute()
    {
        $taggedUsers = $this->tagged_users->map(function ($user) {
            return [
                'id'        => $user->id,
                'full_name' => $user->full_name,
            ];
        });

        unset($this->tagged_users);

        return $taggedUsers;
    }
}
