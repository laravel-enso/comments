<?php

namespace LaravelEnso\Comments\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Comments\app\Exceptions\CommentException;

class ValidateCommentFetch extends FormRequest
{
    public function authorize()
    {
        $this->checkParams();

        return true;
    }

    public function rules()
    {
        return [
            'commentable_id' => 'required',
            'commentable_type' => 'required|string',
        ];
    }

    private function checkParams()
    {
        if (! class_exists($this->get('commentable_type'))) {
            throw new CommentException(
                'The "commentable_type" property must be a valid model class'
            );
        }
    }
}
