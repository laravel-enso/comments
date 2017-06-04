<?php

namespace LaravelEnso\CommentsManager\app\Policies;

use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\CommentsManager\app\Models\Comment;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->isAdmin();
    }

    public function create(User $user, Comment $comment)
    {
        return true;
    }

    public function update(User $user, Comment $comment)
    {
        return false;

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
        return $user->id === $comment->user_id;
    }

    private function commentIsRecent(Comment $comment)
    {
        return $comment->created_at->diffInHours(Carbon::now()) < config('comments.editableTimeLimitInHours');
    }
}
