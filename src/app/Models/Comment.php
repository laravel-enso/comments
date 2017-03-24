<?php

namespace LaravelEnso\CommentsManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\App\Traits\UpdatedBy;

class Comment extends Model
{
    use UpdatedBy;

    public function user()
    {
        return $this->belongsTo('LaravelEnso\Core\App\Models\User');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function tagged_users()
    {
        return $this->belongsToMany('LaravelEnso\Core\App\Models\User');
    }
}
