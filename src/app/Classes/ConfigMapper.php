<?php

namespace LaravelEnso\CommentsManager\app\Classes;

use LaravelEnso\CommentsManager\app\Exceptions\CommentConfigException;

class ConfigMapper
{
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function class()
    {
        $commentable = config('enso.comments.commentables.'.$this->type);

        if (is_null($commentable)) {
            throw new CommentConfigException(__(
                'Entity ":entity" does not exist in enso/comments.php config file',
                ['entity' => $this->type]
            ));
        }

        return $commentable;
    }
}
