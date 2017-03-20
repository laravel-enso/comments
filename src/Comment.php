<?php

namespace LaravelEnso\CommentsManager;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\Traits\UpdatedBy;

/**
 * App\Comment.
 *
 * @property int $id
 * @property int $user_id
 * @property int $commentable_id
 * @property string $commentable_type
 * @property string $body
 * @property bool $is_edited
 * @property int $updated_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \App\User $updatedBy
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereIsEdited($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $tagged_users
 */
class Comment extends Model
{
    use UpdatedBy;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function tagged_users()
    {
        return $this->belongsToMany('App\User');
    }
}
