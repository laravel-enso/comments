<?php

namespace LaravelEnso\CommentsManager\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'body' => 'required',
            'path' => 'required',
            'taggedUsers' => 'array',
        ];

        if ($this->method() === 'POST') {
            $rules = $rules + [
                'commentable_id' => 'required',
                'commentable_type' => 'required|string',
            ];
        }

        return $rules;
    }
}
