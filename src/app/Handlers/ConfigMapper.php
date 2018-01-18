<?php

namespace LaravelEnso\CommentsManager\app\Handlers;

use CommentConfigException;

class ConfigMapper
{
    protected $commentable;
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
                ['entity' => $type]
            ));
        }

        return $commentable;
    }
}
