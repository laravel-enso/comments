<?php

namespace LaravelEnso\Comments\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Comments\App\Http\Requests\ValidateCommentUpdate;
use LaravelEnso\Comments\App\Http\Resources\Comment as Resource;
use LaravelEnso\Comments\App\Models\Comment;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateCommentUpdate $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        tap($comment)->update($request->only('body'))
            ->syncTags($request->get('taggedUsers'))
            ->notify($request->get('path'));

        return new Resource($comment->load([
            'createdBy.person', 'createdBy.avatar', 'taggedUsers.person',
        ]));
    }
}
