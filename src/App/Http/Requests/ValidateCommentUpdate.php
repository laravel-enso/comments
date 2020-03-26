<?php

namespace LaravelEnso\Comments\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
