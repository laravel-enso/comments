<?php

namespace LaravelEnso\CommentsManager\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\CommentsManager\app\Exceptions\CommentException;

class ValidateCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $morphRules = [
            'commentable_id' => 'required',
            'commentable_type' => 'required|string',
        ];

        if ($this->method() === 'GET') {
            return $morphRules;
        }

        $rules = [
            'body' => 'required',
            'path' => 'required',
            'taggedUsers' => 'array',
        ];

        return $this->method() === 'PATCH'
            ? $rules
            : $morphRules + $rules;
    }

    public function withValidator($validator)
    {
        if ($this->method() === 'PATCH') {
            return;
        }

        $validator->after(function ($validator) {
            if (! class_exists($this->get('commentable_type'))) {
                throw new CommentException(
                    'The "commentable_type" property must be a valid model class'
                );
            }
        });
    }
}
