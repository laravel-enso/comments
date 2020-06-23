<?php

namespace LaravelEnso\Comments\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Comments\Http\Requests\ValidateCommentStore;
use LaravelEnso\Comments\Http\Resources;
use LaravelEnso\Comments\Models\Comment;

class Store extends Controller
{
    public function __invoke(ValidateCommentStore $request, Comment $comment)
    {
        $comment->fill($request->except('taggedUsers'));

        tap($comment)->save()
            ->syncTags($request->get('taggedUsers'))
            ->notify($request->get('path'));

        return new Resources\Comment($comment->load([
            'createdBy.person', 'createdBy.avatar', 'taggedUsers.person',
        ]));
    }
}
