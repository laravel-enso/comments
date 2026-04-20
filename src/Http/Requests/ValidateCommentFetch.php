<?php

namespace LaravelEnso\Comments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Helpers\Traits\TransformMorphMap;

class ValidateCommentFetch extends FormRequest
{
    use TransformMorphMap;

    public function morphType(): string
    {
        return 'commentable_type';
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'commentable_id'   => 'required',
            'commentable_type' => 'required|string',
        ];
    }
}
