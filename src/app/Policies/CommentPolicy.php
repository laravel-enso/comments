<?php

namespace LaravelEnso\CommentsManager\app\Policies;

use Carbon\Carbon;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\CommentsManager\app\Models\Comment;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function update(User $user, Comment $comment)
    {
        return $this->userOwnsComment($user, $comment)
            && $this->commentIsRecent($comment);
    }

    public function destroy(User $user, Comment $comment)
    {
        return $this->userOwnsComment($user, $comment)
            && $this->commentIsRecent($comment);
    }

    private function userOwnsComment(User $user, Comment $comment)
    {
        return $user->id === intval($comment->created_by);
    }

    private function commentIsRecent(Comment $comment)
    {
        return $comment->created_at->diffInHours(Carbon::now()) < config('enso.comments.editableTimeLimitInHours');
    }
}
