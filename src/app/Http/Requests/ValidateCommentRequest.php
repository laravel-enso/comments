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
            'taggedUserList' => 'array'
        ];

        if ($this->method() === 'PATCH') {
            $rules = array_merge($rules, [
                'commentable_id' => 'required',
                'commentable_type' => 'required',
            ]);
        }

        return $rules;
    }
}
