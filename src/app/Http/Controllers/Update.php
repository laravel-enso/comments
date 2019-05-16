<?php

namespace LaravelEnso\Comments\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Comments\app\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\Comments\app\Http\Resources\Comment as Resource;
use LaravelEnso\Comments\app\Http\Requests\ValidateCommentUpdate;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateCommentUpdate $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        tap($comment)->update($request->only('body'))
            ->syncTags($request->only('taggedUsers', 'path'));

        return new Resource($comment->load([
            'createdBy.person', 'taggedUsers.person',
        ]));
    }
}
