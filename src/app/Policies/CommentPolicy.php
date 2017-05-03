<?php

namespace LaravelEnso\CommentsManager\app\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\CommentsManager\app\Models\Comment;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create comments.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return request()->user()->isAdmin() || $this->user_id === request()->user()->id;
    }

    /**
     * Determine whether the user can update the comment.
     *
     * @param \App\User    $user
     * @param \App\Comment $comment
     *
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return request()->user()->isAdmin() || $this->user_id === request()->user()->id;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param \App\User    $user
     * @param \App\Comment $comment
     *
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return request()->user()->isAdmin() || $this->user_id === request()->user()->id;
    }
}
