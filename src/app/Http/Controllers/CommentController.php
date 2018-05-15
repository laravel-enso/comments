<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Http\Responses\CommentsIndex;
use LaravelEnso\CommentsManager\app\Http\Requests\ValidateCommentRequest;

class CommentController extends Controller
{
    public function index()
    {
        return new CommentsIndex();
    }

    public function update(ValidateCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->updateWithTags(
            $request->only(['body', 'path', 'taggedUserList'])
        );

        return ['comment' => $comment];
    }

    public function store(ValidateCommentRequest $request, Comment $comment)
    {
        $comment = $comment->createWithTags(
            $request->only(['body', 'id', 'type', 'path', 'taggedUserList'])
        );

        return [
            'comment' => $comment,
            'count' => $comment->commentable
                ->comments()
                ->count(),
        ];
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);

        $count = $comment->commentable
            ->comments()
            ->count();

        $comment->delete();

        return ['count' => --$count];
    }
}
