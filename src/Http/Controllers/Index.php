<?php

namespace LaravelEnso\Comments\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use LaravelEnso\Comments\Http\Requests\ValidateCommentFetch;
use LaravelEnso\Comments\Http\Resources;
use LaravelEnso\Comments\Models\Comment;

class Index extends Controller
{
    public function __invoke(ValidateCommentFetch $request)
    {
        $comments = Comment::ordered()
            ->with('createdBy.person', 'createdBy.avatar')
            ->with('updatedBy', 'taggedUsers')
            ->for($request->validated())
            ->get();

        return Resources\Comment::collection($comments)->additional([
            'humanReadableDates' => Config::get('enso.comments.humanReadableDates'),
        ]);
    }
}
