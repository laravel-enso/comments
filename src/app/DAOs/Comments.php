<?php

namespace LaravelEnso\CommentsManager\app\DAOs;

use LaravelEnso\CommentsManager\app\Models\Comment;

class Comments
{
    public function index(array $request)
    {
        $commentable = $this->getCommentable($request);

        return [
            'count' => $commentable->comments()->count(),
            'comments' => $commentable->comments()->orderBy('created_at', 'desc')
                ->skip($request['offset'])
                ->take($request['paginate'])
                ->get(),
        ];
    }

    public function update(array $request, Comment $comment)
    {
        $comment->update($request);
    }

    public function store(array $request)
    {
        $comment = new Comment(['body' => $request['body']]);
        $this->getCommentable($request)->comments()->save($comment);

        return $comment;
    }

    public function destroy(Comment $comment)
    {
        $commentable = $comment->commentable;
        $comment->delete();

        return ['count' => $commentable->comments()->count()];
    }

    private function getCommentable(array $request)
    {
        return $this->getCommentableClass($request)::find($request['id']);
    }

    private function getCommentableClass(array $request)
    {
        $class = config('enso.comments.commentables.'.$request['type']);

        if (!$class) {
            throw new \EnsoException(
                __('Current entity does not exist in enso/comments.php config file: ').$request['type']
            );
        }

        return $class;
    }
}
