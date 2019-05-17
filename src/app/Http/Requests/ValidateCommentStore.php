<?php

namespace LaravelEnso\Comments\app\Http\Requests;

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
