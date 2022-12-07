<?php

namespace LaravelEnso\Comments\Http\Requests;

use LaravelEnso\Helpers\Traits\FiltersRequest;

class ValidateCommentStore extends ValidateCommentFetch
{
    use FiltersRequest;

    public function rules()
    {
        return parent::rules() + [
            'body'        => 'required',
            'path'        => 'required',
            'taggedUsers' => 'array',
        ];
    }
}
