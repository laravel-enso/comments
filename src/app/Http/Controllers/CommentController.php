<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\CommentsManager\app\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\CommentsManager\app\Http\Resources\Comment as Resource;
use LaravelEnso\CommentsManager\app\Http\Requests\ValidateCommentRequest;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function index(ValidateCommentRequest $request)
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

        tap($comment)->update($request->only('body'))
            ->syncTags($request->only('taggedUsers', 'path'));

        return new Resource($comment->load(['createdBy.person', 'taggedUsers.person']));
    }

    public function store(ValidateCommentRequest $request, Comment $comment)
    {
        $comment = Comment::create($request->except('taggedUsers'))
            ->syncTags($request->only('taggedUsers', 'path'));

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
