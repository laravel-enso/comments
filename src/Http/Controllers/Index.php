<?php

namespace LaravelEnso\Comments\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use LaravelEnso\Comments\Http\Requests\ValidateCommentFetch;
use LaravelEnso\Comments\Http\Resources\Comment as Resource;
use LaravelEnso\Comments\Models\Comment;

class Index extends Controller
{
    public function __invoke(ValidateCommentFetch $request)
    {
        $comments = Comment::latest()->for($request->validated())
            ->with('createdBy.person', 'createdBy.avatar', 'updatedBy', 'taggedUsers')
            ->get();

        return Resource::collection($comments)->additional([
            'humanReadableDates' => Config::get('enso.comments.humanReadableDates'),
        ]);
    }
}
