<?php

namespace LaravelEnso\Comments\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Comments\Http\Requests\ValidateCommentStore;
use LaravelEnso\Comments\Http\Resources\Comment as Resource;
use LaravelEnso\Comments\Models\Comment;

class Store extends Controller
{
    public function __invoke(ValidateCommentStore $request, Comment $comment)
    {
        $comment->fill($request->validatedExcept('taggedUsers', 'path'));

        tap($comment)->save()
            ->syncTags($request->get('taggedUsers'))
            ->notify($request->get('path'));

        return new Resource($comment->load([
            'createdBy.person', 'createdBy.avatar', 'taggedUsers.person',
        ]));
    }
}
