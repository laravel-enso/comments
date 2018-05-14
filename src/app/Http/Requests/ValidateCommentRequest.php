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
        ];

        if (request()->getMethod() === 'PATCH') {
            array_merge($rules, [
                'id' => 'required',
                'type' => 'required'
            ]);
        }

        return $rules;
    }
}
