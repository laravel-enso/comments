<?php

namespace LaravelEnso\CommentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelEnso\CommentsManager\app\Http\Requests\ValidateCommentRequest;
use LaravelEnso\CommentsManager\app\Http\Services\CommentService;
use LaravelEnso\CommentsManager\app\Models\Comment;

class CommentController extends Controller
{
    private $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function update(ValidateCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        return $this->service->update($request, $comment);
    }

    public function store(ValidateCommentRequest $request, Comment $comment)
    {
        return $this->service->store($request, $comment);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);

        $this->service->destroy($comment);
    }
}
