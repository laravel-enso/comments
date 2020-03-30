<?php

namespace LaravelEnso\Comments\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Comments\App\Models\Comment;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Comment $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        return ['count' => $comment->commentable->comments()->count()];
    }
}
