<?php

namespace LaravelEnso\CommentsManager\app\Http\Services;

use Illuminate\Http\Request;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Exceptions\CommentException;

class CommentService
{
    public function index(Request $request)
    {
        $commentable = $this->getCommentableType($request->get('type'))::find($request->get('id'));

        return [
            'count' => $commentable->comments()->count(),
            'comments' => $commentable->comments()->orderBy('created_at', 'desc')
                ->skip($request->get('offset'))
                ->take($request->get('paginate'))
                ->get(),
        ];
    }

    public function update(Request $request, Comment $comment)
    {
        \DB::transaction(function () use ($request, $comment) {
            $comment->update($request->all());

            $this->updateTags($comment, $request->get('taggedUserList'));
        });

        $this->notifyTaggedUsers($comment, $request->get('path'));

        return ['comment' => $comment];
    }

    public function store(Request $request, Comment $comment)
    {
        \DB::transaction(function () use ($request, &$comment) {
            $comment = Comment::create([
                'body' => $request['body'],
                'commentable_id' => $request['id'],
                'commentable_type' => $this->getCommentableType($request['type']),
            ]);

            $this->updateTags($comment, $request->get('taggedUserList'));
        });

        $this->notifyTaggedUsers($comment, $request->get('path'));

        return [
            'comment' => $comment,
            'count' => $comment->commentable->comments()->count(),
        ];
    }

    public function destroy(Comment $comment)
    {
        $count = $comment->commentable->comments()->count();
        $comment->delete();

        return ['count' => $count - 1];
    }

    private function updateTags(Comment $comment, array $taggedUserList)
    {
        $comment->taggedUsers()
            ->sync(collect($taggedUserList)->pluck('id'));
    }

    private function notifyTaggedUsers(Comment $comment, $path)
    {
        $comment->fresh()->taggedUsers->each->notify(
            class_exists(\App\Notifications\CommentTagNotification::class)
                ? new \App\Notifications\CommentTagNotification($comment->commentable, $comment->body, $path)
                : new \LaravelEnso\CommentsManager\app\Notifications\CommentTagNotification($comment->commentable, $comment->body, $path)
        );
    }

    private function getCommentableType(string $type)
    {
        $class = config('enso.comments.commentables.'.$type);

        if (!$class) {
            throw new CommentException(__(
                'Entity ":entity" does not exist in enso/comments.php config file',
                ['entity' => $type]
            ));
        }

        return $class;
    }
}
