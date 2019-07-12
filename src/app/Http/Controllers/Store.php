<?php

namespace LaravelEnso\Comments\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Comments\app\Models\Comment;
use LaravelEnso\Comments\app\Http\Requests\ValidateCommentStore;
use LaravelEnso\Comments\app\Http\Resources\Comment as Resource;

class Store extends Controller
{
    public function __invoke(ValidateCommentStore $request, Comment $comment)
    {
        $comment = Comment::create($request->except('taggedUsers'))
            ->syncTags($request->only('taggedUsers', 'path'));

        return new Resource($comment->load([
                'createdBy.person', 'createdBy.avatar', 'taggedUsers.person',
        ]));
    }
}
