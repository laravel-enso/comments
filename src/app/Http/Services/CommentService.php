<?php

namespace LaravelEnso\CommentsManager\app\Http\Services;

use Illuminate\Http\Request;
use LaravelEnso\CommentsManager\app\DAOs\Tags;
use LaravelEnso\CommentsManager\app\DAOs\Comments;
use LaravelEnso\CommentsManager\app\Models\Comment;

class CommentService
{
    private $comments;
    private $tags;

    public function __construct()
    {
        $this->comments = new Comments();
        $this->tags = new Tags();
    }

    public function index(Request $request)
    {
        return $this->comments->index($request->all());
    }

    public function update(Request $request, Comment $comment)
    {
        \DB::transaction(function () use ($request, $comment) {
            $this->comments->update($request->all(), $comment);
            $this->tags->update($request->all(), $comment);
        });

        $this->notifyTaggedUsers($comment, $request->get('path'));

        return ['comment' => $comment];
    }

    public function store(Request $request, Comment $comment)
    {
        \DB::transaction(function () use ($request, &$comment) {
            $comment = $this->comments->store($request->all());
            $this->tags->update($request->all(), $comment);
            $this->notifyTaggedUsers($comment, $request->get('path'));
        });

        return [
            'comment' => $comment,
            'count' => $comment->commentable->comments()->count(),
        ];
    }

    public function destroy(Comment $comment)
    {
        $this->comments->destroy($comment);
    }

    private function notifyTaggedUsers(Comment $comment, $path)
    {
        $comment->taggedUsers->each->notify(
            class_exists(\App\Notifications\CommentTagNotification::class) ?
            new \App\Notifications\CommentTagNotification($comment->commentable, $comment->body, $path) :
            new \LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification($comment->commentable, $comment->body, $path)
        );
    }
}
