<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\CommentsManager\app\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\CommentsManager\app\Http\Resources\Comment as Resource;
use LaravelEnso\CommentsManager\app\Http\Requests\ValidateCommentRequest;
use LaravelEnso\CommentsManager\app\Http\Requests\ValidateCommentIndexRequest;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function index(ValidateCommentIndexRequest $request)
    {
        return Resource::collection(
            Comment::with(['createdBy.person', 'updatedBy', 'taggedUsers'])
                ->ordered()
                ->for($request->validated())
                ->get()
            );
    }

    public function update(ValidateCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->updateWithTags($request->validated());

        return new Resource($comment->load(['createdBy.person', 'taggedUsers.person']));
    }

    public function store(ValidateCommentRequest $request, Comment $comment)
    {
        $comment = $comment
            ->store($request->validated());

        return [
            'comment' => new Resource(
                $comment->load(['createdBy', 'taggedUsers'])
            ),
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
