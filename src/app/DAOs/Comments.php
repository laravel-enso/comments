<?php

namespace LaravelEnso\CommentsManager\app\DAOs;

use LaravelEnso\CommentsManager\app\Models\Comment;

class Comments
{
    private $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $commentable = $this->getCommentable();

        return [
            'count' => $commentable->comments()->count(),
            'comments'  => $commentable->comments()->orderBy('created_at', 'desc')
                            ->skip($this->request['offset'])
                            ->take($this->request['paginate'])
                            ->get(),
        ];
    }

    public function update(Comment $comment)
    {
        $comment->update($this->request);
    }

    public function store()
    {
        $comment = new Comment(['body' => $this->request['body']]);
        $this->getCommentable()->comments()->save($comment);

        return $comment;
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
    }

    private function getCommentable()
    {
        return $this->getCommentableClass()::find($this->request['id']);
    }

    private function getCommentableClass()
    {
        $class = config('comments.commentables.'.$this->request['type']);

        if (!$class) {
            throw new \EnsoException(
                __('Current entity does not exist in comments.php config file: ').$this->request['type']
            );
        }

        return $class;
    }
}
