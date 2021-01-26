<?php

namespace LaravelEnso\Comments\Observers;

use Illuminate\Support\Facades\Config;
use LaravelEnso\Comments\Exceptions\CommentConflict;

class Observer
{
    private function deleting()
    {
        $restricted = Config::get('enso.comments.onDelete') === 'restrict';

        if ($restricted && $this->comments()->exists()) {
            throw CommentConflict::delete();
        }
    }

    private function deleted()
    {
        if (Config::get('enso.comments.onDelete') === 'cascade') {
            $this->comments()->delete();
        }
    }
}
