<?php

namespace LaravelEnso\CommentsManager\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;

class Comment extends Model
{
    use UpdatedBy;

    protected $fillable = ['user_id', 'body', 'is_edited'];

    public function user()
    {
        return $this->belongsTo('LaravelEnso\Core\app\Models\User');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function tagged_users()
    {
        return $this->belongsToMany('LaravelEnso\Core\app\Models\User');
    }
}
