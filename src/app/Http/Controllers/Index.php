<?php

namespace LaravelEnso\Comments\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Comments\app\Http\Requests\ValidateCommentFetch;
use LaravelEnso\Comments\app\Http\Resources\Comment as Resource;
use LaravelEnso\Comments\app\Models\Comment;

class Index extends Controller
{
    public function __invoke(ValidateCommentFetch $request)
    {
        return Resource::collection(
            Comment::with(
                'createdBy.person', 'createdBy.avatar', 'updatedBy', 'taggedUsers'
            )->ordered()
            ->for($request->validated())
            ->get()
        )->additional([
            'humanReadableDates' => config('enso.comments.humanReadableDates'),
        ]);
    }
}
