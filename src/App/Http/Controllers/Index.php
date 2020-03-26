<?php

namespace LaravelEnso\Comments\App\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Comments\App\Http\Requests\ValidateCommentFetch;
use LaravelEnso\Comments\App\Http\Resources\Comment as Resource;
use LaravelEnso\Comments\App\Models\Comment;

class Index extends Controller
{
    public function __invoke(ValidateCommentFetch $request)
    {
        return Resource::collection(
            Comment::with('createdBy.person', 'createdBy.avatar', 'updatedBy', 'taggedUsers')
                ->ordered()
                ->for($request->validated())
                ->get()
            )->additional([
                'humanReadableDates' => config('enso.comments.humanReadableDates'),
            ]);
    }
}
