<?php

namespace LaravelEnso\Comments\App\Exceptions;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CommentConflict extends ConflictHttpException
{
    public static function delete()
    {
        return new static(__('The entity has comments and cannot be deleted'));
    }
}
