<?php

namespace LaravelEnso\Comments\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Helpers\App\Contracts\TransformsMorphMap;
use LaravelEnso\Helpers\App\Traits\TransformMorphMap;

class ValidateCommentFetch extends FormRequest implements TransformsMorphMap
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
            'commentable_id' => 'required',
            'commentable_type' => 'required|string',
        ];
    }
}
