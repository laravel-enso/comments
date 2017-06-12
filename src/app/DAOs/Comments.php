<?php

namespace LaravelEnso\CommentsManager\app\DAOs;

use LaravelEnso\CommentsManager\app\Models\Comment;

class Comments
{
    private $commentable;
    private $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $this->commentable = $this->getCommentable();
        $count = $this->commentable->comments()->count();

        $list = $this->commentable->comments()->orderBy('created_at', 'desc')
            ->skip($this->request['offset'])
            ->take($this->request['paginate'])
            ->get();

        return [
            'list'  => $list,
            'count' => $count,
        ];
    }

    public function update(Comment $comment)
    {
        $comment->update($this->request);
    }

    public function store()
    {
        $comment = new Comment(['body' => $this->request['body']]);
        $commentable = $this->getCommentable();
        $commentable->comments()->save($comment);

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
