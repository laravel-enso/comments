<?php

namespace LaravelEnso\Comments\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCommentFetch extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'commentable_id' => 'required',
            'commentable_type' => 'required|string',
        ];
    }
}
