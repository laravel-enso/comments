<?php

namespace LaravelEnso\CommentsManager\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;

class Comment extends Model
{
    use UpdatedBy;

    protected $fillable = ['user_id', 'body', 'is_edited'];
    protected $appends = ['tagged_users_list', 'owner', 'is_editable'];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function tagged_users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'));
    }

    public function getTaggedUsersListAttribute()
    {
        $taggedUsers = collect();
        $this->tagged_users->each(function ($user) use ($taggedUsers) {
            $taggedUsers->push([
                'id'        => $user->id,
                'full_name' => $user->full_name,
            ]);
        });

        unset($this->tagged_users);

        return $taggedUsers;
    }

    public function getOwnerAttribute()
    {
        $attributes = [
            'full_name'   => $this->user->full_name,
            'avatar_link' => $this->user->avatar_link,
        ];

        unset($this->user);

        return $attributes;
    }

    public function getIsEditableAttribute()
    {
        return request()->user()->isAdmin() || $this->user_id === request()->user()->id;
    }
}
