<?php

namespace LaravelEnso\Comments\App\Http\Requests;

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
