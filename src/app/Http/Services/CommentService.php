<?php

namespace LaravelEnso\CommentsManager\app\Http\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelEnso\CommentsManager\app\DAOs\Comments;
use LaravelEnso\CommentsManager\app\DAOs\Tags;
use LaravelEnso\CommentsManager\app\Models\Comment;

class CommentService extends Controller
{
    private $request;
    private $comments;
    private $tags;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->comments = new Comments($this->request->all());
        $this->tags = new Tags($this->request->all());
    }

    public function index()
    {
        return $this->comments->index();
    }

    public function update(Comment $comment)
    {
        \DB::transaction(function () use ($comment) {
            $this->comments->update($comment);
            $this->tags->update($comment);
        });

        $this->notifyTaggedUsers($comment, $this->request->get('url'));

        return ['comment' => $comment];
    }

    public function store()
    {
        $comment = null;

        \DB::transaction(function () use (&$comment) {
            $comment = $this->comments->store();
            $this->tags->update($comment);
            $this->notifyTaggedUsers($comment, $this->request->get('url'));
        });

        return [
            'comment' => $comment,
            'count'   => $comment->commentable->comments()->count(),
        ];
    }

    public function destroy(Comment $comment)
    {
        $this->comments->destroy($comment);
    }

    private function notifyTaggedUsers(Comment $comment, string $url)
    {
        $comment->tagged_users->each->notify(
            class_exists(\App\Notifications\CommentTagNotification::class) ?
            new \App\Notifications\CommentTagNotification($comment->commentable, $comment->body, $url) :
            new \LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification($comment->commentable, $comment->body, $url)
        );
    }
}
