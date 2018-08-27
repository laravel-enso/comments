<?php

namespace LaravelEnso\CommentsManager\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCommentRequest extends FormRequest
{
    private const CommentableRules = [
        'commentable_id' => 'required',
        'commentable_type' => 'required',
    ];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->method() === 'GET') {
            return self::CommentableRules;
        }

        $rules = [
            'body' => 'required',
            'path' => 'required',
            'taggedUsers' => 'array',
        ];

        if ($this->method() === 'POST') {
            $rules = $rules + self::CommentableRules;
        }

        return $rules;
    }
}
