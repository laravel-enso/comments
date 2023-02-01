<?php

namespace LaravelEnso\Comments\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Comments\Http\Requests\ValidateCommentUpdate;
use LaravelEnso\Comments\Http\Resources\Comment as Resource;
use LaravelEnso\Comments\Models\Comment;

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
            'createdBy.person', 'createdBy.avatar', 'updatedBy', 'taggedUsers.person',
        ]));
    }
}
