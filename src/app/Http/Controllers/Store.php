<?php

namespace LaravelEnso\Comments\App\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Comments\App\Http\Requests\ValidateCommentStore;
use LaravelEnso\Comments\App\Http\Resources\Comment as Resource;
use LaravelEnso\Comments\App\Models\Comment;

class Store extends Controller
{
    public function __invoke(ValidateCommentStore $request, Comment $comment)
    {
        $comment->fill($request->except('taggedUsers'));

        tap($comment)->save()
            ->syncTags($request->get('taggedUsers'))
            ->notify($request->get('path'));

        return new Resource($comment->load([
            'createdBy.person', 'createdBy.avatar', 'taggedUsers.person',
        ]));
    }
}
