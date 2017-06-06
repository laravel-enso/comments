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
        return [
            'body' => 'required',
        ];
    }
}
