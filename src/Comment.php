<?php

namespace LaravelEnso\CommentsManager;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\Traits\UpdatedBy;

class Comment extends Model
{
    use UpdatedBy;

    public function user()
    {
        return $this->belongsTo('LaravelEnso\Core\Models\User');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function tagged_users()
    {
        return $this->belongsToMany('LaravelEnso\Core\Models\User');
    }
}
