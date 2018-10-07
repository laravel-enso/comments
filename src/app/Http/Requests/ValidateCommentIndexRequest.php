<?php

namespace LaravelEnso\CommentsManager\app\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\CommentsManager\app\Exceptions\CommentException;

class ValidateCommentIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'commentable_id' => 'required',
            'commentable_type' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!class_exists($this->commentable_type)
                || !new $this->commentable_type instanceof Model) {
                throw new CommentException(
                    'The "commentable_type" property must be a valid model class'
                );
            }
        });
    }
}
