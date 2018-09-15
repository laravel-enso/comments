<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Http\Resources\Comment as Resource;
use LaravelEnso\CommentsManager\app\Http\Requests\ValidateCommentRequest;

class CommentController extends Controller
{
    public function index(ValidateCommentRequest $request)
    {
        return Resource::collection(
            Comment::with(['createdBy', 'updatedBy', 'taggedUsers'])
                ->ordered()
                ->for($request->validated())
                ->get()
            );
    }

    public function update(ValidateCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->updateWithTags($request->validated());

        return new Resource($comment->load(['createdBy', 'taggedUsers']));
    }

    public function store(ValidateCommentRequest $request, Comment $comment)
    {
        $comment = $comment->createWithTags(
            $request->validated()
        );

        return [
            'comment' => new Resource($comment->load(['createdBy', 'taggedUsers'])),
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
