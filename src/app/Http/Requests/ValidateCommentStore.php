<?php

namespace LaravelEnso\Comments\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Comments\app\Exceptions\CommentException;

class ValidateCommentStore extends ValidateCommentFetch
{
    public function rules()
    {
        return parent::rules() + [
            'body' => 'required',
            'path' => 'required',
            'taggedUsers' => 'array',
        ];
    }
}
