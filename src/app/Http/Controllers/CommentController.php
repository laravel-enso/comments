<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelEnso\CommentsManager\app\Models\Comment;
use LaravelEnso\CommentsManager\app\Http\Services\CommentService;
use LaravelEnso\CommentsManager\app\Http\Requests\ValidateCommentRequest;

class CommentController extends Controller
{
    public function index(Request $request, CommentService $service)
    {
        return $service->index($request);
    }

    public function update(ValidateCommentRequest $request, Comment $comment, CommentService $service)
    {
        $this->authorize('update', $comment);

        return $service->update($request, $comment);
    }

    public function store(ValidateCommentRequest $request, Comment $comment, CommentService $service)
    {
        return $service->store($request, $comment);
    }

    public function destroy(Comment $comment, CommentService $service)
    {
        $this->authorize('destroy', $comment);

        return $service->destroy($comment);
    }
}
