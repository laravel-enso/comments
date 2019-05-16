<?php

namespace LaravelEnso\Comments\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Comments\app\Exceptions\CommentException;

class ValidateCommentUpdate extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => 'required',
            'path' => 'required',
            'taggedUsers' => 'array',
        ];
    }
}
