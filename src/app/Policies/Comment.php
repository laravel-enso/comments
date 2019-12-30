<?php

namespace LaravelEnso\Comments\App\Policies;

use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Comments\App\Models\Comment as Model;
use LaravelEnso\Core\App\Models\User;

class Comment
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
            return true;
        }
    }

    public function update(User $user, Model $comment)
    {
        return $this->ownsComment($user, $comment)
            && $this->isRecent($comment);
    }

    public function destroy(User $user, Model $comment)
    {
        return $this->ownsComment($user, $comment)
            && $this->isRecent($comment);
    }

    private function ownsComment(User $user, Model $comment)
    {
        return $user->id === (int) $comment->created_by;
    }

    private function isRecent(Model $comment)
    {
        return $comment->created_at->diffInSeconds(Carbon::now())
            < config('enso.comments.editableTimeLimit');
    }
}
